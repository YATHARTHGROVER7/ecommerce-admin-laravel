@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
   <div>
      <h4 class="mb-sm-2">Settings</h4>
      <ol class="breadcrumb m-0">
         <li class="breadcrumb-item"><a href="javascript: void(0);">Setting</a></li>
         <li class="breadcrumb-item active">Site Setting</li>
      </ol>
   </div>
   <div class="page-title-right"></div>
</div>
<div class="page-content">
   <div class="container-fluid">
      @include('csadmin.elements.message')
      <div class="row">
         <div class="col-12">
            <form method="post" action="{{route('csadmin.sitesettingsprocess')}}" enctype="multipart/form-data" accept-charset="utf-8">
               @csrf
               <input type="hidden" name="id" value="1">
               <div class="card">
                  <div class="card-header">
                     <h5>Site Details & Settings</h5>
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="mb-3">
                              <label for="validationCustom01" class="form-label">Site Title: <span style="color:red">*</span></label>
                              <input type="text" class="form-control @error('site_title') is-invalid @enderror" id="validationCustom01" value="@if(isset($settingData->site_title)){{$settingData->site_title}}@else{{''}}@endif" name="site_title">
                              @error('site_title')
                              <div class="valid-feedback invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="mb-3">
                              <label for="validationCustom01" class="form-label">Site Address (URL):</label>
                              <input type="text" class="form-control" id="validationCustom01" name="site_address" value="@if(isset($settingData->site_address)){{$settingData->site_address}}@else{{''}}@endif" readonly="">
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="mb-3">
                              <label for="validationCustom01" class="form-label">Administration Email Address: <span style="color:red">*</span></label>
                              <input type="email" class="form-control @error('administration_email') is-invalid @enderror" id="validationCustom01" placeholder="First name" name="administration_email" value="@if(isset($settingData->admin_email)){{$settingData->admin_email}}@else{{old('administration_email')}}@endif">
                              @error('administration_email')
                              <div class="valid-feedback invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="mb-3">
                              <label class="form-label">Support Email Address: </label>
                              <input type="email" class="form-control"  placeholder="Support Email Address" name="admin_support_email" value="@if(isset($settingData->admin_support_email)){{$settingData->admin_support_email}}@endif">
                              
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="mb-3">
                              <label class="form-label">Support Mobile Number: </label>
                              <input type="number" class="form-control" placeholder="Support Mobile Number" name="admin_support_mobile" value="@if(isset($settingData->admin_support_mobile)){{$settingData->admin_support_mobile}}@endif" min="0">
                           </div>
                        </div>
						 <div class="col-lg-6">
                           <div class="mb-3">
                              <label class="form-label">Whatsapp Number: </label>
                              <input type="number" class="form-control" placeholder="Whatsapp Number" min="0" name="admin_whatsapp_no" value="@if(isset($settingData->admin_whatsapp_no)){{$settingData->admin_whatsapp_no}}@endif">
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="mb-3">
                              <label class="form-label">GST no.: </label>
                              <input type="text" class="form-control @error('admin_gst_no') is-invalid @enderror"  placeholder="GST no." name="admin_gst_no" value="@if(isset($settingData->admin_gst_no)){{$settingData->admin_gst_no}}@endif">
                           </div>
                        </div>
                        
                        <div class="col-lg-12">
                           <div class="mb-3">
                              <label class="form-label">Share Product Message: </label>
                              <textarea type="text" class="form-control @error('admin_share_message') is-invalid @enderror" placeholder="Share Product Message" name="admin_share_message">@if(isset($settingData->admin_share_message)){{$settingData->admin_share_message}}@endif</textarea>
                           </div>
                        </div>
                        <div class="col-lg-12">
                           <hr>
                           <h6 class="mb-0"><strong>Logo & Favicon</strong></h6>
                           <hr>
                        </div>
                        <div class="col-lg-4">
                           <div class="mb-3">
                              <div class="fileimg">
                                 <img class="fileimg-preview logoimage" src="@if(isset($settingData->logo) && $settingData->logo!=''){{env('SETTING_IMAGE')}}{{$settingData->logo}}@else{{env('NO_IMAGE')}}@endif">
                                 <div style="width:100%">
                                    <label for="validationCustom01" class="form-label">Site Logo:</label>
                                    <div class="input-group">
                                       <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logoFile" name="logo" accept="image/png, image/gif, image/jpeg" onchange="return logoValidation('logoFile')" id="logofile"> 
                                    </div>
                                    <small class="text-muted">Accepted: gif, png, jpg. Max file size 2Mb</small>
                                    @error('logo')
                                    <div class="valid-feedback invalid-feedback">{{ $message }}</div>
                                    @enderror
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-4">
                           <div class="mb-3">
                              <div class="fileimg">
                                 <img class="fileimg-preview favicon" name="favicon" src="@if(isset($settingData->favicon) && $settingData->favicon!=''){{env('SETTING_IMAGE')}}{{$settingData->favicon}}@else{{env('NO_IMAGE')}}@endif">
                                 <div style="width:100%">
                                    <label for="validationCustom01" class="form-label">Favicon Site Icon:</label>
                                    <div class="input-group">
                                       <input type="file" class="form-control @error('favicon') is-invalid @enderror" id="faviconFile" name="favicon" accept="image/png, image/gif, image/jpeg" onchange="return faviconValidation('faviconFile')">
                                    </div>
                                    <small class="text-muted">Accepted: gif, png, jpg. Max file size 2Mb</small>
                                    @error('favicon')
                                    <div class="valid-feedback invalid-feedback">{{ $message }}</div>
                                    @enderror
                                 </div>
                              </div>
                           </div>
                        </div>
						 <div class="col-lg-4">
                           <div class="mb-3">
                              <div class="fileimg">
                                 <img class="fileimg-preview footerlogo" name="footer_logo" src="@if(isset($settingData->footer_logo) && $settingData->footer_logo!=''){{env('SETTING_IMAGE')}}{{$settingData->footer_logo}}@else{{env('NO_IMAGE')}}@endif">
                                 <div style="width:100%">
                                    <label for="validationCustom01" class="form-label">Footer Logo:</label>
                                    <div class="input-group">
                                       <input type="file" class="form-control @error('footer_logo') is-invalid @enderror" id="footerFile" name="footer_logo" accept="image/png, image/gif, image/jpeg" onchange="return footerValidation('footerFile')">
                                    </div>
                                    <small class="text-muted">Accepted: gif, png, jpg. Max file size 2Mb</small>
                                    @error('footer_logo')
                                    <div class="valid-feedback invalid-feedback">{{ $message }}</div>
                                    @enderror
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card-footer">
                     <button type="submit" class="btn btn-primary waves-effect waves-light">Save Changes</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
   var allowedMimes = ['png', 'jpg', 'jpeg', 'gif']; //allowed image mime types
   var maxMb = 2; //maximum allowed size (MB) of image
   	
   function logoValidation(logoFile){
       var fileInput = document.getElementById(logoFile);
   	
   	var mime = fileInput.value.split('.').pop();
       var fsize = fileInput.files[0].size;
       var file = fsize / 1024;
   	var mb = file / 1024; // convert kb to mb
       if(mb > maxMb)
       {         
   		alert('Image size must be less than 2mb');
       }else  if (!allowedMimes.includes(mime)) {  // if allowedMimes array does not have the extension
           alert("Only png, jpg, jpeg alowed");
       }else{
   	        let reader = new FileReader();
   	        reader.onload = function (event) {
   	            $(".logoimage").attr("src", event.target.result);
   	        };
   	        reader.readAsDataURL(fileInput.files[0]);
   	}
   }
   function faviconValidation(faviconFile){
       var fileInput = document.getElementById(faviconFile);
   	var mime = fileInput.value.split('.').pop();
       var fsize = fileInput.files[0].size;
       var file = fsize / 1024;
   	var mb = file / 1024; // convert kb to mb
       if(mb > maxMb)
       {         
   		alert('Image size must be less than 2mb');
       }else  if (!allowedMimes.includes(mime)) {  // if allowedMimes array does not have the extension
           alert("Only png, jpg, jpeg alowed");
       }else{
   	        let reader = new FileReader();
   	        reader.onload = function (event) {
   	            $(".favicon").attr("src", event.target.result);
   	        };
   	        reader.readAsDataURL(fileInput.files[0]);
   	}
   }
	function footerValidation(footerFile){
       var fileInput = document.getElementById(footerFile);
   	var mime = fileInput.value.split('.').pop();
       var fsize = fileInput.files[0].size;
       var file = fsize / 1024;
   	var mb = file / 1024; // convert kb to mb
       if(mb > maxMb)
       {         
   		alert('Image size must be less than 2mb');
       }else  if (!allowedMimes.includes(mime)) {  // if allowedMimes array does not have the extension
           alert("Only png, jpg, jpeg alowed");
       }else{
   	        let reader = new FileReader();
   	        reader.onload = function (event) {
   	            $(".footerlogo").attr("src", event.target.result);
   	        };
   	        reader.readAsDataURL(fileInput.files[0]);
   	}
   }
</script>
@endsection