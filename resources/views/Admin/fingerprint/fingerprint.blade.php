@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
@include('layouts._message')
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="row g-4">
                <div class="container-fluid pt-4 px-4">
                    <div class="row g-4">
                    
                        <div class="col-sm-12 col-xl-12">

                            <div class="row g-4">
                                <div class=" pt-4 px-4 ">
                               
                                    <div class="row g-4">
                                  
                                        <div class="col-sm-12 col-xl-12 rounded">
                                            <div class=" bg-white rounded-3  h-100 p-4">
                                            <div class="col-12 rounded">
                            <div class="bg-white rounded-3  h-100 mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="col-sm-10">
                                        <form action="{{url('/Admin/Fingerprint/')}}" class="me-1">
                                            @csrf
                                            <input type="search" id="search" class="form-control bg-transparent" name="search" placeholder="Search Here" value="{{ request('search') }}">
                                            <button style="display: none;" class="btn btn-success m-1" type="submit">Search</button>
                                            <button style="display: none;" type="hidden" class="btn btn-success m-1" onclick="clearSearch()">Clear</button>
                                        </form>
                                    </div>
                                    <div class="col-sm-2 ">
                                     
                                    </div>
                                </div>
                            </div>
                        </div>
                                            <table
                                                    class="table table-striped table-hover table-responsive table-bordered text-start align-middle ">
                                                    <thead class="text-dark">
                                                        <tr>
                                                            <th class="bg-head text-center" scope="col" colspan="4">Fingerprint Data</th>

                                                        </tr>
                                                        <tr class="bg-title">
                                                            <th class="" scope="col" >#</th>
                                                            <th class="" scope="col">Employee ID</th>
                                                            <th class="" scope="col">Fingerprint</th>
                                                            <th scope="col" >Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="">
                                                      @foreach ($FingerprintTable as $index => $Fingerprint)
                                                      
                                                        <tr>
                                                            <th scope="row">{{ ($FingerprintTable->currentPage() - 1) * $FingerprintTable->perPage() + $index + 1 }}</th>
                                                            <td class="text-capitalize"><img class="rounded-circle me-lg-2"
                                                                    src="{{ asset('public/accountprofile/' . $Fingerprint->user->profile_pic) }}"
                                                                    alt=""
                                                                    style="width: 40px; height: 40px;">
                                                                    {{ $Fingerprint->user->lastname }}, {{ $Fingerprint->user->name }} {{ $Fingerprint->user->middlename }} @if($Fingerprint->user->suffix == 'N/A') @else {{$Fingerprint->user->suffix}}@endif
                                                            </td>
                                                            <td>
                                                            @if($Fingerprint->template != null)
                                                            Registered
                                                            @else
                                                            Not  Registered
                                                            @endif
                                                            </td>
                                                            <td class="text-capitalize text-center">
                                                            @if(Auth::user()->user_type === 0 )
                                                            @if($Fingerprint->status === 'active')
                                                             <a href="{{ url('SuperAdmin/Fingerprint/Active/' . $Fingerprint->id) }}" class="btn btn-success" >Active</a> <a href="{{ url('SuperAdmin/Fingerprint/NotActive/' . $Fingerprint->id) }}" class="btn btn-dark ">Not Active</a> 
                                                             @else
                                                             <a href="{{ url('SuperAdmin/Fingerprint/Active/' . $Fingerprint->id) }}" class="btn btn-dark">Active</a> <a href="{{ url('SuperAdmin/Fingerprint/NotActive/' . $Fingerprint->id) }}" class="btn btn-primary">Not Active</a> 
                                                             @endif
                                                             @else
                                                             @if($Fingerprint->status === 'active')
                                                             <a href="{{ url('Admin/Fingerprint/Active/' . $Fingerprint->id) }}" class="btn btn-success" >Active</a> <a href="{{ url('Admin/Fingerprint/NotActive/' . $Fingerprint->id) }}" class="btn btn-dark ">Not Active</a> 
                                                             @else
                                                             <a href="{{ url('Admin/Fingerprint/Active/' . $Fingerprint->id) }}" class="btn btn-dark">Active</a> <a href="{{ url('Admin/Fingerprint/NotActive/' . $Fingerprint->id) }}" class="btn btn-primary">Not Active</a> 
                                                             @endif
                                                             @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                      

                                                    </tbody>
                                                </table>
                                                
                                                {{ $FingerprintTable->onEachSide(1)->links() }}
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
                                      
                                       


                                        @endsection