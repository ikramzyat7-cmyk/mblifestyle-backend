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
    Schema::table('slides', function (Blueprint $table) {
        $table->enum('style', ['default', 'fagor-banner'])->default('default')->after('type');
        $table->string('promo_amount')->nullable()->after('style');
        $table->string('promo_sub')->nullable()->after('promo_amount');
        $table->string('product_image')->nullable()->after('promo_sub');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            //
        });
    }
};
