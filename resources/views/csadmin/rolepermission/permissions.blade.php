@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Permissions</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">All Permissions</li>
		</ol>
	</div>
	<div class="page-title-right">
		<a href="{{route('csadmin.addrole')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
<!-- Striped rows start -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Permissions Listing</h5>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered mb-0">
              <thead>
                <tr>
                  <th>Permissions</th>
                  @foreach($roleData as $value) 
                  <th style="margin-bottom:1px; text-align:center;">{{$value->role_name}}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
               	<tr>
                  <td>
                    <h5 style="margin-bottom:0px">Dashboard</h5>
                  </td>
                </tr>
				<tr>
                  <td>Dashboard List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','DASHLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'DASHLIST')" {{(isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>View</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','DASHVIEW')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'DASHVIEW')" {{(isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
       
			         <tr>
                  <td>
                    <h5 style="margin-bottom:0px">Customers</h5>
                  </td>
              </tr>
			         <tr>
                  <td>Customers list</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CUSTLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CUSTLIST')" {{(isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CUSTSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CUSTSTATUS')" {{(isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>View</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CUSTVIEW')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CUSTVIEW')" {{(isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
				  <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CUSTDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CUSTDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>
                    <h5 style="margin-bottom:0px">Seller</h5>
                  </td>
                </tr>
				  <tr>
                  <td>Seller list</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SELLERLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SELLERLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>View</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SELLERVIEW')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SELLERVIEW')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SELLERSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SELLERSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SELLEREDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SELLEREDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SELLERDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SELLERDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SELLERADDNEW')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SELLERADDNEW')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <!--<tr>
                  <td>Export File</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CUSTEXPORT')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CUSTEXPORT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>-->
				  <tr>
                  <td>
                    <h5 style="margin-bottom:0px">Products Catalogue</h5>
                  </td>
                </tr>
				  <tr>
                  <td>All Product</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROALLPRODUCT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROALLPRODUCT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Featured</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROFEATURED')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROFEATURED')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Recommended</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PRORECOM')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PRORECOM')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Copy</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROCOPY')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROCOPY')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
               <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PRODELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PRODELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROADDNEW')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROADDNEW')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
				      <tr>
                  <td>
                    <h5 style="margin-bottom:0px">Product Categories</h5>
                  </td>
                </tr>
				       <tr>
                  <td>Categories List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROCATELIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROCATELIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROCATEADD')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROCATEADD')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Featured</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROCATEFEATURED')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROCATEFEATURED')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROCATESTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROCATESTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROCATEEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROCATEEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROCATEDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROCATEDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
				       <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Tag</h5>
                 </td>
               </tr>
				        <tr>
                  <td>Tag List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROTAGLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROTAGLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROTAGADD')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROTAGADD')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Featured</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROTAGFEATURED')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROTAGFEATURED')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROTAGSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROTAGSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROTAGEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROTAGEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROTAGDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROTAGDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Label</h5>
                 </td>
               </tr>
               <tr>
                  <td>Label List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROLABELLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROLABELLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROLABELADD')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROLABELADD')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROLABELSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROLABELSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROLABELEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROLABELEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROLABELDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROLABELDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Attribute</h5>
                 </td>
               </tr>
               <tr>
                  <td>Attribute List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROATTRLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROATTRLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROATTRADD')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROATTRADD')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Variation</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROATTRFEATURED')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROATTRFEATURED')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROATTRSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROATTRSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROATTREDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROATTREDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROATTRDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROATTRDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Terms</h5>
                 </td>
               </tr>
               
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROTERADD')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROTERADD')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROTERSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROTERSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROTEREDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROTEREDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROTERDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROTERDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Brand</h5>
                 </td>
               </tr>
               <tr>
                  <td>Brand List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROBRANDLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROBRANDLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROBRANDADD')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROBRANDADD')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROBRANDSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROBRANDSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROBRANDEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROBRANDEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PROBRANDDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PROBRANDDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
               <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Meet The Makers</h5>
                 </td>
               </tr>
				        <tr>
                  <td>Meet The Makers List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MAKERLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MAKERLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MAKERADD')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MAKERADD')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MAKERSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MAKERSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MAKEREDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MAKEREDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MAKERDELETE')->first(); @endphp 
                  <td style="text-align:center;"> 
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MAKERDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Reviews</h5>
                 </td>
               </tr>
               <tr>
                  <td>Review List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','REVIEWLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'REVIEWLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','REVIEWSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'REVIEWSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Orders</h5>
                 </td>
               </tr>
               <tr>
                  <td>Order List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','ORDERLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'ORDERLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <!--<tr>
                  <td>Order Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','ORDERSTATUS')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'ORDERSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>-->
             
              <tr>
                  <td>View</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','ORDERVIEW')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'ORDERVIEW')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Offers & Promos</h5>
                 </td>
               </tr>
               <tr>
                  <td>Offers & Promos List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','OFFPROLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'OFFPROLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Featured</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','OFFPROFEATURED')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'OFFPROFEATURED')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','OFFPROSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'OFFPROSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','OFFPROEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'OFFPROEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','OFFPRODELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'OFFPRODELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','OFFPROADDNEW')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'OFFPROADDNEW')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Testimonials</h5>
                 </td>
               </tr>
               <tr>
                  <td>Testimonial List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','TESTLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'TESTLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Featured</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','TESTFEATURED')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'TESTFEATURED')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','TESTSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'TESTSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','TESTEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'TESTEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','TESTDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'TESTDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','TESTADDNEW')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'TESTADDNEW')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Media</h5>
                 </td>
               </tr>
               <tr>
                  <td>Media List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MEDIALIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MEDIALIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MEDIAEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MEDIAEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MEDIADELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MEDIADELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MEDIAADDNEW')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MEDIAADDNEW')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">News & Blogs</h5>
                 </td>
               </tr>
               <tr>
                  <td>News & Blogs List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Featured</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGFEATURED')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGFEATURED')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGADDNEW')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGADDNEW')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">News & Blog Categories</h5>
                 </td>
               </tr>
               <tr>
                  <td>Categories List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGCATELIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGCATELIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGCATEADD')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGCATEADD')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGCATESTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGCATESTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGCATEEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGCATEEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGCATEDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGCATEDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Tags</h5>
                 </td>
               </tr>
               <tr>
                  <td>Tag List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGTAGLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGTAGLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGTAGADD')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGTAGADD')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGTAGSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGTAGSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGTAGEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGTAGEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSBLOGTAGDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSBLOGTAGDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Appearence</h5>
                 </td>
               </tr>
               <tr>
                  <td>Slider & Banner List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SILDBANLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SILDBANLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SILDBANSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SILDBANSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SILDBANEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SILDBANEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SILDBANDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SILDBANDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SILDBANADDNEW')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SILDBANADDNEW')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Menu</h5>
                 </td>
               </tr>
               <tr>
                  <td>Menu List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MENULIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MENULIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MENUADD')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MENUADD')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MENUEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MENUEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MENUDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MENUDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Header</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','HEADER')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'HEADER')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Footer</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','FOOTER')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'FOOTER')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Editor</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','EDITOR')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'EDITOR')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
                <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Certificate</h5>
                 </td>
               </tr>
               <tr>
                  <td>Certificate List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CERTIFICATESLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CERTIFICATESLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CERTIFICATESADD')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CERTIFICATESADD')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CERTIFICATESSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CERTIFICATESSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Featured</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CERTIFICATESFEATURED')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CERTIFICATESFEATURED')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CERTIFICATESEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CERTIFICATESEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CERTIFICATESDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CERTIFICATESDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Shipping Rate</h5>
                 </td>
               </tr>
               <tr>
                  <td>Shipping Rate List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SHIPPINGRATELIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SHIPPINGRATELIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Select Shipping Serviceable Area</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SSSAREA')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SSSAREA')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Activate Shipping Rates Based On India</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','ASRBOINDIA')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'ASRBOINDIA')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Activate Shipping Rates Based On Countries</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','ASRBOCONTRIES')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'ASRBOCONTRIES')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Free Shipping</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','FREESHIPPING')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'FREESHIPPING')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              
              <tr>
                  <td>
                    <h5 style="margin-bottom:0px">Shipping Agency</h5>
                  </td>
                </tr>
				
              <tr>
                  <td>View</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SHIPPINGVIEW')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SHIPPINGVIEW')" {{(isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">E-commerce</h5>
                 </td>
               </tr>
               <tr>
                  <td>E-commerce List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PAYPRATLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PAYPRATLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Razorpay</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','RAZORPAY')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'RAZORPAY')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              
              <tr>
                  <td>CC Avenue</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CCPAY')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CCPAY')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Payumoney</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PAYUMONEY')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PAYUMONEY')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Cash on Delivery</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CASH')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CASH')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
               <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Tax</h5>
                 </td>
               </tr>
				        <tr>
                  <td>Tax List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','TAXLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'TAXLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','TAXADD')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'TAXADD')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','TAXSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'TAXSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','TAXEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'TAXEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','TAXDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'TAXDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Transactions</h5>
                 </td>
               </tr>
               <tr>
                  <td>Transaction List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','TRANLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'TRANLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Sales</h5>
                 </td>
               </tr>
               <tr>
                  <td>Sales Report List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','REPORTLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'REPORTLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Career Enquiry</h5>
                 </td>
               </tr>
               <tr>
                  <td>Career Enquiry List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CENQLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CENQLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td> Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CENQDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CENQDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  
                  @endforeach
              </tr>
              
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px"> Enquiry</h5>
                 </td>
               </tr>
               <tr>
                  <td> Enquiry List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CONUSENQLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CONUSENQLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td> Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CONUSENQDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CONUSENQDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Thank You Enquiry</h5>
                 </td>
               </tr>
               <tr>
                  <td> Thank You Enquiry List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','THENQLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'THENQLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  
                  @endforeach
              </tr>
              <tr>
                  <td> Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','THENQDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'THENQDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  
                  @endforeach
              </tr>
             
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Newsletter</h5>
                 </td>
               </tr>
               <tr>
                  <td>Newsletter List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSLETTERLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSLETTERLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','NEWSLETTERDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'NEWSLETTERDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <!--<tr>
                 <td>
						         <h5 style="margin-bottom:0px">Contact Us Enquiry</h5>
                 </td>
               </tr>
               <tr>
                  <td>Contact Us Enquiry List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CONUSENQLIST')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CONUSENQLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CONUSENQDELETE')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CONUSENQDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Export</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CONUSENQEXPORT')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CONUSENQEXPORT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>-->
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Location</h5>
                 </td>
               </tr>
               <tr>
                  <td>Location List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','LOCATIONLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'LOCATIONLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add State</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','LOCATIONSTATE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'LOCATIONSTATE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add City</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','LOCATIONCITY')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'LOCATIONCITY')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add Pin Code</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','LOCATIONPINCODE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'LOCATIONPINCODE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','LOCATIONDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'LOCATIONDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
               
              <!--<tr>
                 <td>
						         <h5 style="margin-bottom:0px">Staff & Teams</h5>
                 </td>
               </tr>
               <tr>
                  <td>Manage Staff & Teams</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MSTEAM')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MSTEAM')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MSTSTATUS')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MSTSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MSTEDIT')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MSTEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MSTDELETE')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MSTDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','MSTADDNEW')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'MSTADDNEW')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Roles</h5>
                 </td>
               </tr>
               <tr>
                  <td>Role List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','ROLELIST')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'ROLELIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','ROLESTATUS')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'ROLESTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','ROLEEDIT')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'ROLEEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','ROLEDELETE')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'ROLEDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','ROLEADDNEW')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'ROLEADDNEW')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Permission</h5>
                 </td>
               </tr>
               <tr>
                  <td>Permission List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PERLIST')->first(); @endphp 
                  <td>
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PERLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>-->
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Pages</h5>
                 </td>
               </tr>
               <tr>
                  <td>Pages List</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PAGLIST')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PAGLIST')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Status</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PAGSTATUS')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PAGSTATUS')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Edit</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PAGEDIT')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PAGEDIT')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Delete</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PAGDELETE')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PAGDELETE')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Add New</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','PAGADDNEW')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'PAGADDNEW')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                 <td>
						         <h5 style="margin-bottom:0px">Setting</h5>
                 </td>
               </tr>
               <tr>
                  <td>Contact Setting</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','CONSET')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'CONSET')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Site Setting</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SITSET')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SITSET')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              <tr>
                  <td>Social Settings</td>
                  @foreach($roleData as $value) @php $permissiondata = App\Models\CsPermission::where('permission_role_id',$value->role_id)->where('permission_type','SOCSET')->first(); @endphp 
                  <td style="text-align:center;">
                    <input type="checkbox" id="country-floating" name="role_id" onchange="checkrole(this,{{$value->role_id}},'SOCSET')" {{ (isset($permissiondata) && $permissiondata->permission_status == 1 ? 'checked' : '')}} />
                  </td>
                  @endforeach
              </tr>
              </tbody>
            </table>
          </div>
          
        </div>
      </div>
    </div>
    </div>
    <!-- Striped rows end -->
</div>
<script>
  function checkrole(obj, role_id, type) {
    var _token = "{{ csrf_token() }}";
    // role = $('.roleid').val();
    if ($(obj).is(':checked')) {
      var datastring = 'type=' + type + "&role_id=" + role_id + '&_token=' + _token;
      $.post("{{ route('givepermission') }}", datastring, function(response) {});
    } else {
      var datastring = 'type=' + type + "&role_id=" + role_id + '&_token=' + _token;
      $.post("{{ route('removepermission') }}", datastring, function(response) {});
    }
  }
</script>
@endsection