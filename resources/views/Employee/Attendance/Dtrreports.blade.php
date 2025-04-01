<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Grid Layout</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            max-width: 940px;
            margin: 0 auto;
            border-collapse: separate;
            /* Spacing between cells */
        }

        td {
            border-radius: 5px;
            padding: 7.5px;
            text-align: center;
        }

        .full-width {
            grid-column: span 2;
        }



        /* Adds a border only to the table with the specific header */
        .time-record-table,
        .time-record-table th,
        .time-record-table td {
            border: 1px solid black;
            border-collapse: collapse;
            /* Ensure borders don't double up */
        }

        .time-record-table {
            width: 100%;
            border-radius: 5px;
        }

        .custom-hr {
            border: none;
            /* Remove default border */
            border-top: 2px solid black;
            /* Add a solid line */
            margin-bottom: 20px;
            /* Add spacing above and below */
            width: 100%;
            /* Set the width */
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td>
                <header class="header">
                    <p style="text-align: start;">Civil Service Form No.48</p>
                    <p style="font-size: 14px"><b>DAILY TIME RECORD</b></p>
                    <p>-----o0o-----</p>
                    <p style="font-size: 14px; margin-bottom: 10px;"><u><b>{{ Auth::user()->lastname }}, {{ Auth::user()->name }}</b></u></p>
                    <p style="text-align: start;">For the month of ________<u>{{$date_range}}</u>_______</p>
                    </p>
                    <p style="text-align: start;">Official hours for Regular days _____________________</p>
                    <p style="text-align: start; margin-bottom: 2px;">Arrival and Departure Saturday ____________________</p>
                </header>

                <div>
                    <table class="time-record-table" style="font-size:10px;">
                        <thead>
                            <tr>
                                <th rowspan="2">Day</th>
                                <th colspan="2">AM</th>
                                <th colspan="2">PM</th>
                                <th colspan="2">Undertime</th>
                            </tr>
                            <tr>
                                <th>Arrival</th>
                                <th>Departure</th>
                                <th>Arrival</th>
                                <th>Departure</th>
                                <th>Hours</th>
                                <th>Minutes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailySeries as $date => $details)
                            @php
                            $currentDay = \Carbon\Carbon::parse($date)->format('d');
                            $isWeekend = in_array($date, $weekends); // Check if the date is a weekend
                            $isHoliday = in_array($date, $holidays); // Check if the date is a holiday
                            $dayRecords = $attendancegenerate->filter(function ($punch) use ($date) {
                            return $punch->date === $date;
                            });
                            @endphp

                            @if ($isWeekend && $dayRecords->isEmpty())
                            {{-- Display "Saturday" or "Sunday" if it's a weekend and no attendance records exist --}}
                            <tr>
                                <td>{{ $currentDay }}</td>
                                <td colspan="1" style="color: blue;">
                                    {{ strtoupper(\Carbon\Carbon::parse($date)->format('l')) }} {{-- Outputs "SATURDAY" or "SUNDAY" --}}
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @elseif ($isHoliday && $dayRecords->isEmpty())
                            {{-- Display "Holiday" if it's a holiday and no attendance records exist --}}
                            <tr>
                                <td>{{ $currentDay }}</td>
                                <td colspan="1" style="color: green;">HOLIDAY</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @elseif ($dayRecords->isEmpty())
                            {{-- Display a blank row for other days without attendance records --}}
                            <tr>
                                <td>{{ $currentDay }}</td>
                                <td colspan="1"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @else
                            {{-- Display rows for days with attendance records --}}
                            @foreach ($dayRecords as $index => $punch)
                            <tr>
                                {{-- Display the Day only for the first record of the day --}}
                                <td>{{ $currentDay }}</td>

                                {{-- Display attendance details --}}
                                <td>
                                    @if ($punch->punch_in_am_first != null)
                                    {{ \Carbon\Carbon::parse($punch->punch_in_am_first)->format('g:i A') }}
                                    @endif
                                </td>
                                <td>
                                    @if ($punch->punch_in_am_second != null)
                                    {{ \Carbon\Carbon::parse($punch->punch_in_am_second)->format('g:i A') }}
                                    @endif
                                </td>
                                <td>
                                    @if ($punch->punch_in_pm_first != null)
                                    {{ \Carbon\Carbon::parse($punch->punch_in_pm_first)->format('g:i A') }}
                                    @endif
                                </td>
                                <td>
                                    @if ($punch->punch_in_pm_second != null)
                                    {{ \Carbon\Carbon::parse($punch->punch_in_pm_second)->format('g:i A') }}
                                    @endif
                                </td>

                                @php
                                $total_minutes_am = 0;
                                $total_minutes_pm = 0;

                                // Morning punch-in and punch-out
                                if (!empty($punch['punch_in_am_first']) && !empty($punch['punch_in_am_second'])) {
                                $punch_in_time = (new DateTime($punch['punch_in_am_first']))->format('H:i:s');
                                $punch_out_time = (new DateTime($punch['punch_in_am_second']))->format('H:i:s');

                                if ($punch_in_time < '08:00:00' && $punch_out_time> '12:00:00') {
                                    $punch_in = new DateTime("08:00:00");
                                    $punch_out = new DateTime("12:00:00");
                                    } else {
                                    $punch_in = new DateTime($punch['punch_in_am_first']);
                                    $punch_out = new DateTime($punch['punch_in_am_second']);
                                    }

                                    $interval = $punch_in->diff($punch_out);
                                    $total_minutes_am = ($interval->h * 60) + $interval->i;
                                    }

                                    // Afternoon punch-in and punch-out
                                    if (!empty($punch['punch_in_pm_first']) && !empty($punch['punch_in_pm_second'])) {
                                    $punch_in_time_pm = (new DateTime($punch['punch_in_pm_first']))->format('H:i:s');
                                    $punch_out_time_pm = (new DateTime($punch['punch_in_pm_second']))->format('H:i:s');

                                    if ($punch_in_time_pm < '13:00:00' && $punch_out_time_pm> '17:00:00') {
                                        $punch_in_pm = new DateTime("13:00:00");
                                        $punch_out_pm = new DateTime("17:00:00");
                                        } else {
                                        $punch_in_pm = new DateTime($punch['punch_in_pm_first']);
                                        $punch_out_pm = new DateTime($punch['punch_in_pm_second']);
                                        }

                                        $interval_pm = $punch_in_pm->diff($punch_out_pm);
                                        $total_minutes_pm = ($interval_pm->h * 60) + $interval_pm->i;
                                        }

                                        // Total minutes
                                        $total = $total_minutes_am + $total_minutes_pm;
                                        @endphp


                                        @php
                                        $remainingMinutes=480 - $total; // Calculate the remaining minutes
                                        $remainingHours=intdiv($remainingMinutes, 60); // Convert remaining minutes to hours
                                        $remainingMinutesMod=$remainingMinutes % 60; // Calculate remaining minutes after hours
                                        @endphp


                                        @if($remainingHours === 0 && $remainingMinutesMod < 10 )
                                            <td>
            </td>
            <td></td>
            @else
            <td style="color:red;">{{$remainingHours}}</td>
            <td style="color:red;">{{$remainingMinutesMod}}</td>
            @endif


        </tr>

        @endforeach

        @endif

        @endforeach
        <td colspan="5" style="text-align: end;"> Total</td>
        <td></td>
        <td></td>
        </tbody>
    </table>
    <footer style="font-size: 14px;">
        <p style="text-align: start;"> I certify on my honor that the above is a true and correct report of the hours of work performed, record of which was made daily at the time of arrival and departure from office.</p>
        <p style="margin-top:10px;"><b>{{Auth::user()->name}} {{Auth::user()->lastname}}</b></p>
        <p class="custom-hr"></p>
        <p style="text-align: start;">VERIFIED as to the prescribed office hours;</p>
        <p class="custom-hr" style="margin-top:20px; margin-bottom:0px;"></p>
        <span>In Charge</span>
    </footer>
    </td>
    <td>
        <header class="header">
            <p style="text-align: start;">Civil Service Form No.48</p>
            <p style="font-size: 14px"><b>DAILY TIME RECORD</b></p>
            <p>-----o0o-----</p>
            <p style="font-size: 14px; margin-bottom: 10px;"><u><b>{{ Auth::user()->lastname }}, {{ Auth::user()->name }}</b></u></p>
            <p style="text-align: start;">For the month of ________<u>{{$date_range}}</u>_______</p>
            </p>
            <p style="text-align: start;">Official hours for Regular days _____________________</p>
            <p style="text-align: start; margin-bottom: 2px;">Arrival and Departure Saturday ____________________</p>
        </header>

        <div>
            <table class="time-record-table" style="font-size:10px;">
                <thead>
                    <tr>
                        <th rowspan="2">Day</th>
                        <th colspan="2">AM</th>
                        <th colspan="2">PM</th>
                        <th colspan="2">Undertime</th>
                    </tr>
                    <tr>
                        <th>Arrival</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Departure</th>
                        <th>Hours</th>
                        <th>Minutes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dailySeries as $date => $details)
                    @php
                    $currentDay = \Carbon\Carbon::parse($date)->format('d');
                    $isWeekend = in_array($date, $weekends); // Check if the date is a weekend
                    $isHoliday = in_array($date, $holidays); // Check if the date is a holiday
                    $dayRecords = $attendancegenerate->filter(function ($punch) use ($date) {
                    return $punch->date === $date;
                    });
                    @endphp

                    @if ($isWeekend && $dayRecords->isEmpty())
                    {{-- Display "Saturday" or "Sunday" if it's a weekend and no attendance records exist --}}
                    <tr>
                        <td>{{ $currentDay }}</td>
                        <td colspan="1" style="color: blue;">
                            {{ strtoupper(\Carbon\Carbon::parse($date)->format('l')) }} {{-- Outputs "SATURDAY" or "SUNDAY" --}}
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @elseif ($isHoliday && $dayRecords->isEmpty())
                    {{-- Display "Holiday" if it's a holiday and no attendance records exist --}}
                    <tr>
                        <td>{{ $currentDay }}</td>
                        <td colspan="1" style="color: green;">HOLIDAY</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @elseif ($dayRecords->isEmpty())
                    {{-- Display a blank row for other days without attendance records --}}
                    <tr>
                        <td>{{ $currentDay }}</td>
                        <td colspan="1"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @else
                    {{-- Display rows for days with attendance records --}}
                    @foreach ($dayRecords as $index => $punch)
                    <tr>
                        {{-- Display the Day only for the first record of the day --}}
                        <td>{{ $currentDay }}</td>

                        {{-- Display attendance details --}}
                        <td>
                            @if ($punch->punch_in_am_first != null)
                            {{ \Carbon\Carbon::parse($punch->punch_in_am_first)->format('g:i A') }}
                            @endif
                        </td>
                        <td>
                            @if ($punch->punch_in_am_second != null)
                            {{ \Carbon\Carbon::parse($punch->punch_in_am_second)->format('g:i A') }}
                            @endif
                        </td>
                        <td>
                            @if ($punch->punch_in_pm_first != null)
                            {{ \Carbon\Carbon::parse($punch->punch_in_pm_first)->format('g:i A') }}
                            @endif
                        </td>
                        <td>
                            @if ($punch->punch_in_pm_second != null)
                            {{ \Carbon\Carbon::parse($punch->punch_in_pm_second)->format('g:i A') }}
                            @endif
                        </td>

                        @php
                        $total_minutes_am = 0;
                        $total_minutes_pm = 0;

                        // Morning punch-in and punch-out
                        if (!empty($punch['punch_in_am_first']) && !empty($punch['punch_in_am_second'])) {
                        $punch_in_time = (new DateTime($punch['punch_in_am_first']))->format('H:i:s');
                        $punch_out_time = (new DateTime($punch['punch_in_am_second']))->format('H:i:s');

                        if ($punch_in_time < '08:00:00' && $punch_out_time> '12:00:00') {
                            $punch_in = new DateTime("08:00:00");
                            $punch_out = new DateTime("12:00:00");
                            } else {
                            $punch_in = new DateTime($punch['punch_in_am_first']);
                            $punch_out = new DateTime($punch['punch_in_am_second']);
                            }

                            $interval = $punch_in->diff($punch_out);
                            $total_minutes_am = ($interval->h * 60) + $interval->i;
                            }

                            // Afternoon punch-in and punch-out
                            if (!empty($punch['punch_in_pm_first']) && !empty($punch['punch_in_pm_second'])) {
                            $punch_in_time_pm = (new DateTime($punch['punch_in_pm_first']))->format('H:i:s');
                            $punch_out_time_pm = (new DateTime($punch['punch_in_pm_second']))->format('H:i:s');

                            if ($punch_in_time_pm < '13:00:00' && $punch_out_time_pm> '17:00:00') {
                                $punch_in_pm = new DateTime("13:00:00");
                                $punch_out_pm = new DateTime("17:00:00");
                                } else {
                                $punch_in_pm = new DateTime($punch['punch_in_pm_first']);
                                $punch_out_pm = new DateTime($punch['punch_in_pm_second']);
                                }

                                $interval_pm = $punch_in_pm->diff($punch_out_pm);
                                $total_minutes_pm = ($interval_pm->h * 60) + $interval_pm->i;
                                }

                                // Total minutes
                                $total = $total_minutes_am + $total_minutes_pm;
                                @endphp


                                @php
                                $remainingMinutes=480 - $total; // Calculate the remaining minutes
                                $remainingHours=intdiv($remainingMinutes, 60); // Convert remaining minutes to hours
                                $remainingMinutesMod=$remainingMinutes % 60; // Calculate remaining minutes after hours
                                @endphp


                                @if($remainingHours === 0 && $remainingMinutesMod < 10 )
                                    <td>
    </td>
    <td></td>
    @else
    <td style="color:red;">{{$remainingHours}}</td>
    <td style="color:red;">{{$remainingMinutesMod}}</td>
    @endif



    </tr>

    @endforeach

    @endif

    @endforeach
    <td colspan="5" style="text-align: end;"> Total</td>
    <td></td>
    <td></td>
    </tbody>
    </table>
    <footer style="font-size: 14px;">
        <p style="text-align: start;"> I certify on my honor that the above is a true and correct report of the hours of work performed, record of which was made daily at the time of arrival and departure from office.</p>
        <p style="margin-top:10px;"><b>{{Auth::user()->name}} {{Auth::user()->lastname}}</b></p>
        <p class="custom-hr"></p>
        <p style="text-align: start;">VERIFIED as to the prescribed office hours;</p>
        <p class="custom-hr" style="margin-top:20px; margin-bottom:0px;"></p>
        <span>In Charge</span>
    </footer>
    </td>
    </tr>
    </table>
</body>

</html>