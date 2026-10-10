<?php

use App\Models\VolunteerApplication;
use App\Support\PersonNameFormatter;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        VolunteerApplication::query()->orderBy('id')->each(function (VolunteerApplication $app) {
            $formatted = PersonNameFormatter::format((string) $app->name);
            if ($formatted !== '' && $formatted !== $app->name) {
                $app->name = $formatted;
                $app->saveQuietly();
            }
        });
    }

    public function down(): void
    {
        // Irreversível: capitalização de nomes não possui forma canônica anterior.
    }
};
