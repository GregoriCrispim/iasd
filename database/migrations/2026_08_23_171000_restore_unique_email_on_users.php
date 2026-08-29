<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove o legado de contas duplicadas (mesmo e-mail em painel e site)
     * e restaura a unicidade de e-mail.
     */
    public function up(): void
    {
        $panelRoleIds = DB::table('roles')
            ->where('guard_name', 'web')
            ->whereIn('name', [
                'super_admin',
                'manager',
                'collaborator',
                'fotografia_lider',
                'fotografia_colaborador',
                'fotografia', // legado pré-split
            ])
            ->pluck('id');

        $duplicateEmails = DB::table('users')
            ->select('email')
            ->groupBy('email')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('email');

        foreach ($duplicateEmails as $email) {
            $users = DB::table('users')
                ->where('email', $email)
                ->orderBy('id')
                ->get();

            $keeperId = null;
            foreach ($users as $user) {
                $isPanel = DB::table('model_has_roles')
                    ->where('model_type', 'App\\Models\\User')
                    ->where('model_id', $user->id)
                    ->whereIn('role_id', $panelRoleIds)
                    ->exists();

                if ($isPanel) {
                    $keeperId = (int) $user->id;
                    break;
                }
            }

            if ($keeperId === null) {
                $keeperId = (int) $users->first()->id;
            }

            foreach ($users as $user) {
                $discardId = (int) $user->id;
                if ($discardId === $keeperId) {
                    continue;
                }

                $this->reassignUserReferences($discardId, $keeperId);
                $this->deleteUserRow($discardId);
            }
        }

        // Remove índice não-único legado, se existir, antes de recriar o unique.
        $this->dropEmailIndexIfPresent();

        Schema::table('users', function (Blueprint $table) {
            $table->unique('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->index('email');
        });
    }

    private function dropEmailIndexIfPresent(): void
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['email']);
            });
        } catch (\Throwable) {
            // Índice já ausente ou nome diferente no driver — segue para o unique.
        }
    }

    private function reassignUserReferences(int $fromId, int $toId): void
    {
        if (Schema::hasTable('face_search_consents')) {
            $keeperAlbumIds = DB::table('face_search_consents')
                ->where('user_id', $toId)
                ->pluck('gallery_album_id')
                ->all();

            DB::table('face_search_consents')
                ->where('user_id', $fromId)
                ->when(
                    $keeperAlbumIds !== [],
                    fn ($q) => $q->whereNotIn('gallery_album_id', $keeperAlbumIds)
                )
                ->update(['user_id' => $toId]);

            DB::table('face_search_consents')->where('user_id', $fromId)->delete();
        }

        if (Schema::hasTable('cms_page_user')) {
            $keeperPageIds = DB::table('cms_page_user')
                ->where('user_id', $toId)
                ->pluck('cms_page_id')
                ->all();

            DB::table('cms_page_user')
                ->where('user_id', $fromId)
                ->when(
                    $keeperPageIds !== [],
                    fn ($q) => $q->whereNotIn('cms_page_id', $keeperPageIds)
                )
                ->update(['user_id' => $toId]);

            DB::table('cms_page_user')->where('user_id', $fromId)->delete();
        }

        $reassignColumns = [
            ['gallery_albums', 'created_by'],
            ['gallery_photos', 'uploaded_by'],
            ['cms_revisions', 'created_by'],
            ['cms_approvals', 'approver_id'],
            ['users', 'created_by'],
            ['users', 'manager_id'],
        ];

        foreach ($reassignColumns as [$table, $column]) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
                DB::table($table)->where($column, $fromId)->update([$column => $toId]);
            }
        }
    }

    private function deleteUserRow(int $userId): void
    {
        DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->where('model_id', $userId)
            ->delete();

        if (Schema::hasTable('model_has_permissions')) {
            DB::table('model_has_permissions')
                ->where('model_type', 'App\\Models\\User')
                ->where('model_id', $userId)
                ->delete();
        }

        DB::table('users')->where('id', $userId)->delete();
    }
};
