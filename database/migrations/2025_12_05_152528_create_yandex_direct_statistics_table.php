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
        Schema::create('yandex_direct_statistics', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('campaign_id');

            $table->date('date');
            $table->integer('impressions');
            $table->integer('clicks');
            $table->float('ctr');
            $table->float('avg_cpc');
            $table->float('cost');
            $table->integer('conversions');
            $table->float('cost_per_conversion');
            $table->float('conversion_rate');

            $table->unique(['campaign_id', 'date'], 'unique_campaign_date');

            $table->foreign('campaign_id')
                ->references('campaign_id')
                ->on('yandex_direct_campaigns')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index('date');
            $table->index(['campaign_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yandex_direct_statistics');
    }
};
