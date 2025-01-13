@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="row g-4">
                <div class="col-sm-12 col-xl-12">
                    <div class="bg-white text-center rounded-3  p-4">

                        <div class="col-12">
                            <div class="bg-white rounded h-100 p-4">
                                @include('layouts._message')
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class=" text-dark ">List of Archived Employees</h6>


                                    <div class="d-flex justify-content-between align-items-center">
                                        <form action="" class="me-1">
                                            @csrf
                                            <input type="search" id="search" class="form-control bg-transparent"
                                                name="search" placeholder="Search Here" value="{{ request('search') }}">
                                            <button style="display: none;" class="btn btn-success m-1"
                                                type="submit">Search</button>
                                            <button style="display: none;" type="hidden" class="btn btn-success m-1"
                                                onclick="clearSearch()">Clear</button>
                                        </form>
                                        @if(Auth::user()->user_type == 0)

                                        <a href="{{url('SuperAdmin/Employee')}}" class="m-1 btn btn-primary ">Back</a>
                                        @elseif(Auth::user()->user_type == 1)

                                        <a href="{{url('Admin/Employee')}}" class="m-1 btn btn-primary ">Back</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table text-start align-middle ">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">ID Number</th>
                                                <th scope="col">Full Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Department</th>
                                                <th scope="col">Position</th>
                                                <th scope="col">Date of Assumption</th>
                                                <th scope="col">Date Archive</th>
                                                <th scope="col">Restore</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getEmployee as $index => $employee)
                                            <tr>
                                                <th scope="row">{{ ($getEmployee->currentPage() - 1) * $getEmployee->perPage() + $index + 1 }}</th>
                                                <td>{{ $employee->custom_id}}</td>
                                                <td class="text-capitalize">{{ $employee->lastname}}, {{ $employee->name}} @if($employee->suffix == 'N/A')  @else {{$employee->suffix}}@endif</td>

                                                <td>{{ $employee->email}}</td>
                                                <td>@foreach ($depart as $data)
                                                    @if ($employee->department == $data->id)
                                                    {{ $data->abbreviation }}
                                                    @endif
                                                    @endforeach
                                                </td>
                                                <td>@foreach ($pos as $data)
                                                    @if ($employee->position == $data->id)
                                                    {{ $data->abbreviation }}
                                                    @endif
                                                    @endforeach
                                                </td>
                                                <td>{{$employee->date_of_assumption}}</td>
                                                <td>{{ \Carbon\Carbon::parse( $employee->date_archive)->format('Y, M d - g:i A') }}</td>
                                                @if(Auth::user()->user_type == 0)
                                                <td>
                                                    <a class=" rounded-1" href="{{ url('SuperAdmin/Employee/Restore/'.$employee->id)}}"> <i class="fas fa-trash-restore" style="color: #63E6BE;"></i></a>
                                                </td>

                                                @elseif(Auth::user()->user_type == 1)
                                                <td>
                                                    <a class=" rounded-1" href="{{ url('Admin/Employee/Restore/'.$employee->id)}}"> <i class="fas fa-trash-restore" style="color: #63E6BE;"></i></a>

                                                </td>

                                                @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{$getEmployee->onEachSide(1)->links()}}
                                </div>
                            </div>
                        </div>

                    </div>
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