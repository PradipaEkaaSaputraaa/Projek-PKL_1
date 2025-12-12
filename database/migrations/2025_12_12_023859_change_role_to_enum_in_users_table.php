// database/migrations/*_change_role_to_enum_in_users_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Wajib ditambahkan

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah tipe kolom role menjadi ENUM('user', 'admin')
        // Pastikan nama tabel dan nama kolom sesuai.
        DB::statement("ALTER TABLE users CHANGE role role ENUM('user', 'admin') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Untuk rollback, ubah kembali ke VARCHAR(255)
        DB::statement("ALTER TABLE users CHANGE role role VARCHAR(255) NOT NULL DEFAULT 'user'");
    }
};