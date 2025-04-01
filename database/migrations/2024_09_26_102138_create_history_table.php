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
        Schema::create('history', function (Blueprint $table) {
            $table->id();
            $table->string('history_id', 20)->collation('utf8mb4_unicode_ci');
            $table->datetime('period')->nullable();
            $table->string('particular')->nullable();
            $table->decimal('v_earned',10,3)->nullable();
            $table->decimal('v_wp',10,3)->nullable();
            $table->decimal('v_balance',10,3)->nullable();
            $table->decimal('v_wop',10,3)->nullable();
            $table->decimal('s_earned',10,3)->nullable();
            $table->decimal('s_wp',10,3)->nullable();
            $table->decimal('s_balance',10,3)->nullable();
            $table->decimal('s_wop',10,3)->nullable();
            $table->datetime('date_action')->nullable();
            $table->timestamps();
            $table->foreign('history_id')->references('custom_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history');
    }
};
