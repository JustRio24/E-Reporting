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
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number', 50)->unique();
            $table->foreignId('facility_id')->constrained('facilities')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('reporter_id')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('damage_category_id')->constrained('damage_categories')->onUpdate('cascade')->onDelete('restrict');
            $table->string('severity', 50)->default('low')->index();
            $table->string('title');
            $table->text('description');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('status', 50)->default('draft')->index();
            $table->timestamp('reported_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};
