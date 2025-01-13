<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class UpdateUserCredit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-user-credit';



    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increment user credits for sick and vacation balances';

    /**
     * Execute the console command.
     */

    // This is 1 Month
    public function handle()
    {
        DB::beginTransaction();

        try {
            // Get the current time
            $now = Carbon::now();

            // Check if today is the 27th of the month
            if ($now->day == 28) {
                // Update all users by adding 1.25 to each leave type
                $updated = User::query()->update([
                    'sick_leave' => DB::raw('sick_leave + 1.25'),
                    'vacation_leave' => DB::raw('vacation_leave + 1.25')
                ]);

                if ($updated) {
                    // Insert a new record in the history table for each user
                    User::all()->each(function ($user) use ($now) {
                        // Update your handle() method accordingly
                        History::create([
                            'history_id' => $user->custom_id,
                            'period' => $now->format('Y-m-01'),  // Set to the first day of the current month
                            'v_earned' => 1.25,
                            'v_balance' => $user->vacation_leave + 1.25, // Updated vacation leave balance
                            's_earned' => 1.25,
                            's_balance' => $user->sick_leave + 1.25,     // Updated sick leave balance
                           // Include the current date and time
                        ]);
                    });

                    DB::commit();  // Commit transaction
                    $this->info($updated . ' users have been updated and history records created.');
                } else {
                    DB::rollBack();
                    $this->info('No users found to update.');
                }
            } else {
                DB::rollBack();
                $this->info('Today is not the first day of the month. No updates were made.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error occurred: ' . $e->getMessage());
        }
    }









    /** For testing
     *
     * Configure the Task Scheduler on your Windows or Server and run daily every 1 minute
     * use the bat (UpdateUserCredit.bat) file path to run this task scheduler
     *
     * Step 1: Go to Task Scheduler
     * Step 2: Create Task name it Laravel Scheduler
     *       : (/)Run whether user is logged on or not
     *       : (/)Run with highest privileges
     * Step 3: Trigger click " New "
     *       : For Testing set the One time specific time or you can use "php artisan scheduler:run/php artisn scheduler:work" in Terminal
     *       : Set Daily
     *       : (/)Repeat tast every 1 minute
     *       :Then click " ok "
     * Step 4: Actions click " New "
     *       :Action should be " Start a Program "
     *       :Program/script Browse the "UpdateUserCredit.bat" in your laravel file along with this commands folder file "C:\xampp\htdocs\HRMS-Project-main\app\Console\Commands\UpdateUserCredit.bat"
     *       :Then click " ok "
     * Step 5: Settings
     *       :Double check the setting
     *       :(/)Allow task to be run on demand
     *       :Then ok
     * Step 5: run manually the file
     *       :you can see the status if ready click, the right click then " Run"
     *       :the status should be "running"
     *
     */


    /*  public function handle()
    {
        try {
            // Get the current time
            $now = Carbon::now();

            // Find and update all users created at a time where the minutes since creation are a multiple of 5
            $updated = User::whereRaw('TIMESTAMPDIFF(MINUTE, created_at, NOW()) % 5 = 0')
                ->update([
                    'sick_balance' => DB::raw('sick_balance + 1.25'),
                    'vacation_balance' => DB::raw('vacation_balance + 1.25')
                ]);

            if ($updated) {
                $this->info($updated . ' users have been updated successfully.');
            } else {
                $this->info('No users found to update.');
            }
        } catch (\Exception $e) {
            $this->error('Error occurred: ' . $e->getMessage());
        }
    }*/
}
