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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 20)->collation('utf8mb4_unicode_ci');
            $table->date('from');
            $table->date('to');
            $table->enum('status', ['Pending', 'Approved', 'Declined'])->default('Pending');
            $table->unsignedBigInteger('leave_type')->nullable(); 
            $table->integer('leave_days')->nullable();
            $table->enum('details_leave', ['1', '2'])->nullable();
            $table->enum('details_leave_sick', ['In Hospital', 'Out Patient'])->nullable();
            $table->enum('deleted', ['1', '2'])->default('1');
            $table->string('abroad')->nullable();
            $table->string('monetization', 255)->nullable();
            $table->string('terminal', 255)->nullable();
            $table->string('adoption', 255)->nullable();
            $table->timestamps();
            
            // Ensure the foreign key matches the `custom_id` type in the `users` table
            $table->foreign('employee_id')->references('custom_id')->on('users')->onDelete('cascade');
            $table->foreign('leave_type')->references('id')->on('leavetype')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
