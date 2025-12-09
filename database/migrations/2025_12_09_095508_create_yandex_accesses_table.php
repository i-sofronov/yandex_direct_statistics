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
        Schema::create('yandex_accesses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')->references('id')->on('accounts')->cascadeOnDelete();

            $table->string('client_login');
            $table->text('access_token');
            $table->text('refresh_token');
            $table->timestamp('token_expires_at');
            $table->boolean('is_active')->default(true);
            $table->longText('metadata')->default(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yandex_accesses');
    }
};
