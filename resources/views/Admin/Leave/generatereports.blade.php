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
            width: 33.33%;
            vertical-align: middle;
        }

        .border div:first-child {
            text-align: left;
        }

        .border div:nth-child(2) {
            text-align: left;
        }

        .border div:last-child {
            text-align: left;
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
        <h1>LEAVE SUMMARY REPORTS</h1>
    </header>

    <div class="border ">
        <div>
            <p><b>SORTED BY:</b>{{ Auth::user()->lastname }}, {{ Auth::user()->name }} {{ Auth::user()->middlename }} @if(Auth::user()->suffix == 'N/A')  @else {{Auth::user()->suffix}}@endif</p>
            @if ($employeeIds == !null)
                <p><b>Employee ID:</b> {{ $employeeIds }}</p>
            @endif
            @if ($statustype == !null)
            <p><b>Leave Type:</b> {{ $statustype->status }}</p>
            @else
            <p><b>Leave Type:</b> All</p>
        @endif


        </div>

        <div>
            @if ($timeframeStart && $timeframeEnd == !null)
                <p><b>TIME FRAME:</b> {{ \Carbon\Carbon::parse($timeframeStart)->format('Y, F j') }} -
                    {{ \Carbon\Carbon::parse($timeframeEnd)->format('Y, F j') }}</p>
            @else
                <p><b>TIME FRAME:</b> All</p>
            @endif
            @if ($employeestatus == !null)
            <p><b>Leave Status:</b> {{$employeestatus}}</p>
        @else
            <p><b>Leave Status:</b> All</p>
        @endif
        </div>
        <div>
            <p><b>DATE:</b> {{ \Carbon\Carbon::parse($dateNow)->format('Y, F j') }} </p>
            <p><b>Total Result:</b> {{ $recordCount }}</p>
        </div>
    </div>

    <div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Leave Type</th>
                    <th colspan="2">Inclusive Date</th>
                    <th>No. of Days</th>
                    <th>Requested Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaveData as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="text-transform: capitalize;">{{ $data->user->lastname }}, {{ $data->user->name }} {{ $data->user->middlename }} @if($data->user->suffix == 'N/A')  @else {{$data->user->suffix}}@endif</td>
                        <td>{{ $data->leavetype->status }}</td>
                        <td colspan="2">{{ \Carbon\Carbon::parse($data->from)->format('Y, F j') }} -
                            {{ \Carbon\Carbon::parse($data->to)->format('Y, F j') }}</td>
                        <td>{{ $data->leave_days }}</td>
                        <td>{{ \Carbon\Carbon::parse($data->created_at)->format('Y, F j') }}</td>
                        <td>{{ $data->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
