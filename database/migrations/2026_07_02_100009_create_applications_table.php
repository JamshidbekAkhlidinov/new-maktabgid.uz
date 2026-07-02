<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type'); // excursion|enrollment
            $table->string('child_name');
            $table->date('child_birth_date')->nullable();
            $table->unsignedSmallInteger('child_age')->nullable();
            $table->string('current_grade')->nullable();
            $table->string('target_grade')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->string('preferred_start')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('pending'); // pending|confirmed|rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
