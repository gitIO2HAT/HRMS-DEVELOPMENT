<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jhon Mark Egos - Portfolio</title>
    <link rel="icon" href="picture.png" type="image/png">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="style.css">
</head>
<style>
    body {
        margin-left: 10px;
        margin-right: 10px;
        padding-top: 100px;
        background-image: url("img/testingBG.png");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        /* Ensures background stays fixed while content scrolls */
    }

    .header-custom {
        height: 100px;
        font-size: 30px;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background-color: #1c9445;
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgb(170, 168, 168);
        border-radius: 50%;
        width: 50px;
        height: 50px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-size: 100%, 100%;
    }

    .custom-text {
        text-decoration: none;
        color: rgb(7, 7, 7);

    }

    .custom-text:hover {
        color: rgb(25, 206, 70);

    }

    .name {
        font-size: 100px;
        font-family: 'Poppins', sans-serif;
    }

    .aboutme {
        font-size: 25px;
        text-align: justify;
        font-family: 'Montserrat Classic', sans-serif;
    }

    .hobbiesInterest {
        font-size: 30px;
        font-family: 'Poppins', sans-serif;
    }

    .list {
        font-size: 25px;
        font-family: 'Montserrat Classic', sans-serif;
    }

    .contact {
        color: #542c5c;
        font-size: 25px;
        font-family: 'Poppins', sans-serif;
        text-decoration: none;
    }

    .expercience {
        font-size: 25px;
        font-family: 'Montserrat Classic', sans-serif;
        padding-left: 100px;
        padding-right: 100px;
        margin-top: 400px;

    }

    .details {
        font-size: 25px;
        text-align: justify;
        font-family: 'Montserrat Classic', sans-serif;
    }

    .info a {
        text-decoration: none;


    }

    .info {
        margin-top: 180px;
        margin-bottom: 180px;
    }

    .technician a {
        text-decoration: none;
    }
</style>

