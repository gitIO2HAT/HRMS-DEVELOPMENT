@extends('Layouts.App')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="row g-4">
                <div class="container-fluid pt-4 px-4">
                    <div class="row g-4">
                        <div class="col-sm-12 col-xl-12">
                            <div class="row g-4">
                                <div class=" pt-4 px-4 ">
                                    <div class="row g-4">
                                        <div class="col-sm-12 col-xl-7 rounded">
                                            <div class=" bg-white rounded-3  h-100 p-4">
                                                
                                                <form action="{{ url('/SuperAdmin/Attendance/DailyTimeRecord') }}" method="GET">
                                                    @csrf
                                                    <div class="d-flex align-items-center dropdown-container">
                                                        <div class="me-2">
                                                            <label for="year" class="form-label">Year</label>
                                                            <select class="form-select dropdown-shortened" id="year" name="year" required>
                                                                @for ($year = now()->year; $year >= 2000; $year--)
                                                                    <option value="{{ $year }}" {{ $year == old('year', $selectedYear) ? 'selected' : '' }}>
                                                                        {{ $year }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label for="month" class="form-label">Month</label>
                                                            <select class="form-select dropdown-shortened" id="month" name="month" required>
                                                                @foreach ([1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'] as $key => $value)
                                                                    <option value="{{ $key }}" {{ $key == old('month', $selectedMonth) ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 mt-3">
                                                        <button type="submit" class="btn btn-custom">Generate Daily Time Record</button>
                                                    </div>
                                                </form>

                                                <table
                                                    class="table table-striped table-hover table-responsive table-bordered text-start align-middle ">
                                                    <thead class="text-dark">
                                                        <tr>
                                                            <th class="bg-head text-center" scope="col" colspan="9">My Attendance</th>

                                                        </tr>
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
                                                        @foreach($attendanceData as $data)
                                                        @if ($data['user_id'] === $punch->user_id && $data['date'] === $punch->date)
                                                        <tr>
                                                            <th scope="row">{{ ($getall->currentPage() - 1) * $getall->perPage() + $index + 1 }}</th>
                                                            <td class="text-capitalize"><img class="rounded-circle me-lg-2"
                                                                    src="{{ asset('public/accountprofile/' . $punch->user->profile_pic) }}"
                                                                    alt=""
                                                                    style="width: 40px; height: 40px;">

                                                                {{ $punch->user->lastname }}, {{ $punch->user->name }} {{ $punch->user->middlename }} @if($punch->user->suffix == 'N/A') @else {{$punch->user->suffix}}@endif
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
                                                                        <td></td>
                                                                        <td></td>
                                                                        @else
                                                                        <td style="color:red;">{{$remainingHours}}</td>
                                                                        <td style="color:red;">{{$remainingMinutesMod}}</td>
                                                                        @endif

                                                        </tr>
                                                        @endif
                                                        @endforeach
                                                        @endforeach
                                                      
                                                    </tbody>
                                                </table>
                                                {{ $getPunch->onEachSide(1)->links() }}
                                                @foreach($getNot['getNotify'] as $unread)
                                                <!-- Modal -->
                                                <div class="modal fade" id="descriptionModal{{ $unread->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $unread->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title text-dark" id="descriptionModalLabel{{ $unread->id }}">{{$unread->title_message}}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{ $unread->description_message }}
                                                            </div>
                                                            <div class="modal-footer">
                                                                @if(Auth::user()->user_type == 0)
                                                                <a href="{{ url('SuperAdmin/Read/'.$unread->id)}}" class="btn btn-success">Ok!</a>
                                                                @elseif(Auth::user()->user_type == 1)
                                                                <a href="{{ url('Admin/Read/'.$unread->id)}}" class="btn btn-success">Ok!</a>
                                                                @elseif(Auth::user()->user_type == 2)
                                                                <a href="{{ url('Employee/Read/'.$unread->id)}}" class="btn btn-success">Ok!</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach



                                            </div>

                                        </div>
                                        <div class="col-sm-12 col-xl-5 rounded">
                                            <div class="bg-white rounded-3 h-100 p-4">
                                                <div class="row g-4">
                                                    <div class="col-sm-12">
                                                        <div class="bg-white rounded-3 h-100 p-4">
                                                            <h4 class="text-dark mb-4">Statistics</h4>
                                                            <div
                                                                class="bg-today shadow-sm p-3 mb-5 bg-body-tertiary rounded-2 ">
                                                                <div class="d-flex justify-content-between">
                                                                    <h6 class="text-start text-dark">Today</h6>
                                                                    <span>{{$Today}} / 8 hrs</span>
                                                                </div>
                                                                <div class="d-flex justify-content-center">
                                                                    <progress value="{{$TodaySeconds}}"
                                                                        max="28800"></progress>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="bg-week shadow-sm p-3 mb-5 bg-body-tertiary rounded-2 ">
                                                                <div class="d-flex justify-content-between">
                                                                    <h6 class="text-start text-dark">This Week</h6>
                                                                    <span>{{ $Week }} / 40 hrs</span>
                                                                </div>
                                                                <div class="d-flex justify-content-center">
                                                                    <progress
                                                                        value="{{ $WeekSeconds }}"
                                                                        max="144000"></progress>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="bg-month shadow-sm p-3 mb-5 bg-body-tertiary rounded-2 ">
                                                                <div class="d-flex justify-content-between">
                                                                    <h6 class="text-start text-dark">This Month</h6>
                                                                    <span>{{ $Month }} / 160 hrs</span>
                                                                </div>
                                                                <div class="d-flex justify-content-center">
                                                                    <progress
                                                                        value="{{ $MonthSeconds }}"
                                                                        max="576000"></progress>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="bg-remaining shadow-sm p-3 mb-5 bg-body-tertiary rounded-2 ">
                                                                <div class="d-flex justify-content-between">
                                                                    <h6 class="text-start text-dark">Remaining</h6>
                                                                    <span>{{ $MonthRemaining }} / 160 hrs</span>
                                                                </div>
                                                                <div class="d-flex justify-content-center">
                                                                    <progress
                                                                        value="{{ $MonthRemainingSeconds }}"
                                                                        max="576000"></progress>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="generateReportsModal" tabindex="-1" aria-labelledby="generateReportsModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-dark" id="generateReportsModalLabel">Generate Reports</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ url('/SuperAdmin/Attendance/GenerateReports') }}" method="POST">
                                                            @csrf <!-- Add CSRF token for security -->
                                                            <label class="text-dark" for="employeeIds">Select User</label>
                                                            <select id="employeeIds" name="employeeIds" class="form-control underline-input">
                                                                <option value="" selected>--Select All--</option>
                                                                @foreach ($users as $user)
                                                                <option value="{{ $user->custom_id }}">{{ $user->lastname }}, {{ $user->name }}
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


                                        <script>
                                            function updateDateTime() {
                                                const today = new Date();
                                                const day = today.getDate();
                                                const monthNames = [
                                                    "January", "February", "March", "April", "May", "June",
                                                    "July", "August", "September", "October", "November", "December"
                                                ];
                                                const month = monthNames[today.getMonth()];
                                                const year = today.getFullYear();

                                                const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                                                const dayOfWeek = daysOfWeek[today.getDay()];
                                                let hours = today.getHours();
                                                const minutes = today.getMinutes().toString().padStart(2, '0');
                                                const seconds = today.getSeconds().toString().padStart(2, '0');
                                                const ampm = hours >= 12 ? 'PM' : 'AM';
                                                hours = hours % 12;
                                                hours = hours ? hours : 12; // the hour '0' should be '12'
                                                const timeString = `${hours}:${minutes}:${seconds}${ampm}`;

                                                const dateString =
                                                    `<span class="timesheet">Timesheet</span> <span class="date">${day} ${month} ${year} <span> `;
                                                document.getElementById('current-date-superadmin').innerHTML = dateString;
                                            }

                                            // Update the date and time every second
                                            setInterval(updateDateTime, 1000);

                                            // Initial call to display the date and time immediately on page load
                                            updateDateTime();
                                        </script>
                                        <script>
                                            function updateTime() {
                                                const today1 = new Date();
                                                const day1 = today1.getDate();
                                                const daySuffix1 = (day1) => {
                                                    if (day1 > 3 && day1 < 21) return 'th';
                                                    switch (day1 % 10) {
                                                        case 1:
                                                            return "st";
                                                        case 2:
                                                            return "nd";
                                                        case 3:
                                                            return "rd";
                                                        default:
                                                            return "th";
                                                    }
                                                };

                                                const daysOfWeek1 = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                                                const months1 = [
                                                    "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                                                ];

                                                const dayOfWeek1 = daysOfWeek1[today1.getDay()];
                                                const month1 = months1[today1.getMonth()];
                                                const year1 = today1.getFullYear();

                                                let hours1 = today1.getHours();
                                                const minutes1 = today1.getMinutes().toString().padStart(2, '0');
                                                const seconds1 = today1.getSeconds().toString().padStart(2, '0');
                                                const ampm1 = hours1 >= 12 ? ' PM' : ' AM';
                                                hours1 = hours1 % 12;
                                                hours1 = hours1 ? hours1 : 12; // the hour '0' should be '12'
                                                const timeString1 = `${hours1}:${minutes1}:${seconds1}${ampm1}`;

                                                const dateString1 =
                                                    `<span class="date1">${dayOfWeek1}, ${day1}${daySuffix1(day1)} ${month1} ${year1} ${timeString1}</span>`;
                                                document.getElementById('current-date1-superadmin').innerHTML = dateString1;
                                            }

                                            // Update the time every second
                                            setInterval(updateTime, 1000);

                                            // Initial call to display the time immediately on page load
                                            updateTime();
                                        </script>

                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                let intervalId;
                                                let totalDuration = 0;
                                                const progressBar = document.getElementById("progressBar");

                                                function updateDisplay() {
                                                    const hours = Math.floor(totalDuration / 3600);
                                                    const minutes = Math.floor((totalDuration % 3600) / 60);
                                                    const seconds = totalDuration % 60;

                                                    const hoursDisplay = hours > 0 ? `${hours}h ` : "";
                                                    const minutesDisplay = minutes > 0 ? `${minutes}m ` : "";
                                                    const secondsDisplay = `${seconds}s`;

                                                    document.getElementById("todays-hours").textContent = hoursDisplay + minutesDisplay +
                                                        secondsDisplay;
                                                    document.getElementById("todays-hours-stat").textContent = hoursDisplay + minutesDisplay +
                                                        secondsDisplay + ' / 8 hrs'; // Update this span
                                                    progressBar.value = totalDuration; // Update progress bar
                                                }

                                                function fetchCurrentTime() {
                                                    fetch("{{ route('current-time-superadmin') }}")
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            totalDuration = data.totalDuration;
                                                            updateDisplay();
                                                        })
                                                        .catch(error => console.error("Error fetching current time:", error));
                                                }

                                                function startTimer() {
                                                    intervalId = setInterval(() => {
                                                        totalDuration++;
                                                        updateDisplay();
                                                    }, 1000);
                                                }

                                                function stopTimer() {
                                                    clearInterval(intervalId);
                                                }

                                                fetchCurrentTime();

                                                document.getElementById("clockInButton").addEventListener("click", function() {
                                                    startTimer();
                                                });

                                                document.getElementById("clockOutButton").addEventListener("click", function() {
                                                    stopTimer();
                                                });

                                                // Initial display update
                                                setInterval(updateDisplay, 60000);
                                                updateDisplay();
                                            });
                                        </script>

                                        <script>
                                            async function fetchInternetTime() {
                                                try {
                                                    const response = await fetch(
                                                        `http://api.timezonedb.com/v2.1/get-time-zone?key=INQ8VCI2UGFC&format=json&by=zone&zone=Asia/Manila`
                                                    );
                                                    if (!response.ok) {
                                                        throw new Error('Network response was not ok');
                                                    }
                                                    const data = await response.json();
                                                    return new Date(data.formatted);
                                                } catch (error) {
                                                    console.error('There was a problem with the fetch operation:', error);
                                                    return null;
                                                }
                                            }

                                            async function checkTimeAndDisplayButton() {
                                                const now = await fetchInternetTime();
                                                if (!now) {
                                                    console.error('Unable to fetch the internet time');
                                                    return;
                                                }

                                                const formatter = new Intl.DateTimeFormat('en-US', {
                                                    timeZone: 'Asia/Manila',
                                                    hour: '2-digit',
                                                    minute: '2-digit',
                                                    hour12: false
                                                });

                                                const formattedTime = formatter.formatToParts(now);
                                                const hours = parseInt(formattedTime.find(part => part.type === 'hour').value);
                                                const minutes = parseInt(formattedTime.find(part => part.type === 'minute').value);

                                                /*  const isClockInTime = (
                                                      (hours === 7 && minutes >= 0 && minutes <= 59) ||
                                                      (hours === 8 && minutes >= 0 && minutes <= 15) ||
                                                      (hours === 12 && minutes >= 31 && minutes <= 59) ||
                                                      (hours === 13 && minutes >= 0 && minutes <= 15)
                                                  );

                                                  const isClockOutTime = (
                                                      (hours === 12 && minutes >= 0 && minutes <= 30) ||
                                                      (hours === 17 && minutes >= 0 && minutes <= 59) ||
                                                      (hours === 18 && minutes === 0)
                                                  );
                                                  */
                                                const isClockInTime = true;

                                                // Always allow clock-out at any time
                                                const isClockOutTime = true;

                                                document.getElementById('clockInButton').style.display = isClockInTime ? 'block' : 'none';
                                                document.getElementById('clockOutButton').style.display = isClockOutTime ? 'block' : 'none';
                                            }

                                            // Check every minute if the button should be displayed
                                            setInterval(checkTimeAndDisplayButton, 60000);

                                            // Initial check when the page loads
                                            checkTimeAndDisplayButton();
                                        </script>
                                        @endsection