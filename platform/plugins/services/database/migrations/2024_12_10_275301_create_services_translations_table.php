<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('services_translations')) {
            Schema::create('services_translations', function (Blueprint $table): void {
                $table->string('lang_code');
                $table->foreignId('services_id');
                $table->string('name')->nullable();
                $table->string('subtitle')->nullable();
                $table->text('description')->nullable();
                $table->string('slug')->nullable();
                $table->primary(['lang_code', 'services_id'], 'service_translations_primary');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('services_translations');
    }
};
