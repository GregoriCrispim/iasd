<?php

use App\Models\VolunteerApplication;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Normaliza telefones legados para apenas dígitos (até 11) antes do índice único.
        VolunteerApplication::query()->orderBy('id')->each(function (VolunteerApplication $app) {
            $digits = substr(preg_replace('/\D+/', '', (string) $app->phone) ?? '', 0, 11);
            $email = strtolower(trim((string) $app->email));

            $dirty = false;
            if ($digits !== '' && $digits !== $app->phone) {
                $app->phone = $digits;
                $dirty = true;
            }
            if ($email !== '' && $email !== $app->email) {
                $app->email = $email;
                $dirty = true;
            }
            if ($dirty) {
                $app->saveQuietly();
            }
        });

        Schema::table('volunteer_applications', function (Blueprint $table) {
            $table->unique('email', 'volunteer_applications_email_unique');
            $table->unique('phone', 'volunteer_applications_phone_unique');
        });
    }

    public function down(): void
    {
        Schema::table('volunteer_applications', function (Blueprint $table) {
            $table->dropUnique('volunteer_applications_email_unique');
            $table->dropUnique('volunteer_applications_phone_unique');
        });
    }
};
