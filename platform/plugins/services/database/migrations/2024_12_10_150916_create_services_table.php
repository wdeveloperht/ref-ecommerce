<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table): void {
            $table->id();
            $table->string('key', 120)->unique();
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('open_in_new_tab')->default(false);
            $table->integer('order')->default(0)->nullable();
            $table->string('image')->nullable();
            $table->string('tablet_image')->nullable();
            $table->string('mobile_image')->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
