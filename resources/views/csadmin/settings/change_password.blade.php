@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
   <div>
      <h4 class="mb-sm-2">Settings</h4>
      <ol class="breadcrumb m-0">
         <li class="breadcrumb-item"><a href="javascript: void(0);">Setting</a></li>
         <li class="breadcrumb-item active">Change Password</li>
      </ol>
   </div>
   <div class="page-title-right"></div>
</div>
<div class="page-header page-header-light">
   <div class="page-header-content header-elements-md-inline">
      <div class="page-title d-flex">
         <div>
            <div class="header-elements-md-inline">
               <div class="d-flex">
                  <div class="breadcrumb"></div>
               </div>
            </div>
         </div>
         <a href="{{route('dashboard')}}" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
      </div>
   </div>
</div>
<div class="content" style="margin-top:-60px;">
   <div class="page-header-content header-elements-md-inline">
      <div class="page-title d-flex">
         <div>
            <div class="header-elements-md-inline">
               <div class="d-flex">
                  <div class="breadcrumb"></div>
               </div>
            </div>
         </div>
         <a href="{{route('dashboard')}}" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         @if(session()->has('success'))
         <div class="alert alert-success alert-dismissible">
            <!--<button type="button" class="close" data-dismiss="alert"><span>×</span></button>-->
            {{ session()->get('success') }}
         </div>
         @endif
         @if(session()->has('error'))
         <div class="alert alert-warning alert-dismissible">
            <!--<button type="button" class="close" data-dismiss="alert"><span>×</span></button>-->
            {{ session()->get('error') }}
         </div>
         @endif
         <form method="post" action="{{route('backend.changepasswordprocess')}}">
            @csrf
            <div class="card">
               <div class="card-header bg-transparent header-elements-inline">
                  <h5>Change Password</h5>
               </div>
               <div class="card-body">
                  <div class="row">
                     <div class="col-lg-6">
                        <div class="form-group">
                           <label>Password:</label>
                           <span style="color:red;">*</span>
                           <input type="password" class="form-control  @error('password') is-invalid @enderror" name="password" placeholder="***********" autocomplete="current-password">
                           @error('password')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <div class="form-group">
                           <label>Confirm Password:</label>
                           <span style="color:red;">*</span>
                           <input type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" placeholder="***********" autocomplete="current-password">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card-footer bg-white d-flex justify-content-between">
                  <button type="submit" class="btn btn-primary">Save & Continue</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection