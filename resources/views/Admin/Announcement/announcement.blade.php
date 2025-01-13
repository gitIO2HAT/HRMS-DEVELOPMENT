@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="row g-4">
                <div class="col-sm-12 col-xl-12">
                    <div class="bg-white text-center rounded-3  p-4">

                        @include('layouts._message')
                        <div class="col-12">
                            <div class="bg-white rounded h-100 p-4">
                                <h5 class="text-dark">Announcement Board</h5>
                                <div class="d-flex justify-content-end align-items-end">



                                    <div>

                                        <a class="m-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            <i class="fas fa-plus" style="color: #1c9445;"></i>
                                        </a>

                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Title</th>
                                                <th scope="col">Start</th>
                                                <th scope="col">End</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getAnn as $index => $announce)
                                            <tr data-bs-toggle="modal" data-bs-target="#dataModal{{ $announce->id }}" style="cursor:pointer;">
                                                <th class="border-bottom border-white" scope="row">{{ ($getAnn->currentPage() - 1) * $getAnn->perPage() + $index + 1 }}</th>
                                                <td class="border-bottom border-white">{{$announce->title}}</td>
                                                <td class="border-bottom border-white">{{ date('Y-m-d h:i A',
                                                    strtotime($announce->scheduled_date)) }}</td>
                                                <td class="border-bottom border-white">{{ date('Y-m-d h:i A',
                                                    strtotime($announce->scheduled_end)) }}</td>
                                                <td class="border-bottom border-white">
                                                    @if($announce->scheduled_date > $currentDateTime)
                                                    <span class=" rounded-pill shadow p-2"><i class="far fa-dot-circle text-warning"></i> Ongoing</span>

                                                    @elseif($announce->scheduled_date <= $currentDateTime && $announce->scheduled_end >= $currentDateTime)
                                                        <span class=" rounded-pill shadow p-2"><i class="far fa-dot-circle text-danger"></i> In Progress</span>
                                                        @endif
                                                </td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    {{$getAnn->onEachSide(1)->links()}}

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-12">
            <div class="row g-4">
                <div class="col-sm-12 col-xl-12">
                    <div class="bg-white text-center rounded-3  p-4">

                        <div class="col-12">
                            <div class="bg-white rounded h-100 p-4">
                                <h5 class="text-dark">Announcement Board Completed</h5>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Title</th>
                                                <th scope="col">Scheduled Date</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getCompleted as $index => $announce)
                                            <tr data-bs-toggle="modal" data-bs-target="#completeModal{{ $announce->id }}" style="cursor:pointer;">
                                                <th class="border-bottom border-white" scope="row">{{ ($getCompleted->currentPage() - 1) * $getCompleted->perPage() + $index + 1 }}
                                                </th>
                                                <td class="border-bottom border-white">{{$announce->title}}</td>
                                                <td class="border-bottom border-white">{{ date('Y-m-d h:i A',
                                                    strtotime($announce->scheduled_date)) }}</td>
                                                <td class="border-bottom border-white">
                                                    @if($announce->scheduled_end < $currentDateTime)
                                                        <span class=" rounded-pill shadow p-2"><i class="far fa-dot-circle text-success"></i> Completed</span>
                                                        @endif </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{$getCompleted->onEachSide(1)->links()}}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="container-fluid pt-4 px-4">
                    <div class="row g-4">
                        <div class="col-sm-12 col-xl-12">
                            <div class="row g-4">
                                <div class=" pt-4 px-4 ">
                                    <div class="row g-4">
                                        <div class="col-sm-12 col-xl-12 rounded">
                                            <div class="bg-white rounded-2 p-4">
                                                <form action="" method="post" class="form-container">
                                                    @csrf
                                                    <div class=" row g-4">
                                                        <div class="modal-content text-end">


                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="col-sm-8 col-xl-8 modal-content">

                                                            <h2 class="text-start  p-2 text-dark border-bottom border-success">
                                                                Create
                                                                Announcement</h2>
                                                            <div class="form-group">
                                                                <label class="text-dark" for="title">Title</label>
                                                                <input type="text" name="title" class="form-control" id="title" placeholder="Enter Title">
                                                                <label class="text-dark" for="scheduled_date">Start</label>
                                                                <input type="datetime-local" name="scheduled_date" class="form-control" id="scheduled_date">

                                                                <label class="text-dark" for="scheduled_end">End</label>
                                                                <input type="datetime-local" name="scheduled_end" class="form-control" id="scheduled_end" min="" onchange="setMinEndTime()">
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="text-dark" for="description">Description</label>
                                                                <textarea class="form-control" name="description" id="description" cols="30" rows="10" placeholder="Enter Description"></textarea>
                                                                <button type="submit" class="btn btn-success btn-block save_btn mt-1">Send</button>
                                                            </div>
                                                        </div>
                                                        <div class="modal-content col-sm-4 col-xl-4 border-start border-light">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>Employees</th>
                                                                        <!-- Add more table headers if needed -->
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($users as $user)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="">
                                                                                <img class="rounded-circle me-lg-2" src="{{ asset('public/accountprofile/' . $user->profile_pic) }}" alt="" style="width: 40px; height: 40px;">
                                                                                <input type="checkbox" name="selected_users[]" value="{{ $user->id}}" class="form-check-input" style="display: none;">
                                                                            </div>
                                                                        </td>
                                                                        <td>{{ $user->lastname }}, {{ $user->name }} {{ $user->middlename }} @if($user->suffix == 'N/A')  @else {{$user->suffix}}@endif</td>
                                                                        <!-- Add more table cells for other user attributes if needed -->
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                        </div>

                                                        @include('layouts._message')
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