<body id="Home" class=".container-xxl">

    <header class="header-custom d-flex justify-content-between align-items-center px-5">
        <div class="d-flex justify-content-between text-start">
            <img src="img/user3.png" class="p-2" width="100px" height="100px">
            <div class="p-2">
                <h6>Republic of the Philippines</h6>
                <h6><u>Province of Davao Del Sur - Region XI</u></h6>
                <h5>MUNICIPALITY OF SULOP</h5>
            </div>
        </div>
        <div class="d-flex btn btn-white custom-text border-dark justify-content-center align-items-center fs-5">
            <a class="custom-text" href="{{ url('/LoginUser') }}">Log In</a>
        </div>
    </header>


    <div class=" row mt-5 ">

        <div id="Aboutme" class="row col-12 col-sm-12 col-xl-8 text-dark text-center">

            <table class="table text-center ms-5 mt-5 border border-dark rounded-3" style="background-color: transparent; font-size: 40px;">
                <tbody>
                    <tr>
                        <th scope="row" colspan="3" class="text-start ps-5" style="border: none; background-color: transparent; color: black;">
                            <div id="current-date2-superadmin"></div>
                        </th>
                    </tr>
                    <tr>
                        <th scope="row" class="text-start" style="border: none; background-color: transparent; color: black;"></th>
                        <td class="text-end" style="border: none; background-color: transparent; color: black;">Name:</td>
                        <th scope="row" class="text-center" style="border: none; background-color: transparent; color: black;">{{ $firstRecord->user->name ?? 'NO DATA'}}</th>
                        <td style="border: none; background-color: transparent; color: black;"></td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-start" style="border: none; background-color: transparent; color: black;"></th>
                        <td class="text-end" style="border: none; background-color: transparent; color: black;">Department:</td>
                        <th scope="row" class="text-center" style="border: none; background-color: transparent; color: black;">{{ $departments->name ?? 'NO DATA'}}</th>
                        <td style="border: none; background-color: transparent; color: black;"></td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-start" style="border: none; background-color: transparent; color: black;"></th>
                        <td class="text-end" style="border: none; background-color: transparent; color: black;">Position:</td>
                        <th scope="row" class="text-center" style="border: none; background-color: transparent; color: black;">{{ $positions->name ?? 'NO DATA'}}</th>
                        <td style="border: none; background-color: transparent; color: black;"></td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-start" style="border: none; background-color: transparent; color: black;"></th>
                        <td class="text-end" style="border: none; background-color: transparent; color: black;">Time-In AM:</td>
                        <th scope="row" class="text-center" style="border: none; background-color: transparent; color: black;">{{ $firstRecord->punch_in_am_first ?? 'NO DATA'}}</th>
                        <td style="border: none; background-color: transparent; color: black;"></td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-start" style="border: none; background-color: transparent; color: black;"></th>
                        <td class="text-end" style="border: none; background-color: transparent; color: black;">Time-Out AM:</td>
                        <th scope="row" class="text-center" style="border: none; background-color: transparent; color: black;">{{ $firstRecord->punch_in_am_second ?? 'NO DATA'}}</th>
                        <td style="border: none; background-color: transparent; color: black;"></td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-start" style="border: none; background-color: transparent; color: black;"></th>
                        <td class="text-end" style="border: none; background-color: transparent; color: black;">Time-In PM:</td>
                        <th scope="row" class="text-center" style="border: none; background-color: transparent; color: black;">{{ $firstRecord->punch_in_pm_first ?? 'NO DATA'}}</th>
                        <td style="border: none; background-color: transparent; color: black;"></td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-start" style="border: none; background-color: transparent; color: black;"></th>
                        <td class="text-end" style="border: none; background-color: transparent; color: black;">Time-Out PM:</td>
                        <th scope="row" class="text-center" style="border: none; background-color: transparent; color: black;">{{ $firstRecord->punch_in_pm_first ?? 'NO DATA'}}</th>
                        <td style="border: none; background-color: transparent; color: black;"></td>
                    </tr>
                </tbody>
            </table>


        </div>

        <div class="col-12 col-sm-12 col-xl-4 text-center align-content-center">
            <img 
                src="{{ $firstRecord && $firstRecord->user && $firstRecord->user->profile_pic 
                    ? asset('public/accountprofile/' . $firstRecord->user->profile_pic) 
                    : asset('public/accountprofile/default.png') }}" 
                alt="Profile Picture" 
                class="shadow img-fluid rounded-circle mb-3"
                style="width: 400px; height: 400px;">
        </div>
        
    </div>

    <script>
        function updateTime() {
            const today1 = new Date();
            const day1 = today1.getDate();
            const daySuffix1 = (day1) => {
                if (day1 > 3 && day1 < 21) return 'th';
                switch (day1 % 10) {
                    case 1:
                        return "st";
                    case 2:
                        return "nd";
                    case 3:
                        return "rd";
                    default:
                        return "th";
                }
            };

            const daysOfWeek1 = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            const months1 = [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];

            const dayOfWeek1 = daysOfWeek1[today1.getDay()];
            const month1 = months1[today1.getMonth()];
            const year1 = today1.getFullYear();

            let hours1 = today1.getHours();
            const minutes1 = today1.getMinutes().toString().padStart(2, '0');
            const seconds1 = today1.getSeconds().toString().padStart(2, '0');
            const ampm1 = hours1 >= 12 ? ' PM' : ' AM';
            hours1 = hours1 % 12;
            hours1 = hours1 ? hours1 : 12; // the hour '0' should be '12'
            const timeString1 = `${hours1}:${minutes1}:${seconds1}${ampm1}`;

            const dateString1 =
                `<span class="date1">${dayOfWeek1}, ${day1}${daySuffix1(day1)} ${month1} ${year1} ${timeString1}</span>`;
            document.getElementById('current-date2-superadmin').innerHTML = dateString1;
        }

        // Update the time every second
        setInterval(updateTime, 1000);

        // Initial call to display the time immediately on page load
        updateTime();
    </script>






    <!-- Example of Font Awesome icons -->

    </div>
    <!-- Bootstrap 5 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

    <script src="script.js"></script>
</body>

</html>