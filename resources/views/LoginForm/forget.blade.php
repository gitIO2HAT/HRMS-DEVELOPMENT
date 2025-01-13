<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .background {
            width: 100%;
            height: 100vh;
            background-image: url("img/SULOP.png");
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
            height: 450px;
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
            <img src="{{ asset('img/SULOP.png') }}" alt="logo" height="450px">
        </div>

        <div class="form">
            <div class="form-login">
                <form method="post" action="{{ url('/ForgetPassword/Reset') }}">
                    @csrf
                    <div class="text-center">
                        <img src="{{ asset('img/HUMAN.png') }}" alt="logo" height="200px" weight="200px">
                    </div>
                    <h4>Forgot Password</h4>
                    @if (Session::has('status'))
                        <div class="alert alert-success">{{ Session::get('status') }}</div>
                    @endif

                    @if (Session::has('error'))
                        <div class="alert alert-danger">{{ Session::get('error') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @include('layouts._message')

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-envelope'></i></span>
                        <input name="email" type="email" required>
                        <label>Email</label>
                    </div>
                    <div class="login">
                        <button type="submit" class="btn">Send Password Reset Link</button>
                    </div>
                    <div class="login" style=" margin-top:10px;">
                        <a class="btn" href="{{ url('/LoginUser') }}">Login</a>
                    </div>

                </form>

            </div>
        </div>

</body>

</html>
