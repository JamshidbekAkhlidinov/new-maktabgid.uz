<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('type'); // maktab|bogcha|markaz|mutaxassis
            $table->text('about')->nullable();
            $table->string('lang')->nullable();
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
            $table->string('address')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->unsignedBigInteger('monthly_price')->nullable(); // null = kelishilgan
            $table->string('grades')->nullable();
            $table->string('work_hours')->nullable();
            $table->boolean('works_saturday')->default(false);
            $table->boolean('accepting')->default(true);
            $table->decimal('rating', 2, 1)->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->string('badge')->nullable();
            $table->timestamps();

            $table->index(['type', 'district_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
