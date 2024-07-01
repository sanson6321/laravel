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
        Schema::create('logs', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable(true)->comment('作成日時');
            $table->timestamp('updated_at')->nullable(true)->comment('更新日時');
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(true)->comment('ユーザーID');
            $table->string('user_name')->length(100)->comment('ユーザー名');
            $table->integer('type')->default(0)->comment('タイプ');
            $table->string('table_name')->length(100)->comment('テーブル名');
            $table->text('content')->nullable(true)->comment('内容更新後');
            $table->text('content_old')->nullable(true)->comment('内容更新前');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
