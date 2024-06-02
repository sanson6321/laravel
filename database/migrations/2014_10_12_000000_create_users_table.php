<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->nullable(true)->comment('作成日時');
            $table->string('created_name')->length(100)->nullable(true)->comment('作成者名');
            $table->timestamp('updated_at')->nullable(true)->comment('更新日時');
            $table->string('updated_name')->length(100)->nullable(true)->comment('更新者名');
            $table->integer('updated_no')->default(0)->comment('更新番号');
            $table->timestamp('deleted_at')->nullable(true)->comment('削除日時');
            $table->string('name')->length(100);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
