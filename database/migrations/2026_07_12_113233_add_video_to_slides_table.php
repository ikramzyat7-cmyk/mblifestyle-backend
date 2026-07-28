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
        $table->string('video')->nullable()->after('image');
        $table->enum('type', ['image', 'video'])->default('image')->after('video');
    });
}

public function down(): void
{
    Schema::table('slides', function (Blueprint $table) {
        $table->dropColumn(['video', 'type']);
    });
}
};
