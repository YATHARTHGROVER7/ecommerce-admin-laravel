@extends('csadmin.layouts.master') 
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Reviews</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Reviews</li>
		</ol>
	</div> 
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		 
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Review Listing</h5> 
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0 table-striped">
								<thead>
									<tr>
										<th style="width: 68px; text-align: center;">
											<input type="checkbox" id="select-all">
										</th>
										<th style="width: 50px; text-align: center;">S.No.</th>
										<th style="width: 145px;">User Name</th>
										<th style="width: 145px;">Product Name</th>
										<th style="width: 200px;">Review</th>
                                        <th style="text-align:center">Rating</th>
                                        @if(isset($permissionData) && in_array('REVIEWSTATUS',$permissionData))
										<th style="text-align:center">Status</th>@endif
										<th>Date</th>
									   <!-- <th style="text-align:center">Action</th>  -->
									</tr>
								</thead>
								<tbody> @if(count($productReview)>0) @php $counter = 1 @endphp @foreach($productReview as $key => $value)
									<tr>
										<td style="width:68px;text-align:center"><input type="checkbox" class="singlecheckbox" name="select"></td>
										<td style="width: 50px; text-align: center;">{{$counter++}}</td>
										<td style="text-align: left; width: 50px;">{{$value->pr_title}}</td>
										<td style="text-align: left; width: 50px;">{{$value->products->product_name}}</td>
										<td style="text-align: left; width: 50px;">{{$value->pr_review}}</td>
                                        <td style="text-align: center;">
                                            @for($i=1;$i<=$value->pr_rating;$i++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                            @for($i=1;$i<=5-$value->pr_rating;$i++)
                                                <i class="far fa-star"></i>
                                            @endfor
                                        </td> 
                                        @if(isset($permissionData) && in_array('REVIEWSTATUS',$permissionData))
                                        <td style="text-align:center"> 
                                            @if(isset($value->pr_status) && $value->pr_status==1) 
                                                <span class="badge bg-success font-size-12">Approved</span>
                                            @else 
                                                <a href="{{route('csadmin.reviews.reviewStatus',$value->pr_id)}}"><span class="badge bg-danger font-size-12">Not Approved</span></a> 
                                            @endif 
                                        </td>@endif
                                        
                                        <td>{{date('d-m-Y', strtotime($value->created_at));}}<br><span style="font-size:11px">{{date("h:i:s A",strtotime($value->created_at))}}</span></td>
                                       <!-- <td class="text-center"> 
                                            <a href="{{route('csadmin.product.reviewdestroy',$value->pr_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a> 
                                        </td> -->
									</tr> 
                                    @endforeach 
                                    @else
									<tr>
										<td colspan="8" class="text-center">No Data Found</td>
									</tr> 
                                    @endif 
                                </tbody>
							</table>
						</div>
					</div>
					@include('csadmin.elements.pagination',['pagination'=>$productReview])
				</div>
			</div>
		</div>
	</div>
</div>
 @endsection