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
        Schema::create('damage_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('damage_report_id')->constrained('damage_reports')->onUpdate('cascade')->onDelete('cascade');
            $table->string('photo_path', 500);
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_photos');
    }
};
