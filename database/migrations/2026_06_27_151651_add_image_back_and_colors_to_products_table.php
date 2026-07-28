<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_back')->nullable()->after('image');
            $table->json('colors')->nullable()->after('image_back');
            $table->json('sizes')->nullable()->after('colors');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['image_back', 'colors', 'sizes']);
        });
    }
};