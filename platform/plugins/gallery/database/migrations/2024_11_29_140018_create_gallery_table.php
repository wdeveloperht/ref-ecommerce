<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('gallery', function (Blueprint $table): void {
            $table->id();
            $table->string('status', 60)->default('published');
            $table->string('name', 120);
            $table->string('key', 120);
            $table->string('description', 400)->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('gallery_id');
            $table->string('title')->nullable();
            $table->string('image');
            $table->string('link')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery');
        Schema::dropIfExists('gallery_items');
    }
};
