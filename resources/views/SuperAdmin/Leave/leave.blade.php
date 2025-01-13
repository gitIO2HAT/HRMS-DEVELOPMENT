@extends('layouts.app')

@section('content')
@include('layouts._message')



<div class="container-fluid pt-4 px-4">
    <div class="row g-4">


        <div class=" col-sm-12 col-xl-12 bg-white rounded-1">
            <div class="row">
                <div class="col-6 col-sm-6 col-xl-6 mt-2">
                    <form action="{{ url()->current() }}" method="GET" class="me-1">
                        @csrf
                        <input type="search" id="search" class="form-control bg-transparent"
                            name="search" placeholder="Search Here"
                            value="{{ request('search') }}">
                        <button style="display: none;" class="btn btn-success m-1" type="submit">Search</button>
                    </form>
                </div>
                <div class="col-6 col-sm-6 col-xl-6 d-flex justify-content-end mt-2">
                    <a type="button" class="mx-2 rounded-2 bg-success text-white p-2" data-bs-toggle="modal" data-bs-target="#addLeaveModal">
                        Add Leave
                    </a>
                    <a type="button" class="mx-2 rounded-2 bg-warning text-dark p-2" data-bs-toggle="modal" data-bs-target="#generateModal">
                        Generate Reports
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table text-start align-middle ">
                    <thead>
                        <tr class="">
                            <th scope="col">#</th>
                            <th scope="col">Employee Name</th>
                            <th scope="col">Leave Type</th>
                            <th scope="col">Inclusive Date</th>
                            <th scope="col">No. of Days</th>
                            <th scope="col">Documents Provided</th>
                            <th scope="col">Date of Application</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody class="" >
                        @foreach($leaveData as $index => $leave)
                        <tr class="">
                            <td>{{ ($leaveData->currentPage() - 1) * $leaveData->perPage() + $index + 1 }}</td>
                            <td class="text-capitalize"><img class="rounded-circle me-lg-2"
                                    src="{{ asset('public/accountprofile/' .$leave->user->profile_pic) }}"
                                    alt="" style="width: 40px; height: 40px;">
                                {{$leave->user->lastname}}, {{$leave->user->name}} {{$leave->user->middlename}} @if($leave->user->suffix == 'N/A')  @else {{$leave->user->suffix}}@endif
                            </td>
                            <td>{{$leave->leavetype->status}}</td>
                            <td>{{ \Carbon\Carbon::parse($leave->from)->format('F j, Y') }} - {{ \Carbon\Carbon::parse($leave->to)->format('F j, Y') }}</td>
                            <td>{{$leave->leave_days}}</td>
                            <td>

                                @if($leave->monetization || $leave->terminal || $leave->adoption)
                                {{-- Check if monetization document exists --}}
                                @if($leave->monetization)
                                @php
                                // Get the file extension for monetization (in lowercase to handle case-sensitivity)
                                $fileExtension = strtolower(pathinfo($leave->monetization, PATHINFO_EXTENSION));
                                @endphp


                                @if(in_array($fileExtension, ['png', 'jpeg', 'jpg']))
                                {{-- Display image document and provide download link --}}
                                <img src="{{ asset('public/leavedocuments/' . $leave->monetization) }}" alt="Image file" style="max-width: 40px;" /> |
                                <a href="{{ asset('public/leavedocuments/' . $leave->monetization) }}" download>Download Image ({{ strtoupper($fileExtension) }})</a>
                                @else
                                {{-- Handle unsupported file formats --}}
                                Invalid file format.
                                @endif

                                {{-- Check if Terminal document exists --}}
                                @elseif($leave->terminal)
                                @php
                                // Get the file extension for terminal (in lowercase)
                                $fileExtension = strtolower(pathinfo($leave->terminal, PATHINFO_EXTENSION));
                                @endphp


                                @if(in_array($fileExtension, ['png', 'jpeg', 'jpg']))
                                {{-- Display image document and provide download link --}}
                                <img src="{{ asset('public/leavedocuments/' . $leave->terminal) }}" alt="Image file" style="max-width: 40px;" /> |
                                <a href="{{ asset('public/leavedocuments/' . $leave->terminal) }}" download>Download Image ({{ strtoupper($fileExtension) }})</a>
                                @else
                                {{-- Handle unsupported file formats --}}
                                Invalid file format.
                                @endif

                                {{-- Check if Adoption document exists --}}
                                @elseif($leave->adoption)
                                @php
                                // Get the file extension for adoption (in lowercase)
                                $fileExtension = strtolower(pathinfo($leave->adoption, PATHINFO_EXTENSION));
                                @endphp


                                @if(in_array($fileExtension, ['png', 'jpeg', 'jpg']))
                                {{-- Display image document and provide download link --}}
                                <img src="{{ asset('public/leavedocuments/' . $leave->adoption) }}" alt="Image file" style="max-width: 40px;" /> |
                                <a href="{{ asset('public/leavedocuments/' . $leave->adoption) }}" download>Download Image ({{ strtoupper($fileExtension) }})</a>
                                @else
                                {{-- Handle unsupported file formats --}}
                                Invalid file format.
                                @endif
                                @endif

                                {{-- If no files are available --}}
                                @else
                                No file available.
                                @endif



                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($leave->created_at)->format('F j, Y') }}
                            </td>
                            <td>
                                <a type="button" class="mx-2" data-bs-toggle="modal" data-bs-target="#editStatusModal-{{ $leave->id }}" data-leave-id="{{ $leave->id }}">
                                    @if($leave->status == 'Pending')
                                    <span class=" rounded-pill shadow p-2"><i class="far fa-dot-circle text-warning"></i> {{$leave->status}}</span>
                                    @elseif($leave->status == 'Approved')
                                    <span class=" rounded-pill shadow p-2"><i class="far fa-dot-circle text-success"></i> {{$leave->status}}</span>
                                    @elseif($leave->status == 'Declined')
                                    <span class=" rounded-pill shadow p-2"><i class="far fa-dot-circle text-danger"></i> {{$leave->status}}</span>

                                    @endif
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $leaveData->appends(['search' => request('search')])->links() }}

            </div>
        </div>
    </div>

    <div class="modal fade" id="addLeaveModal" tabindex="-1" aria-labelledby="addLeaveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="addLeaveModalLabel">Add Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Form content here -->
                    <form action="/SuperAdmin/Leave/AddLeave" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="text-center">

                            <div>
                                <label from="employee_id">Details Leave</label>
                                <select id="employee_id" name="employee_id" class="form-control underline-input">
                                    <option value="" disabled selected>Select Employee</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->custom_id }}">{{ $user->lastname }},{{ $user->name }} {{ $user->middlename }} @if($user->suffix == 'N/A')  @else {{$user->suffix}}@endif</option>
                                    @endforeach
                                </select>
                                @if($errors->has('employee_id'))
                                <span class="text-danger">{{ $errors->first('employee_id') }}</span>
                                @endif
                            </div>
                            <div>
                                <label for="leave_type">Leave Type</label>
                                <select id="leave_type" name="leave_type" class="form-control underline-input">
                                    <option value="" disabled selected>Leave Type</option>
                                    @foreach($leavetype as $type)
                                    <option value="{{ $type->id }}">{{ $type->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="details-leave-container" class="hidden">
                                <label for="details-leave">Details Leave</label>
                                <select id="details-leave" name="details_leave" class="form-control underline-input">
                                    <option value="" disabled selected>Details Leave</option>
                                    <option value="1">Within the Philippines</option>
                                    <option value="2">Abroad (Please Specify if na pili ang abroad)</option>
                                </select>
                                <div id="details-abroad-container" class="hidden">
                                    <label for="abroad">Specify:</label>
                                    <input type="text" name="abroad" id="abroad" class="form-control underline-input">
                                </div>
                            </div>
                            <div id="details-sick-container" class="hidden">
                                <label for="details-sick">Details Leave</label>
                                <select id="details-sick" name="details_leave_sick" class="form-control underline-input">
                                    <option value="" disabled selected>Details Leave</option>
                                    <option value="In Hospital">In Hospital</option>
                                    <option value="Out Patient">Out Patient</option>
                                </select>
                            </div>
                            <div id="details-monetization-container" class="hidden">
                                <label for="monetization_leave">Upload Document for Monetization Leave</label>
                                <input type="file" name="monetization" id="monetization_leave" class="form-control underline-input">
                            </div>
                            <div id="details-terminal-container" class="hidden">
                                <label for="terminal_leave">Upload Document for Terminal Leave</label>
                                <input type="file" name="terminal" id="terminal_leave" class="form-control underline-input">
                            </div>
                            <div id="details-adoption-container" class="hidden">
                                <label for="adoption_leave">Upload Document for Adoption Leave</label>
                                <input type="file" name="adoption" id="adoption_leave" class="form-control underline-input">
                            </div>
                            <script>
                                document.getElementById('leave_type').addEventListener('change', function() {
                                    const selectedLeaveType = this.value;
                                    const detailsLeaveContainer = document.getElementById('details-sick-container');

                                    if (selectedLeaveType == '3') {
                                        detailsLeaveContainer.classList.remove('hidden'); // Show the "Details Leave" section
                                    } else {
                                        detailsLeaveContainer.classList.add('hidden'); // Hide the "Details Leave" section
                                    }
                                });
                            </script>
                            
                            <script>
                                document.getElementById('details-leave').addEventListener('change', function() {
                                    const selectedLeaveType = this.value;
                                    const detailsLeaveContainer = document.getElementById('details-abroad-container');

                                    if (selectedLeaveType == '2') {
                                        detailsLeaveContainer.classList.remove('hidden'); // Show the "Specify" section
                                    } else {
                                        detailsLeaveContainer.classList.add('hidden'); // Hide the "Specify" section
                                    }
                                });
                            </script>
                            <script>
                                document.getElementById('leave_type').addEventListener('change', function() {
                                    const selectedLeaveType = this.value;
                                    const detailsLeaveContainer = document.getElementById('details-leave-container');

                                    if (selectedLeaveType == '1' || selectedLeaveType == '6') {
                                        detailsLeaveContainer.classList.remove('hidden'); // Show the "Details Leave" section
                                    } else {
                                        detailsLeaveContainer.classList.add('hidden'); // Hide the "Details Leave" section
                                    }
                                });
                            </script>
                            <script>
                                document.getElementById('leave_type').addEventListener('change', function() {
                                    const selectedLeaveType = this.value;
                                    const detailsLeaveContainer = document.getElementById('details-monetization-container');

                                    if (selectedLeaveType == '13') {
                                        detailsLeaveContainer.classList.remove('hidden'); // Show the "monetization" section
                                    } else {
                                        detailsLeaveContainer.classList.add('hidden'); // Hide the "monetization" section
                                    }
                                });
                            </script>
                            <script>
                                document.getElementById('leave_type').addEventListener('change', function() {
                                    const selectedLeaveType = this.value;
                                    const detailsLeaveContainer = document.getElementById('details-terminal-container');

                                    if (selectedLeaveType == '14') {
                                        detailsLeaveContainer.classList.remove('hidden'); // Show the "terminal" section
                                    } else {
                                        detailsLeaveContainer.classList.add('hidden'); // Hide the "terminal" section
                                    }
                                });
                            </script>
                            <script>
                                document.getElementById('leave_type').addEventListener('change', function() {
                                    const selectedLeaveType = this.value;
                                    const detailsLeaveContainer = document.getElementById('details-adoption-container');

                                    if (selectedLeaveType == '15') {
                                        detailsLeaveContainer.classList.remove('hidden'); // Show the "adoption" section
                                    } else {
                                        detailsLeaveContainer.classList.add('hidden'); // Hide the "adoption" section
                                    }
                                });
                            </script>
                            @if($errors->has('leave_type'))
                            <span class="text-danger">{{ $errors->first('leave_type') }}</span>
                            @endif
                            <label for="inclusive-from">Inclusive From:</label>
                            <input type="date" name="from" id="inclusive-from" class="form-control underline-input">
                            <label for="inclusive-to">Inclusive To:</label>
                            <input type="date" name="to" id="inclusive-to" class="form-control underline-input">

                            <label for="leave_days">Number of Working Days Applied for:</label>
                            <input type="text" name="leave_days" id="leave_days" readonly class="form-control underline-input">
                            <script>
                                document.getElementById('inclusive-from').addEventListener('change', calculateLeaveDays);
                                document.getElementById('inclusive-to').addEventListener('change', calculateLeaveDays);

                                function calculateLeaveDays() {
                                    const fromDate = new Date(document.getElementById('inclusive-from').value);
                                    const toDate = new Date(document.getElementById('inclusive-to').value);

                                    if (fromDate && toDate && toDate >= fromDate) {
                                        let count = 0;
                                        let currentDate = new Date(fromDate);

                                        while (currentDate <= toDate) {
                                            const dayOfWeek = currentDate.getDay();
                                            // 0 = Sunday, 6 = Saturday; exclude weekends
                                            if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                                                count++;
                                            }
                                            currentDate.setDate(currentDate.getDate() + 1); // Increment by one day
                                        }

                                        document.getElementById('leave_days').value = count;
                                    } else {
                                        document.getElementById('leave_days').value = ''; // Clear value if invalid dates
                                    }
                                }
                            </script>
                            <button type="submit" class="btn btn-success mt-3">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @foreach($leaveData as $leave)
    <div class="modal fade" id="editStatusModal-{{ $leave->id }}" tabindex="-1" aria-labelledby="editStatusModalLabel-{{ $leave->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStatusModalLabel-{{ $leave->id }}">Edit Leave Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="statusForm-{{ $leave->id }}" method="POST" action="{{ url('/SuperAdmin/Leave/AddLeave/EditStatus/' . $leave->id) }}">
                        @csrf
                        @method('PATCH')


                        <label for="status-{{ $leave->id }}">Status:</label>
                        <select name="status" id="status-{{ $leave->id }}" class="form-control underline-input">
                            <option value="Pending" @if($leave->status == 'Pending') selected @endif>Pending</option>
                            <option value="Approved" @if($leave->status == 'Approved') selected @endif>Approved</option>
                            <option value="Declined" @if($leave->status == 'Declined') selected @endif>Declined</option>
                        </select>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

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

    <div class="modal fade" id="generateModal" tabindex="-1" aria-labelledby="generateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="generateModalLabel">Generate Reports</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/SuperAdmin/Leave/GenerateReports') }}" method="POST">
                        @csrf <!-- Add CSRF token for security -->
                        <label class="text-dark" for="employeeIds">Select User</label>
                        <select id="employeeIds" name="employeeIds" class="form-control underline-input">
                            <option value="" selected>--Select All--</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->custom_id }}">{{ $user->lastname }}, {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <label class="text-dark" for="employeetype">Select Type</label>
                        <select id="employeetype" name="employeetype" class="form-control underline-input">
                            <option value="" selected>--Select All--</option>
                            @foreach ($leavetype as $leave)
                                <option value="{{$leave->id}}">{{$leave->status}}</option>
                            @endforeach
                        </select>
                        <label class="text-dark" for="employeestatus">Select Status</label>
                        <select id="employeestatus" name="employeestatus" class="form-control underline-input">
                            <option value="" selected>--Select All--</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Declined">Declined</option>
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


    @endsection