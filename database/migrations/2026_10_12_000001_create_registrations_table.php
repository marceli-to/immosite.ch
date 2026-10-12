<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->json('interests')->nullable();   // e.g. ["2.5", "gewerbe"]
            $table->string('first_name');
            $table->string('last_name');
            $table->string('street')->nullable();
            $table->string('zip_city')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('exported_at')->nullable(); // set once sent in the weekly list
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
