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
        Schema::create('yandex_direct_campaigns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')->references('id')->on('accounts')->cascadeOnDelete();
            $table->unsignedBigInteger('campaign_id')->unique();
            $table->string('campaign_name');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yandex_direct_campaigns');
    }
};
