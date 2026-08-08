<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\GalleryFaceDescriptor;
use App\Models\GalleryPhoto;
use App\Models\MemberInvite;
use App\Models\Role;
use App\Models\User;
use App\Services\Face\FaceDescriptorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaceSearchTest extends TestCase
{
    use RefreshDatabase;

    private FaceDescriptorService $descriptors;

    protected function setUp(): void
    {
        parent::setUp();
        $this->descriptors = new FaceDescriptorService;

        foreach (['super_admin', 'member'] as $role) {
            Role::query()->create(['name' => $role, 'guard_name' => 'web']);
        }
    }

    private function member(?string $birth = '1990-01-01'): User
    {
        $user = User::create([
            'name' => 'Membro',
            'email' => 'm'.uniqid().'@ex.com',
            'password' => 'senha12345',
            'birth_date' => $birth,
            'is_active' => true,
        ]);
        $user->syncRoles(['member']);

        return $user;
    }

    private function baseVector(): array
    {
        $v = [];
        for ($i = 0; $i < 128; $i++) {
            $v[] = 0.01 * $i;
        }

        return $v;
    }

    private function seedAlbum(bool $published = true): array
    {
        $album = GalleryAlbum::create([
            'title' => 'Culto',
            'slug' => 'culto-'.uniqid(),
            'is_published' => $published,
        ]);

        $near = GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'path' => $album->id.'/near.webp',
            'original_filename' => 'near.jpg',
            'mime_type' => 'image/webp',
            'size_bytes' => 1000,
            'faces_status' => 'ready',
        ]);
        $far = GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'path' => $album->id.'/far.webp',
            'original_filename' => 'far.jpg',
            'mime_type' => 'image/webp',
            'size_bytes' => 1000,
            'faces_status' => 'ready',
        ]);

        $base = $this->baseVector();
        $farVector = array_map(fn ($x) => $x + 1.0, $base); // distância grande

        $version = (string) config('face.version', 'v1');

        // Duas faces na mesma foto "near" para validar deduplicação por foto.
        foreach ([0, 1] as $idx) {
            GalleryFaceDescriptor::create([
                'gallery_album_id' => $album->id,
                'gallery_photo_id' => $near->id,
                'face_index' => $idx,
                'model_version' => $version,
                'descriptor' => $this->descriptors->encrypt($base),
            ]);
        }
        GalleryFaceDescriptor::create([
            'gallery_album_id' => $album->id,
            'gallery_photo_id' => $far->id,
            'face_index' => 0,
            'model_version' => $version,
            'descriptor' => $this->descriptors->encrypt($farVector),
        ]);

        return [$album, $near, $far];
    }

    private function consentPayload(array $override = []): array
    {
        return array_merge([
            'descriptor' => $this->baseVector(),
            'source' => 'upload',
            'consent_self' => 1,
            'consent_biometric' => 1,
            'consent_limitations' => 1,
        ], $override);
    }

    public function test_unauthenticated_search_is_rejected(): void
    {
        [$album] = $this->seedAlbum();

        $this->postJson(route('galeria.busca-facial', $album->slug), $this->consentPayload())
            ->assertUnauthorized();
    }

    public function test_unused_invite_never_authorizes_face_search_even_after_deletion(): void
    {
        [$album] = $this->seedAlbum();
        $generated = MemberInvite::generateCode();
        $invite = MemberInvite::create([
            'code_hash' => $generated['hash'],
            'code_prefix' => $generated['prefix'],
            'max_uses' => 1,
            'uses_count' => 0,
            'is_active' => true,
        ]);

        $this->postJson(route('galeria.busca-facial', $album->slug), $this->consentPayload())
            ->assertUnauthorized();

        $invite->delete();

        $this->postJson(route('galeria.busca-facial', $album->slug), $this->consentPayload())
            ->assertUnauthorized();
    }

    public function test_administrator_without_member_role_cannot_search(): void
    {
        [$album] = $this->seedAlbum();
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@ex.com',
            'password' => 'senha12345',
            'is_active' => true,
        ]);
        $admin->syncRoles(['super_admin']);

        $this->actingAs($admin, 'admin')
            ->postJson(route('galeria.busca-facial', $album->slug), $this->consentPayload())
            ->assertUnauthorized();

        $this->actingAs($admin, 'web')
            ->postJson(route('galeria.busca-facial', $album->slug), $this->consentPayload())
            ->assertForbidden();
    }

    public function test_search_returns_matching_photo_ids_only(): void
    {
        [$album, $near, $far] = $this->seedAlbum();

        $response = $this->actingAs($this->member())
            ->postJson(route('galeria.busca-facial', $album->slug), $this->consentPayload());

        $response->assertOk();
        $ids = $response->json('photo_ids');

        $this->assertContains($near->id, $ids);
        $this->assertNotContains($far->id, $ids);
        // Deduplicação: apesar de duas faces na foto near, o id aparece uma vez.
        $this->assertCount(1, array_keys($ids, $near->id, true));
    }

    public function test_response_never_leaks_biometrics(): void
    {
        [$album] = $this->seedAlbum();

        $response = $this->actingAs($this->member())
            ->postJson(route('galeria.busca-facial', $album->slug), $this->consentPayload());

        $body = $response->getContent();
        $this->assertStringNotContainsString('descriptor', $body);
        $this->assertArrayNotHasKey('matches', $response->json());
    }

    public function test_consent_is_required(): void
    {
        [$album] = $this->seedAlbum();

        $this->actingAs($this->member())
            ->postJson(route('galeria.busca-facial', $album->slug), $this->consentPayload(['consent_biometric' => 0]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('consent_biometric');
    }

    public function test_minor_requires_guardian_declaration(): void
    {
        [$album] = $this->seedAlbum();
        $minor = $this->member(now()->subYears(14)->format('Y-m-d'));

        $this->actingAs($minor)
            ->postJson(route('galeria.busca-facial', $album->slug), $this->consentPayload())
            ->assertStatus(422)
            ->assertJsonValidationErrors('guardian_declaration');
    }

    public function test_unpublished_album_is_not_searchable(): void
    {
        [$album] = $this->seedAlbum(published: false);

        $this->actingAs($this->member())
            ->postJson(route('galeria.busca-facial', $album->slug), $this->consentPayload())
            ->assertNotFound();
    }

    public function test_invalid_descriptor_size_is_rejected(): void
    {
        [$album] = $this->seedAlbum();

        $this->actingAs($this->member())
            ->postJson(route('galeria.busca-facial', $album->slug), $this->consentPayload(['descriptor' => array_fill(0, 10, 0.1)]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('descriptor');
    }
}
