@extends('csadmin.layouts.login')
@section('content')
@php
$settingData = App\Models\CsThemeAdmin::where('id', 1)->first();
@endphp

<div class="w-100 mt-3">
	<div class="row justify-content-center">
		<div class="col-lg-12">
			<div>
				<div class="text-center">
					<div>
						<a href="{{env('APP_URL')}}" class="">
							<img src="@if(isset($settingData->logo) && $settingData->logo!=''){{env('SETTING_IMAGE')}}/{{$settingData->logo}}@else{{''}}@endif" alt="" height="50" class="auth-logo logo-dark mx-auto" />
						</a>
					</div>
					<h4 class="font-size-18 mt-4">Welcome Back !</h4>
					<p class="text-muted">Sign in to continue to {{env('APP_NAME')}}.</p>
				</div>
				@include('csadmin.elements.message')
				<div class="p-2 mt-2">
					<form class="login-form" action="{{route('adminlogincheck')}}" method="post">
						@csrf
						<div class="mb-3 auth-form-group-custom mb-4">
							<i class="ri-user-2-line auti-custom-input-icon"></i>
							<label for="username">Username</label>
							<input type="email" class="form-control @error('administration_email') is-invalid @enderror" id="username" name="administration_email" placeholder="Enter username" />
							@error('administration_email')
								<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
						</div>
						<div class="mb-3 auth-form-group-custom mb-4">
							<i class="ri-lock-2-line auti-custom-input-icon"></i>
							<label for="userpassword">Password</label>
							<input type="password" class="form-control @error('admin_password') is-invalid @enderror" id="userpassword" name="admin_password" placeholder="Enter password" />
							@error('admin_password')
								<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
						</div>
						<div class="mt-4 text-center">
							<button class="btn btn-primary w-md waves-effect waves-light" type="submit">Log In</button>
						</div>
						</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection