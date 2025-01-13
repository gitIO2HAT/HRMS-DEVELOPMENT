@extends('layouts.app')

@section('content')

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="row g-4">
                <div class=" pt-4 px-4 ">
                    @include('layouts._message')
                    <div class="row g-4">
                        <div class="col-12 rounded">
                            <div class="bg-white rounded-3  h-100 p-4">
                                <h2 class="text-dark text-center">DEPARTMENT ARCHIVED</h2>
                                <div class="d-flex align-items-center">
                                    <div class="col-sm-10 ms-5 ">
                                        <form action="{{url('/SuperAdmin/Department/DepartmentArchived')}}" class="me-1">
                                            @csrf
                                            <input type="search" id="search" class="form-control bg-transparent" name="search" placeholder="Search Here" value="{{ request('search') }}">
                                            <button style="display: none;" class="btn btn-success m-1" type="submit">Search</button>
                                            <button style="display: none;" type="hidden" class="btn btn-success m-1" onclick="clearSearch()">Clear</button>
                                        </form>
                                    </div>
                                    <div class="col-sm-2 ms-1 ">
                                        @if(Auth::user()->user_type == 0)
                                        <a href="{{url('/SuperAdmin/Department')}}" class="m-1 btn btn-white "><i class="fas fa-arrow-left" style="color: #000000;"> Back</i></a>
                                        @elseif(Auth::user()->user_type == 1)
                                        <a href="{{url('/Admin/Department')}}" class="m-1 btn btn-white "><i class="fas fa-arrow-left" style="color: #000000;"></i> Back</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 rounded">
                            <div class="bg-white rounded-3  h-100 p-4">
                                <table class="table table-striped table-responsive table-hover text-start">
                                    <thead>
                                        <tr>
                                            <th class="">#</th>
                                            <th class="">Department</th>
                                            <th class="">Year Published</th>
                                            <th class="">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($departments as $index => $list)
                                        <tr>
                                            <td class="">{{ ($departments->currentPage() - 1) * $departments->perPage() + $index + 1 }}</td>
                                            <td class="">
                                                {{ $list->name }}
                                            </td>
                                            <td class="">
                                                {{ \Carbon\Carbon::parse($list->created_at)->format('Y') }}
                                            </td>
                                            <td class="">
                                                @if(Auth::user()->user_type == 0)
                                                <a href="{{ url('/SuperAdmin/Department/DeletedRestored/'.$list->id) }}"> <i class="fas fa-trash-restore" style="color: #63E6BE;"></i></a>
                                                @elseif(Auth::user()->user_type == 1)

                                                <a href="{{ url('/Admin/Department/DeletedRestored/'.$list->id) }}"> <i class="fas fa-trash-restore" style="color: #63E6BE;"></i></a>
                                                @endif
                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $departments->appends(['page_department' => request('page_department')])->links() }}
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



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#department').on('change', function() {
            var departmentId = $(this).val();
            if (departmentId) {
                $.ajax({
                    url: '/Admin/positions/' + departmentId,
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