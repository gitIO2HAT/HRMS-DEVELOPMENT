@extends('Layouts.App')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="row g-4">
                    <div class="pt-4 px-4">
                        <div class="row g-4">
                            <div class="col-sm-12 col-xl-12">
                                <div class="bg-white rounded-3 h-100 p-4">
                                    <h6 class="mb-4 text-center text-dark">Attendance Records</h6>
                                    <form method="GET" action="{{ url('/SuperAdmin/Attendance') }}">
                                        <div class="d-flex align-content-center mb-3">
                                            @csrf
                                            <label class="text-dark" for="search">Employee Name:</label>
                                            <input type="text" name="search" id="search"
                                                value="{{ request('search') }}" placeholder="Search by Name" class="modify">

                                            <label class="text-dark" for="month">Select Month:</label>
                                            <select name="month" id="month" class="modify">
                                                @for ($m = 1; $m <= 12; $m++)
                                                    <option value="{{ $m }}"
                                                        {{ $selectedMonth == $m ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                                    </option>
                                                @endfor
                                            </select>

                                            <label class="text-dark" for="year">Select Year:</label>
                                            <select name="year" id="year" class="modify">
                                                @for ($y = Carbon\Carbon::now()->year; $y >= Carbon\Carbon::now()->year - 5; $y--)
                                                    <option value="{{ $y }}"
                                                        {{ $selectedYear == $y ? 'selected' : '' }}>
                                                        {{ $y }}
                                                    </option>
                                                @endfor
                                            </select>

                                            <button class="btn btn-success" type="submit">Search</button>
                                            <a type="button" class="btn btn-info mx-2 d-flex align-items-center"
                                                data-bs-toggle="modal" data-bs-target="#generateReportsModal">
                                                Generate Reports
                                            </a>
                                        </div>
                                    </form>

                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="text-dark text-center">
                                                <tr class="bg-title">
                                                    <th class="centered">#</th>
                                                    <th class="centered">Employee Name</th>
                                                    @foreach ($dailySeries as $date => $duration)
                                                        <th>{{ \Carbon\Carbon::parse($date)->format('M d') }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @foreach ($employeeRecords as $index => $record)
                                                    <tr>
                                                        <td>{{ ($employeeRecords->currentPage() - 1) * $employeeRecords->perPage() + $index + 1 }}
                                                        </td>
                                                        <td class="text-dark  d-flex align-items-center">
                                                            <img class="rounded-circle me-lg-2"
                                                                src="{{ asset('public/accountprofile/' . $record->profile_pic) }}"
                                                                alt="" style="width: 40px; height: 40px;">
                                                            {{ $record->name }} {{ $record->lastname }}
                                                        </td>
                                                        @foreach ($dailySeries as $date => $duration)
                                                            <td class="text-dark centered">
                                                                @php
                                                                    $attendance = $RecordsAttendance
                                                                        ->where('user_id', $record->custom_id)
                                                                        ->where('date', $date)
                                                                        ->first();
                                                                    $amFirst =
                                                                        $attendance && $attendance->punch_in_am_first
                                                                            ? '✔'
                                                                            : '✘';
                                                                    $amSecond =
                                                                        $attendance && $attendance->punch_in_am_second
                                                                            ? '✔'
                                                                            : '✘';
                                                                    $pmFirst =
                                                                        $attendance && $attendance->punch_in_pm_first
                                                                            ? '✔'
                                                                            : '✘';
                                                                    $pmSecond =
                                                                        $attendance && $attendance->punch_in_pm_second
                                                                            ? '✔'
                                                                            : '✘';

                                                                    $amStatus =
                                                                        $amFirst === '✔' && $amSecond === '✔'
                                                                            ? '✔'
                                                                            : '✘';
                                                                    $pmStatus =
                                                                        $pmFirst === '✔' && $pmSecond === '✔'
                                                                            ? '✔'
                                                                            : '✘';
                                                                    $finalStatus =
                                                                        $amStatus === '✔' && $pmStatus === '✔'
                                                                            ? '✔'
                                                                            : '✘';

                                                                    // Additional logic to display CHECK MARK and XMARK as per requirements
                                                                    if ($amStatus === '✔' && $pmStatus === '✘') {
                                                                        $displayStatus = '✔ ✘';
                                                                    } elseif ($amStatus === '✘' && $pmStatus === '✔') {
                                                                        $displayStatus = '✘ ✔';
                                                                    } elseif (
                                                                        $amStatus === '✔' &&
                                                                        $pmStatus === '✔'
                                                                    ) {
                                                                        $displayStatus = '✔';
                                                                    } elseif ($amStatus === '✘' && $pmStatus === '✘') {
                                                                        $displayStatus = '✘';
                                                                    } else {
                                                                        $displayStatus =
                                                                            $amStatus . ' and ' . $pmStatus;
                                                                    }
                                                                @endphp

                                                                @if ($displayStatus === '✔ ✘')
                                                                    <i class="fas fa-check-square"
                                                                        style="color: #63E6BE;"></i> <i
                                                                        class="fas fa-window-close"
                                                                        style="color: #e22c2c;"></i>
                                                                @elseif ($displayStatus === '✘ ✔')
                                                                    <i class="fas fa-window-close"
                                                                        style="color: #e22c2c;"></i> <i
                                                                        class="fas fa-check-square"
                                                                        style="color: #63E6BE;"></i>
                                                                @elseif ($displayStatus === '✔')
                                                                    <i class="fas fa-check-square"
                                                                        style="color: #63E6BE;"></i>
                                                                @elseif ($displayStatus === '✘')
                                                                    <i class="fas fa-window-close"
                                                                        style="color: #e22c2c;"></i>
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{ $employeeRecords->onEachSide(1)->links() }}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-12 ">
                        <div class="bg-white rounded-3 h-100 p-4">
                            <table
                                class="table table-striped table-hover table-responsive table-bordered text-start align-middle ">
                                <thead class="text-dark">
                                    <tr>
                                        <th class="bg-head text-center" scope="col" colspan="9">Attendance Admin &&
                                            Employee</th>
                                    </tr>
                                    <form method="GET" action="{{ url('/SuperAdmin/Attendance') }}">
                                        <div class="d-flex align-content-center mb-3">
                                            @csrf
                                            <label class="text-dark" for="search">Employee Name:</label>
                                            <input type="text" name="search" id="search"
                                                value="{{ request('search') }}" placeholder="Search by Name" class="modify">

                                            <label class="text-dark" for="month">Select Month:</label>
                                            <select name="month" id="month" class="modify">
                                                @for ($m = 1; $m <= 12; $m++)
                                                    <option value="{{ $m }}"
                                                        {{ $selectedMonth == $m ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                                    </option>
                                                @endfor
                                            </select>

                                            <label class="text-dark" for="year">Select Year:</label>
                                            <select name="year" id="year" class="modify">
                                                @for ($y = Carbon\Carbon::now()->year; $y >= Carbon\Carbon::now()->year - 5; $y--)
                                                    <option value="{{ $y }}"
                                                        {{ $selectedYear == $y ? 'selected' : '' }}>
                                                        {{ $y }}
                                                    </option>
                                                @endfor
                                            </select>
                                            <button class="btn btn-success" type="submit">Search</button>
                                        </div>
                                    </form>
                                    <tr class="bg-title">
                                        <th class="" scope="col" rowspan="2">#</th>
                                        <th class="" scope="col" rowspan="2">Employee ID</th>
                                        <th class="" scope="col" rowspan="2">Date</th>
                                        <th scope="col" colspan="2">Morning</th>
                                        <th scope="col" colspan="2">Afternoon</th>
                                        <th scope="col" colspan="2">Undertime</th>
                                    </tr>
                                    <tr>
                                        <th class="bg-morning" scope="col">Clock In</th>
                                        <th class="bg-afternoon" scope="col">Clock Out</th>
                                        <th class="bg-morning" scope="col">Clock In</th>
                                        <th class="bg-afternoon" scope="col">Clock Out</th>
                                        <th class="bg-morning" scope="col">Hours</th>
                                        <th class="bg-afternoon" scope="col">Minutes</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    @foreach ($getall as $index => $punch)
                                        @foreach ($attendanceData as $data)
                                            @if ($data['user_id'] === $punch->user_id && $data['date'] === $punch->date)
                                                <tr>
                                                    <th scope="row">
                                                        {{ ($getall->currentPage() - 1) * $getall->perPage() + $index + 1 }}
                                                    </th>
                                                    <td class="text-capitalize"><img class="rounded-circle me-lg-2"
                                                            src="{{ asset('public/accountprofile/' . $punch->user->profile_pic) }}"
                                                            alt="" style="width: 40px; height: 40px;">
                                                        {{ $punch->user->lastname }}, {{ $punch->user->name }}
                                                        {{ $punch->user->middlename }} @if ($punch->user->suffix == 'N/A')
                                                        @else
                                                            {{ $punch->user->suffix }}
                                                        @endif

                                                    </td>
                                                    <td class="text-dark">
                                                        {{ \Carbon\Carbon::parse($punch->date)->format('Y, F j') }}
                                                    </td>
                                                    <td class="text-dark">
                                                        @if (is_null($punch->punch_in_am_first))
                                                            NO DATA
                                                        @else
                                                            {{ \Carbon\Carbon::parse($punch->punch_in_am_first)->format('g:i A') }}
                                                        @endif
                                                    </td>
                                                    <td class="text-dark">
                                                        @if (is_null($punch->punch_in_am_second))
                                                            NO DATA
                                                        @else
                                                            {{ \Carbon\Carbon::parse($punch->punch_in_am_second)->format('g:i A') }}
                                                        @endif
                                                    </td>
                                                    <td class="text-dark">
                                                        @if (is_null($punch->punch_in_pm_first))
                                                            NO DATA
                                                        @else
                                                            {{ \Carbon\Carbon::parse($punch->punch_in_pm_first)->format('g:i A') }}
                                                        @endif
                                                    </td>
                                                    <td class="text-dark">
                                                        @if (is_null($punch->punch_in_pm_second))
                                                            NO DATA
                                                        @else
                                                            {{ \Carbon\Carbon::parse($punch->punch_in_pm_second)->format('g:i A') }}
                                                        @endif
                                                    </td>
                                                    @php
                                                        $total_minutes_am = 0;
                                                        $total_minutes_pm = 0;

                                                        // Morning punch-in and punch-out
                                                        if (
                                                            !empty($punch['punch_in_am_first']) &&
                                                            !empty($punch['punch_in_am_second'])
                                                        ) {
                                                            $punch_in_time = (new DateTime(
                                                                $punch['punch_in_am_first'],
                                                            ))->format('H:i:s');
                                                            $punch_out_time = (new DateTime(
                                                                $punch['punch_in_am_second'],
                                                            ))->format('H:i:s');

                                                            if (
                                                                $punch_in_time < '08:00:00' &&
                                                                $punch_out_time > '12:00:00'
                                                            ) {
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
                                                        if (
                                                            !empty($punch['punch_in_pm_first']) &&
                                                            !empty($punch['punch_in_pm_second'])
                                                        ) {
                                                            $punch_in_time_pm = (new DateTime(
                                                                $punch['punch_in_pm_first'],
                                                            ))->format('H:i:s');
                                                            $punch_out_time_pm = (new DateTime(
                                                                $punch['punch_in_pm_second'],
                                                            ))->format('H:i:s');

                                                            if (
                                                                $punch_in_time_pm < '13:00:00' &&
                                                                $punch_out_time_pm > '17:00:00'
                                                            ) {
                                                                $punch_in_pm = new DateTime('13:00:00');
                                                                $punch_out_pm = new DateTime('17:00:00');
                                                            } else {
                                                                $punch_in_pm = new DateTime(
                                                                    $punch['punch_in_pm_first'],
                                                                );
                                                                $punch_out_pm = new DateTime(
                                                                    $punch['punch_in_pm_second'],
                                                                );
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
                            {{ $getPunch->onEachSide(1)->links() }}
                            <div class="modal fade" id="generateReportsModal" tabindex="-1"
                                aria-labelledby="generateReportsModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-dark" id="generateReportsModalLabel">Generate
                                                Reports</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('/SuperAdmin/Attendance/GenerateReports') }}"
                                                method="POST">
                                                @csrf <!-- Add CSRF token for security -->
                                                <label class="text-dark" for="employeeIds">Select User</label>
                                                <select id="employeeIds" name="employeeIds"
                                                    class="form-control underline-input">
                                                    <option value="" selected>--Select All--</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->custom_id }}">{{ $user->lastname }},
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label for="timeframeStart">From:</label>
                                                <input type="date" name="timeframeStart" id="timeframeStart"
                                                    class="form-control underline-input">
                                                <label for="timeframeEnd">To:</label>
                                                <input type="date" name="timeframeEnd" id="timeframeEnd"
                                                    class="form-control underline-input">
                                                <div class="text-center mt-1">
                                                    <button type="submit" class="btn btn-info">Generate Reports</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @foreach ($getNot['getNotify'] as $unread)
                                <!-- Modal -->
                                <div class="modal fade" id="descriptionModal{{ $unread->id }}" tabindex="-1"
                                    aria-labelledby="descriptionModalLabel{{ $unread->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-dark"
                                                    id="descriptionModalLabel{{ $unread->id }}">
                                                    {{ $unread->title_message }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{ $unread->description_message }}
                                            </div>
                                            <div class="modal-footer">
                                                @if (Auth::user()->user_type == 0)
                                                    <a href="{{ url('SuperAdmin/Read/' . $unread->id) }}"
                                                        class="btn btn-success">Ok!</a>
                                                @elseif(Auth::user()->user_type == 1)
                                                    <a href="{{ url('Admin/Read/' . $unread->id) }}"
                                                        class="btn btn-success">Ok!</a>
                                                @elseif(Auth::user()->user_type == 2)
                                                    <a href="{{ url('Employee/Read/' . $unread->id) }}"
                                                        class="btn btn-success">Ok!</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endsection
