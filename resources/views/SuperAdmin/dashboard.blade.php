@extends('layouts.app')

@section('content')
@if(\Carbon\Carbon::today()->between(
\Carbon\Carbon::parse(Auth::user()->end_of_contract)->subMonth(),
\Carbon\Carbon::parse(Auth::user()->end_of_contract)
))
<div class="col-sm-12 col-xl-12 bg-warning text-center py-3">
    <i class="fas fa-bell" style="font-size: 24px;"></i>
    <span class="ml-2 font-weight-bold">Reminder: Your contract is ending soon! Don't hesitate to contact the administrator.</span>
</div>
@endif
@include('layouts._message')




<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="row g-4">
                <div class="col-sm-4 col-xl-4">
                    <div class="bg-white text-center rounded-3  p-4">
                        <div class="row g-4">
                            <div
                                class="d-flex justify-content-center align-items-center rounded-3 col-sm-4 col-xl-4 bg-info">
                                <i class="bx fas fa-users fa-2x" style="color: #080808;"></i>
                            </div>
                            <div class=" col-sm-4 col-xl-4">
                                <span class="">
                                    <h3 class="fs-5 text-start text-dark">{{ $employeeCount }}</h3>
                                    <p class="text-dark">Employees</p>
                                </span>
                            </div>
                        </div>
                        <span class="fs-5 text-dark"></span>
                    </div>
                </div>
                <div class="rounded col-sm-4 col-xl-4">
                    <div class="bg-white text-center rounded-3  p-4">
                        <div class="row g-4">
                            <div
                                class="d-flex justify-content-center align-items-center rounded-3 col-sm-4 col-xl-4 bg-danger">
                                <i class="far fa-building fa-2x" style="color: #000000;"></i>
                            </div>
                            <div class=" col-sm-4 col-xl-4">
                                <span class="">
                                    <h3 class="fs-5 text-start text-dark">{{ $departmentCount }}</h3>
                                    <p class="text-dark">Departments</p>
                                </span>
                            </div>
                        </div>
                        <span class="fs-5 text-dark"></span>
                    </div>
                </div>
                <div class="col-sm-4 col-xl-4">
                    <div class="bg-white text-center rounded-3  p-4">

                        <div class="row g-4">
                            <div
                                class="d-flex justify-content-center align-items-center rounded-3 col-sm-4 col-xl-4 bg-light">
                                <i class="fas fa-bullhorn fa-2x" style="color: #000000;"></i>
                            </div>
                            <div class=" col-sm-4 col-xl-4">
                                <span class="">
                                    @foreach ($notification['notify'] as $key)
                                    <h3 class="fs-5 text-start text-dark">{{ $key->unread }}</h3>
                                    @endforeach
                                    <p class="text-dark">Announcement</p>
                                </span>
                            </div>
                        </div>
                        <span class="fs-5 text-dark"></span>
                    </div>
                </div>

                <div class=" pt-4 px-4 ">
                    <div class="row g-4">

                        <div class="col-sm-12 col-xl-4 rounded">
                            <div class="bg-white rounded-3  h-100 p-4">
                                <h6 class="mb-4 text-center text-dark">Employee Growth Rate</h6>
                                <canvas id="growthChart" width="200" height="100"></canvas>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xl-4 rounded">
                            <div class="bg-white rounded-3  h-100 p-4">
                                <h6 class="mb-4 text-center text-dark">Retention Rate Chart</h6>
                                <canvas id="retentionRateChart" width="200" height="100"></canvas>
                            </div>
                        </div>

                        <div class="col-sm-12 col-xl-4 rounded">
                            <div class="bg-white rounded-3  h-100 p-4">
                                <h6 class="mb-4 text-center text-dark">Turnover Rate Chart</h6>
                                <canvas id="turnoverRateChart" width="200" height="100"></canvas>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xl-4 rounded">
                            <div class="bg-white rounded-3  h-100 p-4">
                                <h6 class="mb-4 text-center text-dark">Gender Chart</h6>
                                <canvas id="genderChart" width="200" height="100"></canvas>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xl-4 rounded">
                            <div class="bg-white rounded-3  h-100 p-4">
                                <h6 class="mb-4 text-center text-dark">Age Chart</h6>
                                <canvas id="ageChart" width="200" height="100"></canvas>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xl-4 rounded">
                            <div class="bg-white rounded-3 h-100 p-4">
                                <h6 class="mb-4 fs-2 text-primary">Today's Birthday</h6>
                                @foreach ($birthdayUsers as $user)
                                <div class="my-2 rounded-2 border-start border-primary">
                                    <span class=" d-flex justify-content-between align-items-center">
                                        <img class="my-1 mx-1"
                                            src="{{ asset('public/accountprofile/' . $user->profile_pic) }}"
                                            alt="Employee" width="30px">
                                        <h3 class="fs-5 text-start text-dark">Today is {{ $user->name }}'s
                                            birthday!</h3>
                                        <i class="fas fa-birthday-cake" style="color: #000000;"></i>
                                    </span>
                                </div>
                                @endforeach
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection