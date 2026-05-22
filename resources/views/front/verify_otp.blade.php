@extends('layout.index')

@section('home')
    <style>
        *,
        *:before,
        *:after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #080710;
        }

        .background {
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
        }

        .background .shape {
            height: 200px;
            width: 200px;
            position: absolute;
            border-radius: 50%;
        }

        .shape:first-child {
            background: linear-gradient(#1845ad,
                    #23a2f6);
            left: -80px;
            top: -80px;
        }

        .shape:last-child {
            background: linear-gradient(to right,
                    #ff512f,
                    #f09819);
            right: -30px;
            bottom: -80px;
        }

        form {
            height: 450px;
            width: 400px;
            background-color: rgba(255, 255, 255, 0.13);
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
            padding: 50px 35px;
        }

        form * {
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }

        form h3 {
            font-size: 28px;
            font-weight: 500;
            line-height: 42px;
            text-align: center;
        }

        p {
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
            color: #e5e5e5;
        }

        label {
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
        }

        input {
            display: block;
            height: 50px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.07);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 24px;
            font-weight: 600;
            text-align: center;
            letter-spacing: 10px;
        }

        ::placeholder {
            color: #e5e5e5;
            letter-spacing: normal;
            font-size: 14px;
        }

        button {
            margin-top: 40px;
            width: 100%;
            background-color: #ffffff;
            color: #080710;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }

        .error-msg {
            color: #ff512f;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
    </style>

    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form action="{{ route('verifyOtpStore') }}" method="POST" autocomplete="off">
        @csrf
        <h3>OTP Verification</h3>
        <p>Please enter the 6-digit code sent to your email.</p>

        @if ($errors->has('otp'))
            <div class="error-msg">
                {{ $errors->first('otp') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="error-msg">
                {{ session('error') }}
            </div>
        @endif

        <input type="text" placeholder="******" name="otp" id="otp" maxlength="6" required autofocus>

        <button type="submit">Verify & Login</button>
    </form>
@endsection
