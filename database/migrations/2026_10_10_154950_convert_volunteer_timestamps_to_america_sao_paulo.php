<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Converte timestamps gravados sob APP_TIMEZONE=UTC para America/Sao_Paulo.
     */
    public function up(): void
    {
        $this->convertTable('volunteer_applications');
        $this->convertTable('volunteer_application_choices');
    }

    public function down(): void
    {
        $this->convertTable('volunteer_applications', reverse: true);
        $this->convertTable('volunteer_application_choices', reverse: true);
    }

    private function convertTable(string $table, bool $reverse = false): void
    {
        $from = $reverse ? 'America/Sao_Paulo' : 'UTC';
        $to = $reverse ? 'UTC' : 'America/Sao_Paulo';

        DB::table($table)->orderBy('id')->each(function (object $row) use ($table, $from, $to) {
            $updates = [];

            foreach (['created_at', 'updated_at'] as $column) {
                $raw = $row->{$column} ?? null;
                if (! is_string($raw) || $raw === '') {
                    continue;
                }

                $updates[$column] = Carbon::createFromFormat('Y-m-d H:i:s', $raw, $from)
                    ->setTimezone($to)
                    ->format('Y-m-d H:i:s');
            }

            if ($updates !== []) {
                DB::table($table)->where('id', $row->id)->update($updates);
            }
        });
    }
};