@foreach($getAnn as $announce)
<!-- Modal -->
<div class="modal fade" id="dataModal{{ $announce->id }}" tabindex="-1" aria-labelledby="dataModalLabel{{ $announce->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="dataModalLabel{{ $announce->id }}">
                    {{ $announce->title }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $announce->description }}
            </div>
            <div class="modal-footer">
                @if(Auth::user()->user_type == 0)
                <a href="{{ url('SuperAdmin/Read/'.$announce->id) }}" class="btn btn-success">Ok!</a>
                @elseif(Auth::user()->user_type == 1)
                <a href="{{ url('Admin/Read/'.$announce->id) }}" class="btn btn-success">Ok!</a>
                @elseif(Auth::user()->user_type == 2)
                <a href="{{ url('Employee/Read/'.$announce->id) }}" class="btn btn-success">Ok!</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach


@foreach($getCompleted as $announce)
<!-- Modal -->
<div class="modal fade" id="completeModal{{ $announce->id }}" tabindex="-1" aria-labelledby="completeModalLabel{{ $announce->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="completeModalLabel{{ $announce->id }}">
                    {{ $announce->title }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $announce->description }}
            </div>
            <div class="modal-footer">
                @if(Auth::user()->user_type == 0)
                <a href="{{ url('SuperAdmin/Read/'.$announce->id) }}" class="btn btn-success">Ok!</a>
                @elseif(Auth::user()->user_type == 1)
                <a href="{{ url('Admin/Read/'.$announce->id) }}" class="btn btn-success">Ok!</a>
                @elseif(Auth::user()->user_type == 2)
                <a href="{{ url('Employee/Read/'.$announce->id) }}" class="btn btn-success">Ok!</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach



@endsection

@push('javascript')
<script>
    $(document).ready(function() {
        var pusher = new Pusher('686df23863c2ae8a4b8', {
            cluster: 'clust'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            let pending = parseInt($('#' + data.from).html());
            if (!isNaN(pending)) {
                $('#' + data.from).html(data.pending);
            }
        });
    });
</script>
@endpush