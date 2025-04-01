@extends('Layouts.App')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="row g-4">
                @include('Layouts.Message')
                <div class="col-sm-12 col-xl-12">
                    <div>
                        <h2 class="text-dark text-start border-bottom border-success">Edit {{$getId->lastname}}, {{$getId->name}} {{$getId->middlename}} @if($getId->suffix == 'N/A')  @else {{$getId->suffix}}@endif
                            
                        </h2>
                    </div>
                    <div class="bg-white text-center p-4">
                        <div class="user-head">
                            <div class="d-flex justify-content-between border-bottom  ">
                                <a>Admin Controller</a>
                            </div>
                            <form method="post" action="" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">

                                    <div class="col-sm-12 col-xl-12">
                                        <div class="fields">


                                            <div class="input-field">
                                                <label for="department">Department</label>
                                                <select id="department" name="department" class="form-control">
                                                    <option value="" disabled selected>--Select Department--</option>
                                                    @foreach($departments as $department)
                                                    <option value="{{ $department->id }}" @if($getId->department == $department->id) selected @endif>{{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('department'))
                                                <span class="text-danger">{{ $errors->first('department') }}</span>
                                                @endif
                                            </div>

                                            <div class="input-field">
                                                <label for="position">Position</label>
                                                <select id="position" name="position" class="form-control">
                                                    <option value="">@foreach ($pos as $data)
                                                        @if ($getId->position == $data->id)
                                                        {{ $data->name }}
                                                        @endif
                                                        @endforeach
                                                    </option>
                                                </select>
                                                @if($errors->has('position'))
                                                <span class="text-danger">{{ $errors->first('position') }}</span>
                                                @endif
                                            </div>

                                            <div class=" input-field">
                                                <label>Contract</label>
                                                <select id="contract" class="form-control" name="contract" required>
                                                    <option selected disabled>--Select Contract--</option>
                                                    <option value="1" @if($getId->contract == '1') selected @endif>Regular</option>
                                                    <option value="2" @if($getId->contract == '2') selected @endif>Casual</option>
                                                    <option value="3" @if($getId->contract== '3') selected @endif>Contractual</option>
                                                    <option value="4" @if($getId->contract== '4') selected @endif>Job Order</option>
                                                    <option value="5" @if($getId->contract == '5') selected @endif>Seasonal</option>
                                                    @if ($errors->has('contract'))
                                                    <span
                                                        class="text-danger">{{ $errors->first('contract') }}</span>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="input-field">
                                                <label>Date of Assumption</label>
                                                <input type="date" class="form-control" name="date_of_assumption" value="{{$getId->date_of_assumption}}" required>
                                                @if($errors->has('date_of_assumption'))
                                                <span class="text-danger">{{ $errors->first('date_of_assumption') }}</span>
                                                @endif
                                            </div>
                                            <div class="input-field">
                                                <label>End of Contract</label>
                                                <input type="date" class="form-control" name="end_of_contract"
                                                    value="{{$getId->end_of_contract}}">
                                                @if($errors->has('birth_date'))
                                                <span class="text-danger">{{ $errors->first('birth_date') }}</span>
                                                @endif
                                            </div>
                                            <div class="input-field">
                                                <label>Daily Rate</label>
                                                <input type="numeric" class="form-control" name="daily_rate"
                                                    placeholder="e.g., 560" value="{{$getId->daily_rate}}">
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