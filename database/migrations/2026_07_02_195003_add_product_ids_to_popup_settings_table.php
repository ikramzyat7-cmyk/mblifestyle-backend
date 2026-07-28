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
    Schema::table('popup_settings', function (Blueprint $table) {
        $table->json('product_ids')->nullable()->after('product_id');
    });
}

public function down(): void
{
    Schema::table('popup_settings', function (Blueprint $table) {
        $table->dropColumn('product_ids');
    });
}
};
