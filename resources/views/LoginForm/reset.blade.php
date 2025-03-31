<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

        }

        .background {
            width: 100%;
            height: 100vh;
            background-image: url("{{ asset('img/SULOP.png') }}");
            background-size: cover;
            background-position: center;
            filter: blur(20px);
        }

        .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 75%;
            height: 550px;
            background-image: url(img/SULOP.png);
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            margin-top: 20px;

        }

        .logo {
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
        }

        .form {
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
        }

        .form .form-login {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            background: transparent;
            backdrop-filter: blur(20px);
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            color: #e4e4e4;
        }

        .form-login h4 {
            font-size: 32px;
            text-align: center;
        }

        .form-login .input-box {
            position: relative;
            width: 340px;
            height: 50px;
            border-bottom: 2px solid #e4e4e4;
            margin: 30px 0;
        }

        .input-box input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            font-size: 16px;
            color: #e4e4e4;
            font-weight: 500;
            padding-right: 28px;
        }

        .input-box label {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            font-size: 15px;
            font-weight: 500;
            pointer-events: none;
            transition: .5s ease;
        }

        .input-box input:focus~label,
        .input-box input:valid~label {
            top: -5px;
        }

        .input-box .icon {
            position: absolute;
            top: 13px;
            right: 0;
            font-size: 16px;
        }

        .remember-forgot {
            font-size: 14px;
            font-weight: 500;
            margin: -15px 0 15px;
            display: flex;
            justify-content: space-between;
        }

        .remember-forgot label input {
            accent-color: #e4e4e4;
            margin-right: 3px;
        }

        .remember-forgot a {
            color: #e4e4e4;
            text-decoration: none;
        }

        .remember-forgot a:hover {
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            height: 45px;
            background-color: rgba(37, 116, 37, 0.699);
            border: none;
            outline: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            color: #e4e4e4;
            font-weight: 500;
            box-shadow: 0 0 10px rgba(0, 0, 0, 2);
        }

        .btn:hover {
            background-color: rgb(50, 158, 50);
        }
    </style>

</head>

<body>
    <div class="background"></div>
    <div class="container">
        <div class="logo">
            <img src="{{ asset('img/SULOP.png') }}" alt="logo" height="550px">
        </div>

        <div class="form">
            <div class="form-login">
                <form method="post" action="{{ url('/password/reset') }}">
                    @csrf

                    <div class="text-center">
                        <img src="{{ asset('img/HUMAN.png') }}" alt="logo" height="200px" weight="200px">
                    </div>
                    @if (Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif
                    @include('Layouts.Message')

                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-user'></i></span>
                        <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-lock-alt'></i></span>
                        <input id="password" type="password" name="password" required onkeyup="checkPasswordMatch();">
                        <label>Password</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-lock-alt'></i></span>
                        <input id="password-confirm" type="password" name="password_confirmation" required onkeyup="checkPasswordMatch();">
                        <label>Confirm Password</label>
                    </div>
                    <!-- Password match indicator -->
                    <div id="passwordMatchMessage" style="color:red; text-align:center; margin-top:10px;"></div>
                    <div class="remember-forgot">


                        <label for=""></label>
                        <a class="text-end" href="{{ url('/LoginUser') }}">Log In</a>
                    </div>
                    <div class="login">
                        <button type="submit" class="btn">Change Password</button>
                    </div>
                </form>

            </div>

        </div>

</body>
<script>
    function checkPasswordMatch() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("password-confirm").value;
        var message = document.getElementById("passwordMatchMessage");

        if (password != confirmPassword) {
            message.textContent = "Passwords do not match!";
        } else {
            message.textContent = "";
        }
    }
</script>
</html>





