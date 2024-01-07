@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <div>
        <h4 class="mb-sm-2">Manage Offers & Promos</h4>
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
            <li class="breadcrumb-item active">Offers & Promos</li>
        </ol>
    </div>
    @if(isset($permissionData) && in_array('OFFPROADDNEW',$permissionData))
    <div class="page-title-right">
        <a href="{{route('csadmin.addoffers')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
    </div>@endif
</div>
<div class="page-content">
    <div class="container-fluid">
        @include('csadmin.elements.message')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form action="{{route('csadmin.alloffers')}}" method="post" accept-charset="utf-8">
                        @csrf
                        <div class="card-body" style="border-bottom: 1px solid #f1f5f7; padding: 0.9rem 1.25rem;">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-10">
                                    <input
                                        class="form-control"
                                        type="text"
                                        placeholder="Search name..."
                                        name="search_filter"
                                        value="@php echo (!empty($aryFilterSession) && $aryFilterSession['search_filter']!='')?$aryFilterSession['search_filter']:''; @endphp"
                                    />
                                </div>
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" style="margin-left: 5px; display: flex; align-items: center; justify-content: center; float: left;">Search</button>
                                    @if(!empty($aryFilterSession))
                                    <a href="{{route('csadmin.offers.resetfilter')}}" class="btn btn-danger waves-effect waves-light" style="margin-left: 5px; align-items: center; justify-content: center;">Reset</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header" style="display: flex; align-items: center;">Offers & Promos Listings</h5>
                    <div class="card-body" style="border-bottom: 1px solid #f1f5f7; padding: 0.9rem 1.25rem;">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-6">
                                <div class="filter-list">
                                    <ul>
                                        <li class="@if(isset($type) && $type == 'all') {{'active'}} @endif"><a href="{{route('csadmin.alloffers',1)}}">All ({{$countall}})</a></li>
                                        <li class="@if(isset($type) && $type == 'active') {{'active'}} @endif"><a href="{{route('csadmin.alloffers',2)}}">Active ({{$countactive}})</a></li>
                                        <li class="@if(isset($type) && $type == 'inactive') {{'active'}} @endif"><a href="{{route('csadmin.alloffers',3)}}">Inactive ({{$countinactive}})</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="tablesearch">
                                    <select name="bulkstatus" class="custom-select getstatus">
                                        <option value="0" readonly>Bulk action</option>
                                        @if(isset($permissionData) && in_array('OFFPROSTATUS',$permissionData))
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>@endif
                                        @if(isset($permissionData) && in_array('OFFPRODELETE',$permissionData))
                                        <option value="3">Delete</option>@endif
                                    </select>
                                    <button
                                        type="button"
                                        class="btn btn-primary waves-effect waves-light"
                                        style="margin-left: 5px; display: flex; align-items: center; justify-content: center;"
                                        onclick="return checkcondition($(this));"
                                        id="actionbtn"
                                    >
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0px;">
                        <div class="table-responsive">
                            <table class="table mb-0 table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 68px; text-align: center;">
                                            <input type="checkbox" id="select-all" />
                                        </th>
                                        <th style="width: 50px; text-align: center;">S.No.</th>
                                        <th>Promo Name</th>
                                        <th>Promo Code</th>
                                        <th>Valid From</th>
                                        <th>Valid To</th>
                                        @if(isset($permissionData) && in_array('OFFPROFEATURED',$permissionData))
                                        <th style="text-align: center;">Featured</th>@endif
                                         @if(isset($permissionData) && in_array('OFFPROSTATUS',$permissionData))
                                        <th style="text-align: center;">Status</th>@endif
                                        @if(isset($permissionData) && in_array('OFFPROEDIT',$permissionData) || in_array('OFFPRODELETE',$permissionData))
                                        <th style="text-align: center;">Action</th>@endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($offerData)>0) @php $counter = 1; @endphp @foreach($offerData as $offerVal)
                                    <tr>
                                        <td style="width: 68px; text-align: center;">
                                            <input type="checkbox" class="singlecheckbox" data-id="{{$offerVal->promo_id}}" name="select" />
                                        </td>
                                        <td style="text-align: center;">{{$counter++}}</td>
                                        <td>{{$offerVal->promo_name}}</td>
                                        <td>{{$offerVal->promo_coupon_code}}</td>
                                        <td>{{date("d M, Y",strtotime($offerVal->promo_valid_from))}}</td>
                                        <td>{{date("d M, Y",strtotime($offerVal->promo_valid_to))}}</td>
                                        @if(isset($permissionData) && in_array('OFFPROFEATURED',$permissionData))
										<td style="text-align: center;">
                                            @if(isset($offerVal->promo_featured) && $offerVal->promo_featured==1)
                                            <a href="{{route('csadmin.offersfeatured',$offerVal->promo_id)}}"><i class="fas fa-star"></i></a>
                                            @else
                                            <a href="{{route('csadmin.offersfeatured',$offerVal->promo_id)}}"><i class="far fa-star"></i></a>
                                            @endif
                                        </td>@endif
                                        @if(isset($permissionData) && in_array('OFFPROSTATUS',$permissionData))
                                        <td style="text-align: center;">
                                            @if(isset($offerVal->promo_status) && $offerVal->promo_status==1)
                                            <a href="{{route('csadmin.offersstatus',$offerVal->promo_id)}}"><span class="badge bg-success font-size-12">Active</span></a>
                                            @else
                                            <a href="{{route('csadmin.offersstatus',$offerVal->promo_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a>
                                            @endif
                                        </td>@endif
										
                                        <td style="text-align: center;">
                                             @if(isset($permissionData) && in_array('OFFPROEDIT',$permissionData))
                                            <a href="{{route('csadmin.addoffers',$offerVal->promo_id)}}" class="btn btn-info btn-sm btnaction" title="Edit" alt="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
                                             @if(isset($permissionData) && in_array('OFFPRODELETE',$permissionData))
                                            <a href="{{route('csadmin.deleteoffers',$offerVal->promo_id)}}" class="btn btn-danger btn-sm btnaction" title="Delete" alt="Delete" onclick="return confirm('Are you sure you want to delete?');">
                                                <i class="fas fa-trash"></i>
                                            </a>@endif
                                        </td>
                                    </tr>
                                    @endforeach @else
                                    <tr>
                                        <td colspan="8" class="text-center">No Data Found</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @include('csadmin.elements.pagination',['pagination'=>$offerData])
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	var allpromoid = [];
	var promoid = [];
	var promo_id;
	var getstatus;
	var isCheckedall;
	var isChecked;
	$(document).ready(function(){
		promoid.push('');
		$("#select-all").change(function () {
			$(".singlecheckbox").prop('checked', $(this).prop('checked'));
			$(".singlecheckbox").each(function() {
				promoid.push($(this).data('id'));
			});
		});

		$('.singlecheckbox').click(function(){
			promoid.push($(this).data('id'));
		});

	});

	document.getElementById('select-all').onclick = function() {
		var checkboxes = document.getElementsByName('select');
		for(var checkbox of checkboxes) {
			checkbox.checked = this.checked;
		}
	}

	function checkcondition(obj){
		isChecked = $(".singlecheckbox").is(":checked");
		isCheckedall = $("#select-all").is(":checked");
		getstatus = $('.getstatus').val();
		console.log(promoid);

		if(isChecked == false && isCheckedall == false) {
			alert('Please select atleast one row to proceed');
			return false;
		}

		if(getstatus == 0){
			alert('Select atleast one bulk action');
			return false;
		}
		$.post('{{route('csadmin.offersbulkaction')}}', { 'getstatus': getstatus,'promoid':promoid,'_token':_token},  function( data ) {
			if(data.status == true){
				console.log(data.status);
				$('#errormessage').show();
				$('#errormessage').html(data.message);
				setTimeout(function() {
					window.location.reload();
				}, 1000);
			}else{
			}
		});
	}
</script>
@endsection