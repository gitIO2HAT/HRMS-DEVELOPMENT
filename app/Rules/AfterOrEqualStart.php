<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class AfterOrEqualStart implements Rule
{
    protected $startDate;

    public function __construct($startDate)
    {
        $this->startDate = Carbon::parse($startDate);
    }

    public function passes($attribute, $value)
    {
        if (!$value) {
            // If scheduled_end is not provided, validation passes
            return true;
        }

        $endDate = Carbon::parse($value);

        // Check if the end date is after or equal to the start date
        return $endDate->gte($this->startDate);
    }

    public function message()
    {
        return 'The end date and time must be after or equal to the start date and time.';
    }
}
