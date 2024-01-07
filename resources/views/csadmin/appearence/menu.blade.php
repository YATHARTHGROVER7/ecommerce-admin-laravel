@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Appearance</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Menu</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row" id="table-striped">
		    @if(isset($permissionData) && in_array('MENUADD',$permissionData))
			<div class="col-4">
				<div class="card">
					<form method="post" action="{{route('csadmin.menuprocess')}}" enctype="multipart/form-data" accept-charset="utf-8">
						@csrf
						<input type="hidden" name="menu_id" value="@if(isset($menuIdData->menu_id) && $menuIdData->menu_id!=''){{$menuIdData->menu_id;}}@else {{'0'}}@endif">
						<div class="card-header">
							<h5>Add New Menu</h5>
						</div>
						<div class="card-body">
							<div class="mb-3">
								<label class="form-label">Menu Name / Title:</label>
								<input id="largeInput" class="form-control @error('menu_name') is-invalid @enderror" type="text" placeholder="" name="menu_name" value="@if(isset($menuIdData->menu_name) && $menuIdData->menu_name!=''){{$menuIdData->menu_name;}}@else{{old('menu_name')}}@endif">
								@error('menu_name')
								<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
								<p><small class="text-muted">This name is appears on your site</small></p>
							</div>
							<div class="mb-3">
								<label class="form-label">Categories:</label>
								<select class="form-select" name="menu_categoryid" id="basicSelect">
									<option value="0">Select any one</option>
									{!!$strCatEntryHtml!!}
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label">Pages:</label>
								<select class="form-select" name="menu_pageid" id="basicSelect">
									<option value="0">Select any one</option>
									@if(count($getpages)>0)
										@foreach($getpages as $getpage)
											<option value="{{$getpage->page_id}}" @if(isset($menuIdData->menu_pageid) && $menuIdData->menu_pageid==$getpage->page_id){{'selected'}}@else{{''}}@endif >{{$getpage->page_name}}</option>
										@endforeach
									@endif
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label">Parent Menu: </label>
								<select class="form-select" name="menu_parent" id="basicSelect">
									<option value="0">Main menu</option>
									{!!$strMenuEntryHtml!!}
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label">Custom Link: </label>
								<input id="largeInput" class="form-control" type="text" placeholder="" name="menu_customlink" value="@if(isset($menuIdData->menu_customlink) && $menuIdData->menu_customlink!=''){{$menuIdData->menu_customlink;}}@else{{old('menu_customlink')}}@endif">
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<div class="fileimg">
										<img class="fileimg-preview menuImagePreview" src="@if(isset($menuIdData->menu_image) && $menuIdData->menu_image!=''){{env('APPEARANCE_IMAGE')}}{{$menuIdData->menu_image}}@else{{env('NO_IMAGE')}}@endif">
										<div style="width:100%">
											<label for="category_image" class="form-label">Menu Image:</label>
											<div class="input-group">
												<input type="file" class="form-control" id="menuimage" name="menu_image_" accept="image/png, image/gif, image/jpeg, image/jpg" value="{{old('menu_image_')}}" onchange ="return MenuImageValidation('menuimage')">
											</div>
											<small class="text-muted" style="font-size:70%;">Accepted: gif, png, jpg. Max file size 2Mb</small>
										</div>
									</div>
								</div>
							</div>
							<div class="mb-3">
								<input class="form-check-input" name="show_home" type="checkbox" id="formCheck1" @if(isset($menuIdData->menu_mega) && $menuIdData->menu_mega==1){{'checked'}}@endif>
								<label class="form-check-label" for="formCheck1">Mega Menu</label>
							</div>
							<div class="mb-3">
								<input class="form-check-input" name="menu_show_image" type="checkbox" id="formCheck2" @if(isset($menuIdData->menu_show_image) && $menuIdData->menu_show_image==1){{'checked'}}@endif>
								<label class="form-check-label" for="formCheck2">Show Image</label>
							</div>
							<div class="col-lg-12" id="targetDiv" style="display:@php echo (isset($menuIdData->menu_show_image) && $menuIdData->menu_show_image==1)?'':'none'@endphp ">
								<div class="mb-3">
									<label class="form-label">Description:</label>
									<textarea name="menu_desc" class="ckeditor form-control " rows="3">{{(isset($menuIdData->menu_desc))?$menuIdData->menu_desc:''}}</textarea>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<div class="d-grid"><button type="submit" class="btn btn-primary waves-effect waves-float waves-light">@if(isset($menuIdData->menu_name) && $menuIdData->menu_name!=''){{'Update'}}@else{{'Save'}}@endif</button></div>
						</div>
					</form>
				</div>
			</div>@endif
			<div class="col-8">
				<div class="card">
					<div class="card-header">
						<h5>Menu Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;">S.No.</th>
										<th>Menu Name</th>
										@if(isset($permissionData) && in_array('MENUEDIT',$permissionData) || in_array('MENUDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody id="basic-list-group" class="connectedSortable">
									@if(count($menuData)>0)
										@php $counter = 1; @endphp
										@foreach($menuData as $menuVal)
											<tr class="draggable" sliderid="{{$menuVal->menu_id}}" sliderorder="{{$menuVal->menu_id}}">
												<td style="width: 50px; text-align: center;">{{$counter++}}</td>
												<td><span class="fw-bold">{{$menuVal->menu_name}}</span></td>
												@if(isset($permissionData) && in_array('MENUEDIT',$permissionData) || in_array('MENUDELETE',$permissionData))
												<td style="text-align: center;"> 
												@if(isset($permissionData) && in_array('MENUEDIT',$permissionData))
													<a href="{{route('csadmin.menu',$menuVal->menu_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
													@if(isset($permissionData) && in_array('MENUDELETE',$permissionData))
													<a href="{{route('csadmin.deletemenu',$menuVal->menu_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>@endif
												</td>@endif
											</tr>
											@if(count($menuVal->children)>0)
												@foreach($menuVal->children as $childData)
													<tr class="draggable" sliderid="{{$childData->menu_id}}" sliderorder="{{$childData->menu_id}}">
														<td style="width: 50px; text-align: center;">{{$counter++}}</td>
														<td>- <span class="fw-bold">{{$childData->menu_name}}</span></td>
														@if(isset($permissionData) && in_array('MENUEDIT',$permissionData) || in_array('MENUDELETE',$permissionData))
														<td style="text-align: center;"> 
														@if(isset($permissionData) && in_array('MENUEDIT',$permissionData))
															<a href="{{route('csadmin.menu',$childData->menu_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
															@if(isset($permissionData) && in_array('MENUDELETE',$permissionData))
															<a href="{{route('csadmin.deletemenu',$childData->menu_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>@endif
														</td>@endif
													</tr>
													@if(count($childData->subchildren)>0)
														@foreach($childData->subchildren as $subchildData)
														<tr class="draggable" sliderid="{{$subchildData->menu_id}}" sliderorder="{{$subchildData->menu_id}}">
															<td style="width: 50px; text-align: center;">{{$counter++}}</td>
															<td>-- <span class="fw-bold">{{$subchildData->menu_name}}</span></td>
															@if(isset($permissionData) && in_array('MENUEDIT',$permissionData) || in_array('MENUDELETE',$permissionData))
															<td style="text-align: center;">
															    @if(isset($permissionData) && in_array('MENUEDIT',$permissionData)) 
																<a href="{{route('csadmin.menu',$subchildData->menu_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
																@if(isset($permissionData) && in_array('MENUDELETE',$permissionData))
																<a href="{{route('csadmin.deletemenu',$subchildData->menu_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>@endif
															</td>@endif
														</tr>
														@endforeach
													@endif
												@endforeach
											@endif
										@endforeach
									@else
										<tr>
											<td colspan="3" class="text-center">No Data Found</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				@include('csadmin.elements.pagination',['pagination'=>$menuData])
				</div>
			</div>
		</div>
	</div>
</div>
<script>
  const toggleCheckbox = document.getElementById('formCheck2');
  const targetDiv = document.getElementById('targetDiv');

  toggleCheckbox.addEventListener('click', function () {
    if (toggleCheckbox.checked) {
      targetDiv.style.display = 'block'; // Show the div
    } else {
      targetDiv.style.display = 'none'; // Hide the div
    }
  });
</script>

<script>
	$(function() {
		$( "#basic-list-group").sortable({
			connectWith: ".connectedSortable", 
			update:function(){
				var aryOrderInfo = {'sliderid':[],'ordernum':[],'_token':_token};
				$('.draggable').each(function(){
					var intsliderid =$(this).attr('sliderid');
					var intOrderNum = parseInt(1)+parseInt($(this).index());
					aryOrderInfo['sliderid'].push(intsliderid);
					aryOrderInfo['ordernum'].push(intOrderNum);
				});  
				$.post(site_url+'updatemenuOrderAjax',aryOrderInfo,function(response){
					location.reload();
				});
			}
		}).disableSelection();
	});

	function MenuImageValidation(menuimage){
	    var fileInput = document.getElementById(menuimage);
		var mime = fileInput.value.split('.').pop();
	    var fsize = fileInput.files[0].size;
	    var file = fsize / 1024;
		var mb = file / 1024; // convert kb to mb
	    if(mb > maxMb)
	    {         
			alert('Image size must be less than 2mb');
			$('#menuimage').val('');
	    }else  if (!allowedMimes.includes(mime)) {  // if allowedMimes array does not have the extension
	        alert("Only png, jpg, jpeg alowed");
	        $('#menuimage').val('');
	    }else{
		        let reader = new FileReader();
		        reader.onload = function (event) {
		            $(".menuImagePreview").attr("src", event.target.result);
		        };
		        reader.readAsDataURL(fileInput.files[0]);
		}
	}
</script>
@endsection