@php
$user = Session::get('CS_USER');
$settingData = App\Models\CsThemeAdmin::where('id', 1)->first();
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>{{env('APP_NAME')}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />
        <link rel="shortcut icon" href="@if(isset($settingData->favicon) && $settingData->favicon!=''){{env('SETTING_IMAGE')}}/{{$settingData->favicon}}@else{{env('NO_IMAGE')}}@endif" />
        <link href="{{asset('public/backend_assets/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/backend_assets/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/backend_assets/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
    </head>
    <body class="auth-body-bg">
        <div>
            <div class="container-fluid">
                <div class="row justify-content-md-center">
                    <div class="col-lg-4">
                        <div class="authentication-page-content p-4 d-flex align-items-center">
                            
                            @yield('content')
                        </div>
                        <div class="mt-5 text-center">
                            <p> ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                {{env('APP_NAME')}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{asset('public/backend_assets/assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/node-waves/waves.min.js')}}"></script>

        <script src="{{asset('public/backend_assets/assets/js/app.js')}}"></script>
    </body>
</html>
