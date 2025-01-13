<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leavetype', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->timestamps();
        });
        DB::table('leavetype')->insert([
            [
                'status' => 'Vacation Leave',
            ],[
                'status' => 'Mandatory/Forced Leave',
            ],[
                'status' => 'Sick Leave',
            ],[
                'status' => 'Maternity Leave',
            ],[
                'status' => 'Paternity Leave',
            ],[
                'status' => 'Special Privilege Leave',
            ],[
                'status' => 'Solo Parent Leave',
            ],[
                'status' => 'Study Leave',
            ],[
                'status' => 'VAWC Leave',
            ],[
                'status' => 'Rehabilitation Leave',
            ],[
                'status' => 'Special Leave Benefits For Women',
            ],[
                'status' => 'Special Emergency (Calamity) Leave',
            ],[
                'status' => 'Monetization of Leave Credits',
            ],[
                'status' => 'Terminal Leave',
            ],[
                'status' => 'Adoption Leave',
            ]
            // Add other users as needed
        ]);
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leavetype');
    }
};
