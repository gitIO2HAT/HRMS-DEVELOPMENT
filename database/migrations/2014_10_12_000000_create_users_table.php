<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->tinyInteger('user_type');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('lastname', 255);
            $table->string('middlename', 30)->nullable();
            $table->enum('suffix', ['Jr.', 'Sr.', 'I', 'II', 'III','N/A'])->nullable();
            $table->enum('contract', ['1', '2', '3', '4', '5']);
            $table->enum('sex', ['Male', 'Female', 'Other']);
            $table->integer('age')->default(18);
            $table->date('birth_date')->nullable();
            $table->string('phonenumber', 20);
            $table->unsignedBigInteger('department')->nullable(); // Use unsignedBigInteger
            $table->unsignedBigInteger('position')->nullable(); // Use unsignedBigInteger
            $table->decimal('daily_rate', 10, 2);
            $table->string('custom_id', 20)->unique()->nullable();
            $table->date('date_of_assumption')->nullable();
            $table->date('end_of_contract')->nullable();
            $table->enum('is_archive', ['1', '2'])->default('1');
            $table->datetime('date_archive')->nullable();
            $table->enum('civil_status', ['Single', 'Married', 'Widowed'])->nullable();
            $table->text('fulladdress')->nullable();
            $table->string('emergency_fullname', 255)->nullable();
            $table->text('emergency_fulladdress')->nullable();
            $table->string('emergency_phonenumber', 20)->nullable();
            $table->string('emergency_relationship', 50)->nullable();
            $table->string('profile_pic', 255)->default('default.png');
            $table->string('pds_file', 255)->nullable();
            $table->decimal('vacation_leave', 10, 3)->default(0);
            $table->decimal('sick_leave',10, 3)->default(0);
            $table->decimal('special_previlege_leave',10, 3)->default(3);
            $table->decimal('maternity_leave',10, 3)->default(105);
            $table->decimal('paternity_leave',10, 3)->default(7);
            $table->decimal('solo_parent_leave',10, 3)->default(7);
            $table->decimal('study_leave',10, 3)->default(240);
            $table->decimal('vawc_leave',10, 3)->default(10);
            $table->decimal('rehabilitation_leave',10, 3)->default(240);
            $table->decimal('special_leave_benefits_for_women',10, 3)->default(60);
            $table->decimal('special_emergency_leave',10, 3)->default(5);
            $table->rememberToken();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('position')->references('id')->on('positions')->onDelete('cascade');

        });
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'lastname' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => '$2y$12$rtB0bLm5O.eHAz8czKUCwee.JBk1kziejszCU4FYP8TXobrQ5rLE2',
                'user_type' => 0,
                'profile_pic' => 'superadmin.png',
                'middlename' => 'Middle',
                'sex' => 'Other',
                'age' => 30,
                'birth_date' => '1992-05-15',
                'phonenumber' => '1234567890',
                'daily_rate' => 100.00,
                'custom_id' => '1',
                'end_of_contract' => '2025-12-31',
                'is_archive' => '1',
                'contract' => '1',
                'civil_status' => 'Single',
                'fulladdress' => '123 Main St, City, Country',
                'emergency_fullname' => 'Emergency Contact',
                'emergency_fulladdress' => '456 Emergency St, City, Country',
                'emergency_phonenumber' => '9876543210',
                'emergency_relationship' => 'Friend',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add other users as needed
        ]);
        DB::statement('UPDATE users SET age = TIMESTAMPDIFF(YEAR, birth_date, NOW())');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
