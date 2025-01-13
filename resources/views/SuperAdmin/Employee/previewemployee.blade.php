<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview- {{ $getId->name }} {{ $getId->lastname }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<style>
    body {
        width: 210mm;
        height: 297mm;
        margin: 0 auto;
        /* Optional: Center the content on the page */
    }

    /* Additional styles for your content go here */
</style>
@php
    $redirectUrl = '';
    if (Auth::user()->user_type == '0') {
        $redirectUrl = url('/SuperAdmin/Employee');
    } elseif (Auth::user()->user_type == '1') {
        $redirectUrl = url('/Admin/Employee');
    }
@endphp

<body class="bg-white" onclick="window.location='{{ $redirectUrl }}'" style="cursor: pointer;">
    <container class="col-12">
        <div class="row g-4">
            <div class="col-sm-4 col-xl-4 text-center d-flex justify-content-around align-items-center">
                <img src="{{ asset('img/LOGOSULOP.png') }}" alt="Super Admin" width="350px">
            </div>
            <div class="col-sm-8 col-xl-8 ">
                <div class="mt-5 bg-success " style="height:50%; width:100%">
                    <p class="mt-2 d-flex justify-content-center align-items-center">
                    <h3 class="mt-5 text-center d-flex justify-content-around align-items-center text-white">Employee
                        Information
                        Sheet</h3>
                    </p>
                </div>
                <p class="mt-1 text-end text-dark">{{ \Carbon\Carbon::parse(now())->format('Y, F j') }}
                </p>
            </div>

            <div class=" bg-success " style="height:50%; width:100%">
                <p class=" d-flex justify-content-center align-items-center">
                <h3 class=" text-start  text-white">Personal Information:</h3>
                </p>
            </div>
            <h3 class="text-dark">{{ $getId->lastname }}, {{ $getId->name }} {{ $getId->middlename }} @if($getId->suffix == 'N/A')  @else {{$getId->suffix}}@endif</h3>

            <div class="col-sm-4 col-xl-4 text-start text-dark ">
                <p>Email:</p>
                <p>{{ $getId->email }}</p>
            </div>
            <div class="col-sm-8 col-xl-8 text-dark">
                <p>Cell Phone:</p>
                <p>{{ $getId->phonenumber }}</p>
            </div>
            <div class="col-sm-4 col-xl-4 text-start text-dark ">
                <p>Address:</p>
                <p>{{ $getId->fulladdress }}</p>
            </div>
            <div class="col-sm-8 col-xl-8 text-dark">
                <p>Birth Date:</p>
                <p>
                    {{ \Carbon\Carbon::parse($getId->birth_date )->format('Y, F j') }}
                </p>
            </div>
            <div class="col-sm-12 col-xl-12 text-dark">
                <p>Marital Status:</p>
                <p>{{ $getId->civil_status }}</p>
            </div>
            <div class=" bg-success " style="height:50%; width:100%">
                <p class=" d-flex justify-content-center align-items-center">
                <h3 class=" text-start  text-white">Job Information:</h3>
                </p>
            </div>
            <div class="col-sm-12 col-xl-12 border-bottom border-info">
                <div class="row g-4">

                    <div class="col-sm-2 col-xl-2 text-dark">
                        <p>Employee ID:</p>
                        <p>{{ $getId->custom_id }}</p>
                    </div>
                    <div class="col-sm-2 col-xl-2 text-start text-dark ">
                        <p>Start Date:</p>
                        <p>
                            {{ \Carbon\Carbon::parse($getId->date_of_assumption )->format('Y, F j') }}
                        </p>
                    </div>
                    <div class="col-sm-2 col-xl-2 text-start text-dark ">
                        <p>Contract:</p>
                        <p>
                            @if ($getId->contract == 1)
                                Regular
                            @elseif($getId->contract == 2)
                                Casual
                            @elseif($getId->contract == 3)
                                Contractual
                            @elseif($getId->contract == 4)
                                Job Order
                            @elseif($getId->contract == 5)
                                Seasonal
                            @endif
                        </p>
                    </div>
                    <div class="col-sm-2 col-xl-2 text-start text-dark ">
                        <p>End of Contract:</p>
                        <p>{{ \Carbon\Carbon::parse($getId->end_of_contract)->format('Y, F j') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-12 border-bottom border-info">
                <div class="row g-4">
                    <div class="col-sm-4 col-xl-4 text-start text-dark ">
                        <p>Department:</p>
                        <p>
                            @foreach ($depart as $data)
                                @if ($getId->department == $data->id)
                                    {{ $data->name }}
                                @endif
                            @endforeach
                        </p>
                    </div>


                    <div class="col-sm-4 col-xl-4 text-start text-dark ">
                        <p>Position:</p>
                        <p>
                            @foreach ($pos as $data)
                            @if ($getId->position == $data->id)
                                {{ $data->name }}
                            @endif
                        @endforeach
                        </p>
                    </div>

                    <div class="col-sm-4 col-xl-4 text-start text-dark ">
                        <p>Daily Rate:</p>
                        <p>{{ $getId->daily_rate }}</p>
                    </div>
                </div>
            </div>
            <div class=" bg-success " style="height:50%; width:100%">
                <p class=" d-flex justify-content-center align-items-center">
                <h3 class=" text-start  text-white">Emergency Contact Information:</h3>
                </p>
            </div>
            <h3 class="text-dark">{{ $getId->emergency_fullname }}</h3>
            <div class="col-sm-12 col-xl-12">
                <div class="row g-4">
                    <div class="col-sm-4 col-xl-4 text-start text-dark ">
                        <p>Address:</p>
                        <p>{{ $getId->emergency_fulladdress }}</p>
                    </div>
                    <div class="col-sm-4 col-xl-4 text-dark">
                        <p>Phone Number:</p>
                        <p>{{ $getId->emergency_phonenumber }}</p>
                    </div>
                    <div class="col-sm-4 col-xl-4 text-start text-dark ">
                        <p>Relationship:</p>
                        <p>{{ $getId->emergency_relationship }}</p>
                    </div>
                </div>
            </div>
        </div>
    </container>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('lib/chart/chart.min.js') }}"></script>
<script src="{{ asset('lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('lib/tempusdominus/js/moment.min.js') }}"></script>
<script src="{{ asset('lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
<script src="{{ asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

<!-- Template Javascript -->
<script src="{{ asset('js/main.js') }}"></script>

</html>
