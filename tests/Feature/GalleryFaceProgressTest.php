<?php

namespace Tests\Feature;

use App\Jobs\ProcessGalleryPhotoFaces;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryFaceProgressTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['super_admin', 'member'] as $role) {
            Role::query()->create(['name' => $role, 'guard_name' => 'web']);
        }

        Storage::fake('galeria');
    }

    private function publishedAlbumWithPhotos(array $statuses): GalleryAlbum
    {
        $album = GalleryAlbum::create([
            'title' => 'Culto',
            'slug' => 'culto-'.uniqid(),
            'is_published' => true,
        ]);

        foreach ($statuses as $i => $status) {
            $path = $album->id.'/foto-'.$i.'.jpg';
            Storage::disk('galeria')->put($path, 'fake');
            GalleryPhoto::create([
                'gallery_album_id' => $album->id,
                'path' => $path,
                'original_filename' => 'foto-'.$i.'.jpg',
                'mime_type' => 'image/jpeg',
                'size_bytes' => 10,
                'sort_order' => $i,
                'faces_status' => $status,
            ]);
        }

        return $album->fresh();
    }

    public function test_face_progress_endpoint_reports_percent(): void
    {
        $album = $this->publishedAlbumWithPhotos(['ready', 'pending', 'no_face', 'failed']);

        $this->getJson(route('galeria.faces.progress', $album->slug))
            ->assertOk()
            ->assertJson([
                'total' => 4,
                'scanned' => 3,
                'pending' => 1,
                'percent' => 75,
                'complete' => false,
            ]);
    }

    public function test_face_progress_complete_when_no_pending(): void
    {
        $album = $this->publishedAlbumWithPhotos(['ready', 'no_face']);

        $this->getJson(route('galeria.faces.progress', $album->slug))
            ->assertOk()
            ->assertJson([
                'total' => 2,
                'pending' => 0,
                'percent' => 100,
                'complete' => true,
            ]);
    }

    public function test_public_gallery_hides_search_while_processing(): void
    {
        $album = $this->publishedAlbumWithPhotos(['pending', 'pending']);
        $member = User::create([
            'name' => 'Membro',
            'email' => 'm@ex.com',
            'password' => 'senha12345',
            'birth_date' => '1990-01-01',
            'is_active' => true,
        ]);
        $member->syncRoles(['member']);

        // absolutePath may not exist with Storage::fake; page still renders button state from progress.
        $this->actingAs($member, 'web')
            ->get(route('galeria.show', $album->slug))
            ->assertOk()
            ->assertSee('Processando', false)
            ->assertSee('is-processing', false)
            ->assertDontSee('id="galeriaFaceModal"', false);
    }

    public function test_public_gallery_shows_search_when_album_ready(): void
    {
        $album = $this->publishedAlbumWithPhotos(['ready', 'no_face']);
        $member = User::create([
            'name' => 'Membro',
            'email' => 'm2@ex.com',
            'password' => 'senha12345',
            'birth_date' => '1990-01-01',
            'is_active' => true,
        ]);
        $member->syncRoles(['member']);

        $this->actingAs($member, 'web')
            ->get(route('galeria.show', $album->slug))
            ->assertOk()
            ->assertSee('Reconhecimento Facial', false)
            ->assertDontSee('>Processando…<', false)
            ->assertSee('id="galeriaFaceModal"', false);
    }

    public function test_upload_dispatches_face_processing_job(): void
    {
        Bus::fake();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'a@ex.com',
            'password' => 'senha12345',
            'is_active' => true,
        ]);
        $admin->syncRoles(['super_admin']);

        $album = GalleryAlbum::create([
            'title' => 'Culto',
            'slug' => 'culto-upload-'.uniqid(),
            'is_published' => true,
            'created_by' => $admin->id,
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->image('festa.jpg', 400, 400);

        $this->actingAs($admin, 'admin')
            ->postJson(route('admin.galeria.upload', $album), [
                'photos' => [$file],
            ])
            ->assertOk();

        Bus::assertDispatched(ProcessGalleryPhotoFaces::class);
    }
}
