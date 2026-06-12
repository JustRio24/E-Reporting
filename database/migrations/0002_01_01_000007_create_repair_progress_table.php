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
        Schema::create('repair_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('progress_percentage')->unsigned()->default(0);
            $table->text('description');
            $table->string('photo', 500)->nullable();
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_progress');
    }
};
