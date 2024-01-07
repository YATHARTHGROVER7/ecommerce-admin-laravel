@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
   <div>
      <h4 class="mb-sm-2">Settings</h4>
      <ol class="breadcrumb m-0">
         <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
         <li class="breadcrumb-item active">Social Settings</li>
      </ol>
   </div>
   <div class="page-title-right"></div>
</div>
<div class="page-content">
<div class="container-fluid">
@include('csadmin.elements.message')
<div class="row" id="table-striped">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
                <h5>Social Settings</h5>
            </div>
			<form method="post" action="{{route('csadmin.socialsettingprocess')}}">
				@csrf
				<div class="card-body">
					<input type="hidden" id="country-floating" class="" name="id" placeholder="" value="1">
					<div class="row">
						<div class="col-md-6 col-12">
							<div class="mb-3">
								<label class="form-label" for="country-floating"><b>Facebook Url:</b></label>
								<input type="text" id="country-floating" class="form-control  @error('facebook_url') is-invalid @enderror" name="facebook_url" placeholder="" value="@if(isset($settingData->facebook_url)){{$settingData->facebook_url}}@else{{''}}@endif">
								@error('facebook_url')
								<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						<div class="col-md-6 col-12">
							<div class="mb-3">
								<label class="form-label" for="country-floating"><b>Instagram Url:</b></label>
								<input type="text" id="country-floating" class="form-control  @error('instagram_url') is-invalid @enderror" name="instagram_url" placeholder="" value="@if(isset($settingData->instagram_url)){{$settingData->instagram_url}}@else{{''}}@endif">
								@error('instagram_url')
								<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-12">
							<div class="mb-3">
								<label class="form-label" for="country-floating"><b>Twitter Url:</b></label>
								<input type="text" id="country-floating" class="form-control @error('twitter_url') is-invalid @enderror" name="twitter_url" placeholder="" value="@if(isset($settingData->twitter_url)){{$settingData->twitter_url}}@else{{''}}@endif">
								@error('twitter_url')
								<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						<div class="col-md-6 col-12">
							<div class="mb-3">
								<label class="form-label" for="country-floating"><b>Youtube Url:</b></label>
								<input type="text" id="country-floating" class="form-control @error('youtube_url') is-invalid @enderror" name="youtube_url" placeholder="" value="@if(isset($settingData->youtube_url)){{$settingData->youtube_url}}@else{{''}}@endif">
								@error('youtube_url')
								<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						<div class="col-md-6 col-12">
							<div class="mb-3">
								<label class="form-label" for="country-floating"><b>LinkedIn Url:</b></label>
								<input type="text" id="country-floating" class="form-control @error('linkedin_url') is-invalid @enderror" name="linkedin_url" placeholder="" value="@if(isset($settingData->linkedin_url)){{$settingData->linkedin_url}}@else{{''}}@endif">
								@error('linkedin_url')
								<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						<div class="col-md-6 col-12">
							<div class="mb-3">
								<label class="form-label" for="country-floating"><b>Pinterest Url:</b></label>
								<input type="text" id="country-floating" class="form-control @error('pinterest_url') is-invalid @enderror" name="pinterest_url" placeholder="" value="@if(isset($settingData->pinterest_url)){{$settingData->pinterest_url}}@else{{''}}@endif">
								@error('pinterest_url')
								<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
</div>
</div>
@endsection