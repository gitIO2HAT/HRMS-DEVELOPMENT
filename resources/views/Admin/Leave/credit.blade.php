@extends('layouts.app')

@section('content')
@include('layouts._message')



<div class="container-fluid pt-4 px-4">
    <div class="row g-4">


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

                <div class="table-responsive">
                    <table class="table text-start align-middle ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Employee Name</th>
                                <th scope="col">Sick Leave Balance</th>
                                <th scope="col">Vacation Balance</th>
                                <th scope="col">Special Previlege Balance</th>
                                <th scope="col">Add Credits</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                <td class="text-capitalize"><img class="rounded-circle me-lg-2"
                                        src="{{ asset('public/accountprofile/' .$user->profile_pic) }}"
                                        alt="" style="width: 40px; height: 40px;">
                                    {{$user->lastname}}, {{$user->name}} {{$user->middlename}} @if($user->suffix == 'N/A')  @else {{$user->suffix}}@endif
                                </td>
                                <td>{{$user->sick_leave}}</td>
                                <td>{{$user->vacation_leave}}</td>
                                <td>{{$user->special_previlege_leave}}</td>
                                <td> <a type="button" class="mx-2" data-bs-toggle="modal" data-bs-target="#editCreditsModal-{{ $user->id }}" data-leave-id="{{ $user->id }}">
                                        +
                                    </a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $users->appends(['search' => request('search')])->links() }}

                </div>
            </div>
        </div>
        @foreach($users as $user)
        <div class="modal fade" id="editCreditsModal-{{ $user->id }}" tabindex="-1" aria-labelledby="editCreditsModalLabel-{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCreditsModalLabel-{{ $user->id }}">Edit Credits</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="statusForm-{{ $user->id }}" method="POST" action="{{ url('/Admin/Credits/EditCredits/' . $user->id) }}">
                            @csrf
                            @method('PATCH')
                            <div>
                                <select id="contract" class="form-control" name="type" required>
                                    <option selected disabled>--Select Leave Balance--</option>
                                    <option value="sick_leave">Sick Leave Balance</option>
                                    <option value="vacation_leave">Vacation Leave Balance</option>
                                

                                    @if ($errors->has('type'))
                                    <span
                                        class="text-danger">{{ $errors->first('type') }}</span>
                                    @endif
                                </select>
                            </div>


                            <div class="d-flex justify-content-between">
                                <a href="#" class="button d-flex align-items-center p-2" onclick="increment()">+</a>
                                <input type="number" id="numberInput" name="numberInput" min="-100" max="100" step="0.001" value="0.000" class="form-control my-2">
                                <a href="#" class="button d-flex align-items-center p-2" onclick="decrement()">-</a>
                            </div>



                            <script>
                                // Function to increment the input value by 0.001
                                function increment() {
                                    var input = document.getElementById("numberInput");
                                    var value = parseFloat(input.value);
                                    if (value < 100) {
                                        input.value = (value + 0.001).toFixed(3); // Increment by 0.001 and round to 3 decimals
                                    }
                                }

                                // Function to decrement the input value by 0.001
                                function decrement() {
                                    var input = document.getElementById("numberInput");
                                    var value = parseFloat(input.value);
                                    if (value > -100) {
                                        input.value = (value - 0.001).toFixed(3); // Decrement by 0.001 and round to 3 decimals
                                    }
                                }
                            </script>
                            <div class="modal-footer"><button type="submit" class="btn btn-success">Update</button></div>


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

        <!-- JavaScript for handling the increment and decrement of various leave types -->

        @endsection