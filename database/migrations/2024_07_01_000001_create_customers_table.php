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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->nullable(true)->comment('作成日時');
            $table->string('created_name')->length(100)->nullable(true)->comment('作成者名');
            $table->timestamp('updated_at')->nullable(true)->comment('更新日時');
            $table->string('updated_name')->length(100)->nullable(true)->comment('更新者名');
            $table->integer('updated_no')->default(0)->comment('更新番号');
            $table->timestamp('deleted_at')->nullable(true)->comment('削除日時');
            $table->string('name')->length(100)->comment('名前');
            $table->string('post_code')->length(10)->comment('郵便番号');
            $table->smallInteger('prefecture')->comment('都道府県');
            $table->string('address')->length(200)->comment('住所');
            $table->string('address_sub')->length(200)->nullable(true)->comment('住所サブ');
            $table->smallInteger('gender')->comment('性別');
            $table->string('note')->length(1000)->nullable(true)->comment('備考');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
