<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Eslatma: 'email' ustuni ataylab ->change() qilinmadi (SQLite'da bu doctrine/dbal
        // paketini talab qiladi, u loyihada o'rnatilmagan). O'rniga har bir userga
        // (telefon orqali ro'yxatdan o'tganlarga ham) sintetik unique email beriladi
        // — UserSeeder va RegisterController shu qoidaga amal qiladi.
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->unique()->after('name');
            $table->string('role')->default('parent')->after('phone'); // parent|institution|admin
            $table->unsignedSmallInteger('age')->nullable()->after('role');
            $table->foreignId('district_id')->nullable()->after('age')->constrained()->nullOnDelete();
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            $table->timestamp('last_login_at')->nullable()->after('phone_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('district_id');
            $table->dropColumn(['phone', 'role', 'age', 'phone_verified_at', 'last_login_at']);
        });
    }
};
