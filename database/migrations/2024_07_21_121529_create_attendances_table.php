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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 20)->collation('utf8mb4_unicode_ci'); // Ensure the data type and length match custom_id in users table
            $table->date('date');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->timestamp('punch_in_am_first')->nullable();
            $table->timestamp('punch_in_pm_first')->nullable();
            $table->timestamp('punch_in_am_second')->nullable();
            $table->timestamp('punch_in_pm_second')->nullable();
            $table->integer('total_duration')->default(0);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('custom_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
