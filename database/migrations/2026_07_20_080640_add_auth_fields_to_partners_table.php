<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus data partner dummy lama — event yang nempel otomatis jadi
        // partner_id NULL (aman, karena foreign key-nya nullOnDelete)
        DB::table('partners')->delete();

        Schema::table('partners', function (Blueprint $table) {
            $table->string('email')->unique()->after('name');
            $table->string('password')->after('email');
            $table->string('status')->default('active')->after('logo_url');
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn(['email', 'password', 'status', 'remember_token']);
        });
    }
};