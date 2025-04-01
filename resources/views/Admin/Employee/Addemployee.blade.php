@extends('Layouts.App')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="row g-4">
                @include('Layouts.Message')
                <div class="col-sm-12 col-xl-12">
                    <div>
                        <h2 class="text-dark text-start border-bottom border-success">Add New Employee</h2>
                    </div>
                    <div class="bg-white text-center p-4">
                        <div class="user-head">
                            <div class="d-flex justify-content-between border-bottom  ">
                                <a>Admin Controller</a>
                            </div>

                            <form method="post" action="">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-sm-6 col-xl-6">
                                        <div class="fields">
                                            <div class="input-field">
                                                <label>First Name</label>
                                                <input type="text" placeholder="Enter First Name" class="form-control" name="name" value="" required>
                                                @if($errors->has('name'))
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                            <div class="input-field">
                                                <label>Middle Name</label>
                                                <input type="text" placeholder="Enter Middle Name" class="form-control" name="middlename" value="" >
                                                @if($errors->has('middlename'))
                                                <span class="text-danger">{{ $errors->first('middlename') }}</span>
                                                @endif
                                            </div>
                                            <div class="input-field">
                                                <label>Last Name</label>
                                                <input type="text" placeholder="Enter Last Name" class="form-control" name="lastname" value="" required>
                                                @if($errors->has('lastname'))
                                                <span class="text-danger">{{ $errors->first('lastname') }}</span>
                                                @endif
                                            </div>
                                            <div class="input-field">
                                                <label for="suffix">Suffix</label>
                                                <select id="suffix" class="form-control" name="suffix">
                                                    <option selected disabled>--Select Suffix--</option>
                                                    <option value="Jr.">Jr.</option>
                                                    <option value="Sr.">Sr.</option>
                                                    <option value="I">I</option>
                                                    <option value="II">II</option>
                                                    <option value="III">II</option>
                                                    <option value="N/A">N/A</option>
                                                </select>
                                                @if($errors->has('suffix'))
                                                <span class="text-danger">{{ $errors->first('suffix') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xl-6">
                                        <div class="fields">
                                            <div class="input-field">
                                                <label for="sex">Sex</label>
                                                <select id="sex" class="form-control" name="sex">
                                                    <option selected disabled>--Select Sex--</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    @if($errors->has('sex'))
                                                    <span class="text-danger">{{ $errors->first('sex') }}</span>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="input-field">
                                                <label>Birth Date</label>
                                                <input type="date" placeholder="Enter Birth Date" class="form-control" name="birth_date" id="birth_date" value="" required>
                                                @if($errors->has('birth_date'))
                                                <span class="text-danger">{{ $errors->first('birth_date') }}</span>
                                                @endif
                                            </div>
                                            <div class="input-field">
                                                <label>Age</label>
                                                <input type="number" placeholder="Enter Age" class="form-control" name="age" id="age" value="" required>
                                                @if($errors->has('age'))
                                                <span class="text-danger">{{ $errors->first('age') }}</span>
                                                @endif
                                            </div>
                                            <div class="input-field">
                                                <label>Phone Number</label>
                                                <input type="number" class="form-control" name="phonenumber" pattern="(\+63\s?|0)(\d{3}\s?\d{3}\s?\d{4}|\d{4}\s?\d{3}\s?\d{4})" placeholder="e.g., +63 123 456 7890 or 0912 345 6789" value="" required>
                                                @if($errors->has('phonenumber'))
                                                <span class="text-danger">{{ $errors->first('phonenumber') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xl-12">
                                        <div class="fields">
                                            <div class="hidden input-field">
                                                <label>Role</label>
                                                <input class="form-control" name="user_type" value="2" required>
                                                @if($errors->has('user_type'))
                                                <span class="text-danger">{{ $errors->first('user_type') }}</span>
                                                @endif
                                            </div>

                                            <select id="contract" class="form-control" name="contract" required>
                                                <option selected disabled>--Select Contract--</option>
                                                <option value="1">Regular</option>
                                                <option value="2">Casual</option>
                                                <option value="3">Contractual</option>
                                                <option value="4">Job Order</option>
                                                <option value="5">Seasonal</option>
                                                @if ($errors->has('contract'))
                                                <span
                                                    class="text-danger">{{ $errors->first('contract') }}</span>
                                                @endif
                                            </select>

                                            <div class="input-field">
                                                <label for="department">Department</label>
                                                <select id="department" name="department" class="form-control">
                                                    <option value="">Select Department</option>
                                                    @foreach($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('department'))
                                                <span class="text-danger">{{ $errors->first('department') }}</span>
                                                @endif
                                            </div>

                                            <div class="input-field">
                                                <label for="position">Position</label>
                                                <select id="position" name="position" class="form-control">
                                                    <option value="">Select Position</option>
                                                </select>
                                                @if($errors->has('position'))
                                                <span class="text-danger">{{ $errors->first('position') }}</span>
                                                @endif
                                            </div>



                                            <div class="input-field">
                                                <label>Email</label>
                                                <input type="email" placeholder="Enter Email" class="form-control" name="email" value="" required>
                                                @if($errors->has('email'))
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                                @endif
                                            </div>
                                            <div class="hidden input-field">
                                                <label>Password</label>
                                                <input type="password" value="12345" placeholder="Enter Password" class="form-control" name="password" value="" required>
                                                @if($errors->has('password'))
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                                @endif
                                            </div>

                                            <div class="input-field">
                                                <label>End of Contract</label>
                                                <input type="date" class="form-control" name="end_of_contract" required>
                                                @if($errors->has('birth_date'))
                                                <span class="text-danger">{{ $errors->first('birth_date') }}</span>
                                                @endif
                                            </div>


                                            <div class="input-field">
                                                <label>Daily Rate</label>
                                                <input type="numeric" class="form-control" name="daily_rate" placeholder="e.g., 560" value="" required>
                                                @if($errors->has('daily_rate'))
                                                <span class="text-danger">{{ $errors->first('daily_rate') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Submit</button>

                            </form>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#department').on('change', function() {
                var departmentId = $(this).val();
                if (departmentId) {
                    $.ajax({
                        url: '/Admin/positionsAdmin/' + departmentId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#position').empty();
                            $('#position').append('<option value="">Select Position</option>');
                            $.each(data, function(key, value) {
                                $('#position').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ' + status + error);
                        }
                    });
                } else {
                    $('#position').empty();
                    $('#position').append('<option value="">Select Position</option>');
                }
            });
        });
    </script>


    @endsection