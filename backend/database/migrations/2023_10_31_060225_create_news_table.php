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
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('author')->nullable()->default(null);
            $table->string('title');
            $table->mediumText('description')->nullable()->default(null);
            $table->string('source')->nullable()->default(null);
            $table->mediumText('url');
            $table->mediumText('image')->nullable()->default(null);
            $table->dateTime('publishedAt');
            $table->longText('content')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
