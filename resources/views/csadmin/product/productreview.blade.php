@extends('csadmin.layouts.master') 
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Products Reviews</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Products Reviews</li>
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
						<h5>Review List</h5> 
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0 table-striped">
								<thead>
									<tr>
										<th style="width: 68px; text-align: center;">
											<input type="checkbox" id="select-all">
										</th>
										<th style="width: 145px;">User Name</th>
										<th style="width: 200px;">Review</th>
                                        <th style="text-align:center">Rating</th>
										<th style="text-align:center">Status</th>
										<th>Date</th>
										<th style="text-align:center">Action</th>
									</tr>
								</thead>
								<tbody> @if(count($resReviewData)>0) @php $counter = 1 @endphp @foreach($resReviewData as $getdataval)
									<tr>
										<td style="width:68px;text-align:center"><input type="checkbox" class="singlecheckbox" name="select"></td>
										<td style="text-align: left; width: 50px;">{{$getdataval->pr_title}}</td>
										<td style="text-align: left; width: 50px;">{{$getdataval->pr_review}}</td>
                                        <td style="text-align: center;">
                                            @for($i=1;$i<=$getdataval->pr_rating;$i++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                            @for($i=1;$i<=5-$getdataval->pr_rating;$i++)
                                                <i class="far fa-star"></i>
                                            @endfor
                                        </td> 
                                        <td style="text-align:center"> 
                                            @if(isset($getdataval->pr_status) && $getdataval->pr_status==1) 
                                                <a href="{{route('csadmin.product.reviewstatusupdate',$getdataval->pr_id)}}"><span class="badge bg-success font-size-12">Approved</span></a> 
                                            @else 
                                                <a href="{{route('csadmin.product.reviewstatusupdate',$getdataval->pr_id)}}"><span class="badge bg-danger font-size-12">Not Approved</span></a> 
                                            @endif 
                                        </td>
                                        
                                        <td>{{date('d-m-Y', strtotime($getdataval->created_at));}}<br><span style="font-size:11px">{{date("h:i:s A",strtotime($getdataval->created_at))}}</span></td>
                                        <td class="text-center"> 
                                            <a href="{{route('csadmin.product.reviewdestroy',$getdataval->pr_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a> 
                                        </td>
									</tr> 
                                    @endforeach 
                                    @else
									<tr>
										<td colspan="11" class="text-center">No Data Found</td>
									</tr> 
                                    @endif 
                                </tbody>
							</table>
						</div>
					</div>
					@include('csadmin.elements.pagination',['pagination'=>$resReviewData])
				</div>
			</div>
		</div>
	</div>
</div>
 @endsection