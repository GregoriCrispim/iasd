<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->string('name');
            $table->string('destination_route', 120)->default('estudo-biblico');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('campaigns')->insert([
            'slug' => 'sementes',
            'name' => 'Entrega de sementes',
            'destination_route' => 'estudo-biblico',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
