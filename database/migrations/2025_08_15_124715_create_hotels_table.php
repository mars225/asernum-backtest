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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('code')->unique();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->tinyInteger('stars')->nullable(); // 1 à 5
            $table->userstamps();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
