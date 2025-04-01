@extends('Layouts.App')

@section('content')
@include('Layouts.Message')



<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="d-flex justify-content-between">
                <div class=" bg-today mx-2 p-2 rounded-3 text-center" style="width: 500px;">
                    <h1>{{Auth::user()->sick_leave}}</h1>
                    <span class="text-dark"><b>Sick Leave</b></span>
                    <p class="mt-1">Balance</p>
                </div>
                <div class=" bg-week mx-2 p-2 rounded-3 text-center" style="width: 500px;">
                    <h1>{{Auth::user()->vacation_leave}}</h1>
                    <span class="text-dark"><b>Vacation Leave</b></span>
                    <p class="mt-1">Balance</p>
                </div>
                <div class=" bg-month mx-2 p-2 rounded-3 text-center" style="width: 500px;">
                    <h1>{{Auth::user()->special_previlege_leave}}</h1>
                    <span class="text-dark"><b>Special Previlege Leave</b></span>
                    <p class="mt-1">Balance</p>
                </div>
            </div>
        </div>


        <div class="col-sm-12 col-xl-12 bg-white rounded-1">
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
                <a class=" rounded-1 p-2 bg-success text-white" href="{{ url('/SuperAdmin/Leave/MyLeaveCreditCard') }}">View My Leave Credit Card</a>
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
                    <tbody>
                        @foreach($leaveData as $index => $leave)
                        @if($leave->employee_id == Auth::user()->custom_id)
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

                                @if($leave->status == 'Pending')
                                <span class=" rounded-pill shadow p-2"><i class="far fa-dot-circle text-warning"></i> {{$leave->status}}</span>
                                @elseif($leave->status == 'Approved')
                                <span class=" rounded-pill shadow p-2"><i class="far fa-dot-circle text-success"></i> {{$leave->status}}</span>
                                @elseif($leave->status == 'Declined')
                                <span class=" rounded-pill shadow p-2"><i class="far fa-dot-circle text-danger"></i> {{$leave->status}}</span>

                                @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                {{ $leaveData->appends(['search' => request('search')])->links() }}

            </div>
        </div>
    </div>


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
    @endsection