<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Defend X - Partner Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/images/img/favicon.png') }}">

</head>

<body>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* styles.css */
        body {
            background-color: #0b0f19;
            color: white;
        }

        .full-width-container {
            width: 100vw;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .left-section {
            flex: 1.5;
            position: relative;
            background-color: #0b0f19;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        /* Logo Styling */
        .logo-container {
            padding: 10px;
        }

        .logo-img {
            width: 180px;
            height: auto;
        }

        /* Right Section */
        .right-section {
            flex: 0.6;
            /* border: 1px solid white; */
            background-size: cover;
        }

        .right-section .login-container {

            color: white;
            position: relative;
            width: 100%;
            max-width: 365px;
            padding: 50px 35px;
            border-radius: 50px;
            background: #15405B;
            backdrop-filter: blur(0px);
            text-align: center;
            overflow: hidden;
            border: 2px solid #1F5E83;
        }


        .input-group {
            background: linear-gradient(90deg, #00011C, #001A37);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: white;
            border-radius: 0;
            font-size: 0.9rem;
        }

        .form-control {
            background: transparent;
            border: none;
            color: white;
            border-radius: 0;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control:focus {
            background: transparent;
            outline: none;
            box-shadow: none;
            color: white;
        }

        /* Wrapper for the Remember Me & Forgot Password Section */
        .remember-forgot {
            color: white;
        }

        /* Remember Me Styling */
        .remember-me {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            opacity: 0.45;
        }

        /* Forgot Password Styling */
        .forgot-password {
            font-size: 12px;
            opacity: 0.45;
            font-style: italic;
            color: white;
            text-decoration: none;
        }

        /* Optional: Hover effect for better UX */
        .forgot-password:hover {
            opacity: 0.5;
            text-decoration: underline;
        }

        .btn-info {
            background-color: #00aaff;
            border: none;
        }

        .btn-outline-info {
            border-color: #00aaff;
            color: #00aaff;
        }

        .btn-outline-info:hover {
            background-color: #00aaff;
            color: white;
        }

        /* Buttons */
        .login-btn {
            background: linear-gradient(90deg, #00011C, #001A37);
            border: none;
            color: white;
            font-weight: 500;
            border-radius: 10px;
            padding: 10px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
            backdrop-filter: blur(10px);
        }

        .login-btn:hover {
            background: linear-gradient(90deg, #000044, black);
            transform: scale(1.05);
        }

        /* Reduce width of social-login */
        .social-login {
            width: 80%;
            max-width: 310px;
        }

        /* Custom button styling */
        .custom-social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            color: #00C4FF;
            border-radius: 10px;
            padding: 5px 15px;
            text-align: center;
            position: relative;
            border: 2px solid #61c9f7b8;
        }

        /* Ensure icon stays on the left */
        .custom-social-btn i {
            position: absolute;
            left: 15px;
            font-size: 25px;
        }

        .custom-social-btn img.social-icon {
            position: absolute;
            left: 15px;
            width: 25px;
            /* Adjust size */
            height: 25px;
        }

        .custom-social-btn:hover {
            border: 1px solid #61C9F7;
        }


        /* Style the checkbox */
        input[type="checkbox"] {
            appearance: none;
            /* Remove default styling */
            width: 13px;
            height: 13px;
            background-color: #000000;
            /* border-radius: 3px; */
            position: relative;
            cursor: pointer;
            margin-right: 10px;
        }

        /* Checkbox tick when checked */
        input[type="checkbox"]:checked::before {
            content: "\2713";
            /* Checkmark */
            font-size: 14px;
            color: white;
            font-weight: bold;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        a {
            text-decoration: none;
        }

        /* Terms and Conditions Container */
        .terms-container {
            margin-top: 15px;
        }

        /* Terms and Conditions Text */
        .terms {
            font-size: 12px;
            color: lightgray;
        }

        /* Links inside Terms */
        .terms-link {
            color: #00C4FF;
            text-decoration: none;
        }

        .terms-link:hover {
            text-decoration: underline;
        }
    </style>
    <div class="full-width-container d-flex h-100vh">
        <!-- Left Section -->
        <div class="left-section d-flex flex-column justify-content-center align-items-center flex-grow-1 position-relative"
            style=" background: url('{{ asset('assets/images/auth/login-page-bg.jpg')}}') no-repeat center center; background-size: cover;">
            <div class="logo-container position-absolute top-0 start-0 m-3">
                <img src="{{ asset('assets/images/auth/Light-Logo-DefendX.png') }}" alt="Logo" class="logo-img"
                    role="img">
            </div>
            <img src="{{ asset('assets/images/auth/lighting-3.gif') }}" alt="Animated Background"
                class="w-100 h-100 object-fit-cover">
        </div>

        <!-- Right Section -->
        <div class="right-section d-flex flex-column justify-content-center align-items-center p-4" role="form"
            style="background: url('{{ asset('assets/images/auth/right-Artboard.jpg') }}')">
            <div class="login-container text-center">
                <div class="border-animation"></div>
                <h2 class="fw-bold mb-5" role="heading">PARTNER LOGIN</h2>

                <form action="{{ url('login') }}" method="POST">
                    @csrf
                    <!-- Email Input with Error Message -->
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-user" role="img"
                                aria-label="User Icon"></i></span>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email"
                            value="{{ old('email') }}" role="textbox">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Input with Error Message -->
                    <div class="input-group mb-3">
                        <span class="input-group-text">
                            <i class="fa fa-lock" role="img" aria-label="Lock Icon"></i>
                        </span>
                        <input type="password" name="password" id="password-input"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Enter your password" role="textbox">
                        <span class="input-group-text" id="toggle-password" style="cursor: pointer;">
                            <i class="fa fa-eye" id="eye-icon"></i>
                        </span>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <div class="d-flex justify-content-between mb-3 remember-forgot">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" value="1" role="checkbox"> Remember me
                        </label>
                        <a href="#" role="link" class="forgot-password">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn login-btn w-100 mb-3" role="button">LOGIN</button>
                </form>
            </div>

            <div class="social-login mt-4">
                <button id="microsoft-login-btn" class="btn custom-social-btn mb-2" role="button">
                    <i class="fa-brands fa-windows" role="img" aria-label="Microsoft Icon"></i>
                    <span>Login with Microsoft</span>
                </button>
                <button id="google-login-btn" class="btn custom-social-btn" role="button">
                    <img src="{{ asset('assets/images/auth/gmail-icon.png') }}" alt="Google Icon" class="social-icon">
                    <span>Login with Email</span>
                </button>
            </div>

            <div class="terms-container text-center mt-3">
                <p class="terms">
                    By clicking on <strong> <span class="terms-link"> "Login"</span></strong>,<br> you agree to our
                    <a href="#" class="terms-link">Privacy Policy</a> and
                    <a href="#" class="terms-link">Terms & Conditions</a>.
                </p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password-input');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>

</html>
