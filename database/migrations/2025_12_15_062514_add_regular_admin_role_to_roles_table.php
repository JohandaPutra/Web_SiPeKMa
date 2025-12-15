<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing 'admin' role menjadi 'super_admin'
        DB::table('roles')->where('name', 'admin')->update([
            'name' => 'super_admin',
            'display_name' => 'Super Administrator',
            'updated_at' => now(),
        ]);

        // Tambah role baru 'admin' biasa
        DB::table('roles')->insert([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus role 'admin' biasa
        DB::table('roles')->where('name', 'admin')->where('display_name', 'Administrator')->delete();

        // Kembalikan 'super_admin' ke 'admin'
        DB::table('roles')->where('name', 'super_admin')->update([
            'name' => 'admin',
            'display_name' => 'Super Administrator',
            'updated_at' => now(),
        ]);
    }
};
