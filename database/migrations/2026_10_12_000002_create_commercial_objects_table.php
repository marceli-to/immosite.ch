<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Commercial units maintained by the marketer in /admin (module:commercial-admin),
 * for projects where they are not in Flatfox/Melon. Seeded once from
 * config/commercial.php with `php artisan commercial:seed`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commercial_objects', function (Blueprint $table) {
            $table->id();
            $table->string('ref', 20)->unique();
            $table->unsignedSmallInteger('position')->default(0);
            $table->string('house', 20)->nullable();
            $table->string('floor', 20)->nullable();
            $table->decimal('area', 8, 1)->nullable();
            // Rents are often unknown at handover, hence nullable.
            $table->unsignedInteger('rent_net')->nullable();
            $table->unsignedInteger('charges')->nullable();
            $table->string('status', 10)->default('free');
            $table->string('plan_url')->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commercial_objects');
    }
};
