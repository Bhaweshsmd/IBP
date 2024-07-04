<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ __('App Name') }}</title>
    <link rel="stylesheet" href="asset/css/app.min.css">
    <link rel="stylesheet" href="asset/bundles/bootstrap-social/bootstrap-social.css">
    <link rel="stylesheet" href="asset/css/style.css">
    <link rel="stylesheet" href="asset/css/components.css">
    <link rel="stylesheet" href="asset/css/custom.css">
    <link rel="stylesheet" href="asset/css/loginPage.css">
    <link rel='shortcut icon' type='image/x-icon' href='asset/img/favicon.ico' />
</head>

<body>
    <div class="loader"></div>

    <div class="main-login-row">
        <div class="width-50 ">
            <div class="main-login-two-box">
                <div class="img-full-box" style="background-image: url(./asset/image/login_page.jpg)">
                    <div class="center-title-text">
                        <div class="bottom-blur-inner">
                            <h1 class="main-title font-60 gil-heavy">ISIDEL BEACH PARK</h1>
                            <div class="width-c-50">
                                <p class="m-0 font-20 text-contant  gil-reg">Welcome to Isidel Beach Park.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="width-50 ">
            <div class="main-login-one-box  center">
                <div class="center container ">
                    <div class="form-login-main-box ">
                        <h1 class="login-headr font-30 gil-heavy m-0">LOG IN</h1>
                        <h2 class="login-title font-20 gil-reg  mb-4">To access the dashboard</h2>

                        <form method="POST" action="">
                            @csrf
                            <div class="form-x-box main-card ">
                                <div>
                                    <div class="d-flex flex-column mb-3 w-100">
                                        <label for="Username" class="gil-med font-18 text-salon-black">Username</label>
                                        <input name="user_name" type="text" class="login-fild gil-med font-18 px-3"
                                            required id="user_name">
                                    </div>
                                    <div class="d-flex flex-column mb-3 w-100">
                                        <label for="password" class="gil-med font-18 text-salon-black">Password</label>
                                        <input name="user_password" type="password"
                                            class="login-fild gil-med font-18 px-3" required id="user_password">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-dark">
                                    <p class=" gil-med text-white m-0">Login</p>
                                </button>
                                <a class="text-center" href="{{route('verify-forgot-password')}}" >Forgot Password</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="asset/js/app.min.js"></script>
    <script src="asset/js/scripts.js"></script>
    <script src="asset/js/custom.js"></script>
</body>
</html>