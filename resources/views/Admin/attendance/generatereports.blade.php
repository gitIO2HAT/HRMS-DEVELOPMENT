<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance History</title>
    <style>
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 5px 0;

        }

        .header span {
            font-size: 18px;
            display: block;
            margin-top: 5px;
        }

        .border {

            border-bottom: 1px solid #000;
            display: table;
            width: 100%;
            font-size: 12px;
        }

        .border div {
            display: table-cell;
            width: 25%;
            vertical-align: middle;
        }

        .border div:first-child {
            text-align: left;
        }

        .border div:nth-child(2) {
            text-align: center;
        }

        .border div:nth-child(3) {
            text-align: center;
        }

        .border div:last-child {
            text-align: center;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            /* Adds border to the table, header, and cells */
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .HRMS {
            border-bottom: 1px solid #000;
        }
    </style>

</head>

<body>
    <header class="header">
        <h1>LOCAL GOVERNMENT UNIT OF SULOP</h1>
        <span>Province of Davao del Sur</span>
        <span>Municipality of Sulop</span>
        <h1 class="HRMS">HUMAN RESOURCE MANAGEMENT OFFICE</h1>
        <h1>ATTENDANCE SUMMARY REPORTS</h1>
    </header>

    <div class="border">
        <div>
            <p><b>SORTED BY:</b>{{ Auth::user()->lastname }}, {{ Auth::user()->name }} {{ Auth::user()->middlename }}
                @if (Auth::user()->suffix == 'N/A')
                @else
                    {{ Auth::user()->suffix }}
                @endif
            </p>
            @if ($employeeIds == !null)
                <p><b>Employee ID:</b> {{ $employeeIds }}</p>
            @endif

        </div>

        <div>
            @if ($timeframeStart && $timeframeEnd == !null)
                <p><b>TIME FRAME:</b> {{ \Carbon\Carbon::parse($timeframeStart)->format('Y, F j') }} -
                    {{ \Carbon\Carbon::parse($timeframeEnd)->format('Y, F j') }}
                </p>
            @else
                <p><b>TIME FRAME:</b> All</p>
            @endif

        </div>
        <div>
            <p><b>Total Result:</b> {{ $recordCount }}</p>
        </div>
        <div>
            <p><b>DATE:</b> {{ \Carbon\Carbon::parse($dateNow)->format('Y, F j') }} </p>
        </div>
    </div>

    <div>
        <table>
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Full Name</th>
                    <th rowspan="2">Date</th>
                    <th colspan="2">Morning</th>
                    <th colspan="2">Afernoon</th>
                    <th colspan="2">Undertime</th>
                </tr>
                <tr>

                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Hours</th>
                    <th>Minutes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendancegenerate as $index => $punch)
                    @foreach ($attendanceData as $data)
                        @if ($data['user_id'] === $punch->user_id && $data['date'] === $punch->date)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $punch->user->lastname }}, {{ $punch->user->name }}
                                    {{ $punch->user->middlename }} @if ($punch->user->suffix == 'N/A')
                                    @else
                                        {{ $punch->user->suffix }}
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($punch->date)->format('Y, F j') }}</td>
                                <td>
                                    @if ($punch->punch_in_am_first != null)
                                        {{ \Carbon\Carbon::parse($punch->punch_in_am_first)->format('g:i A') }}
                                    @else
                                        No data
                                    @endif
                                </td>
                                <td>
                                    @if ($punch->punch_in_am_second != null)
                                        {{ \Carbon\Carbon::parse($punch->punch_in_am_second)->format('g:i A') }}
                                    @else
                                        No data
                                    @endif
                                </td>
                                <td>
                                    @if ($punch->punch_in_pm_first != null)
                                        {{ \Carbon\Carbon::parse($punch->punch_in_pm_first)->format('g:i A') }}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if ($punch->punch_in_pm_second != null)
                                        {{ \Carbon\Carbon::parse($punch->punch_in_pm_second)->format('g:i A') }}
                                    @else
                                    @endif
                                </td>
                                @php
                                    $total_minutes_am = 0;
                                    $total_minutes_pm = 0;

                                    // Morning punch-in and punch-out
                                    if (!empty($punch['punch_in_am_first']) && !empty($punch['punch_in_am_second'])) {
                                        $punch_in_time = (new DateTime($punch['punch_in_am_first']))->format('H:i:s');
                                        $punch_out_time = (new DateTime($punch['punch_in_am_second']))->format('H:i:s');

                                        if ($punch_in_time < '08:00:00' && $punch_out_time > '12:00:00') {
                                            $punch_in = new DateTime('08:00:00');
                                            $punch_out = new DateTime('12:00:00');
                                        } else {
                                            $punch_in = new DateTime($punch['punch_in_am_first']);
                                            $punch_out = new DateTime($punch['punch_in_am_second']);
                                        }

                                        $interval = $punch_in->diff($punch_out);
                                        $total_minutes_am = $interval->h * 60 + $interval->i;
                                    }

                                    // Afternoon punch-in and punch-out
                                    if (!empty($punch['punch_in_pm_first']) && !empty($punch['punch_in_pm_second'])) {
                                        $punch_in_time_pm = (new DateTime($punch['punch_in_pm_first']))->format(
                                            'H:i:s',
                                        );
                                        $punch_out_time_pm = (new DateTime($punch['punch_in_pm_second']))->format(
                                            'H:i:s',
                                        );

                                        if ($punch_in_time_pm < '13:00:00' && $punch_out_time_pm > '17:00:00') {
                                            $punch_in_pm = new DateTime('13:00:00');
                                            $punch_out_pm = new DateTime('17:00:00');
                                        } else {
                                            $punch_in_pm = new DateTime($punch['punch_in_pm_first']);
                                            $punch_out_pm = new DateTime($punch['punch_in_pm_second']);
                                        }

                                        $interval_pm = $punch_in_pm->diff($punch_out_pm);
                                        $total_minutes_pm = $interval_pm->h * 60 + $interval_pm->i;
                                    }

                                    // Total minutes
                                    $total = $total_minutes_am + $total_minutes_pm;
                                @endphp


                                @php
                                    $remainingMinutes = 480 - $total; // Calculate the remaining minutes
                                    $remainingHours = intdiv($remainingMinutes, 60); // Convert remaining minutes to hours
                                    $remainingMinutesMod = $remainingMinutes % 60; // Calculate remaining minutes after hours
                                @endphp


                                @if ($remainingHours === 0 && $remainingMinutesMod < 10)
                                    <td>
                                    </td>
                                    <td></td>
                                @else
                                    <td style="color:red;">{{ $remainingHours }}</td>
                                    <td style="color:red;">{{ $remainingMinutesMod }}</td>
                                @endif

                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
