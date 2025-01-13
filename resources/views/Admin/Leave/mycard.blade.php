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

    <div>
        <table>
            <thead>
                <tr>
                    <th colspan="12">Leave Credit Card</th>
                </tr>
                <tr>
                    <th colspan="4">Name: {{Auth::user()->lastname}}, {{Auth::user()->name}} {{Auth::user()->middlename}} @if(Auth::user()->suffix == 'N/A')  @else {{Auth::user()->suffix}}@endif</th>
                    <th colspan="4">
                        @foreach($department as $depart)
                        @if($depart->id == Auth::user()->department) <!-- Assuming department_id is a foreign key in users table -->
                        Office: {{$depart->name}}
                        @endif
                        @endforeach
                    </th>


                    <th colspan="4">Day of Service:</th>
                </tr>
                <tr>
                    <th rowspan="2" colspan="1">PERIOD</th>
                    <th rowspan="2" colspan="2">PARTICULARS</th>
                    <th colspan="4">VACATION LEAVE</th>
                    <th colspan="4">SICK LEAVE</th>
                    <th rowspan="2" colspan="1">DATE AND ACTION TAKEN OR APPLICATION FOR LEAVE</th>
                </tr>
                <tr>
                    <th>EARNED</th>
                    <th>ABS. UND. W/P</th>
                    <th>BALANCE</th>
                    <th>ABS. UND. WOP</th>
                    <th>EARNED</th>
                    <th>ABS. UND. W/P</th>
                    <th>BALANCE</th>
                    <th>ABS. UND. WOP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $his)
                <tr>
                    <td>
                        @if($his->period)
                        {{ \Carbon\Carbon::parse($his->period)->format('M-Y') }}
                        @else
                        <!-- or leave it blank if you prefer -->
                        @endif
                    </td>

                    <td colspan="2">
                        @if($his->particular)
                        {{ $his->particular }} <!-- Just display it without formatting -->
                        @else
                        <!-- or leave it blank if you prefer -->
                        @endif
                    </td>

                    <td>{{$his->v_earned}}</td>
                    <td>{{$his->v_wp}}</td>
                    <td>{{$his->v_balance}}</td>
                    <td>{{$his->v_wop}}</td>
                    <td>{{$his->s_earned}}</td>
                    <td>{{$his->s_wp}}</td>
                    <td>{{$his->s_balance}}</td>
                    <td>{{$his->s_wop}}</td>
                    <td>
                        @if($his->date_action)
                        {{ \Carbon\Carbon::parse($his->date_action)->format('d-M-Y') }}
                        @else
                        <!-- or leave it blank if you prefer -->
                        @endif

                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</body>

</html>