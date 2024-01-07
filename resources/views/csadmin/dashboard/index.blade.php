@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <div>
        <h4 class="mb-sm-2">Dashboard</h4>
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
    <div class="page-title-right"></div>
</div>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-1 overflow-hidden">
                                        <p class="text-truncate font-size-14 mb-2">Total Orders</p>
                                        <a href="{{route('csadmin.orders')}}">
                                            <h4 class="mb-0">{{$ordercountData}}</h4>
                                        </a>
                                    </div>
                                    <div class="text-primary ms-auto">
                                        <i class="ri-store-2-line font-size-24"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-1 overflow-hidden">
                                        <p class="text-truncate font-size-14 mb-2">Total Products</p>
                                        <a href="{{route('allproduct')}}">
                                            <h4 class="mb-0">{{$productcountData}}</h4>
                                        </a>
                                    </div>
                                    <div class="text-primary ms-auto">
                                        <i class="ri-briefcase-4-line font-size-24"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-1 overflow-hidden">
                                        <p class="text-truncate font-size-14 mb-2">Total Customers</p>
                                        <a href="{{route('csadmin.customer')}}">
                                            <h4 class="mb-0">{{$usercountData}}</h4>
                                        </a>
                                    </div>
                                    <div class="text-primary ms-auto">
                                        <i class="ri-briefcase-4-line font-size-24"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="card">
                    <div class="card-body">
                        <div class="float-end d-none d-md-inline-block">
                            <div class="btn-group mb-2">
                                <button type="button" class="btn btn-sm btn-light">Today</button>
                                <button type="button" class="btn btn-sm btn-light active">Weekly</button>
                                <button type="button" class="btn btn-sm btn-light">Monthly</button>
                            </div>
                        </div>
                        <h4 class="card-title mb-4">Revenue Analytics</h4>
                        <div>
                            <div id="line-column-chart" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>
    
                    <div class="card-body border-top text-center">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="d-inline-flex">
                                    <h5 class="me-2">$12,253</h5>
                                    <div class="text-success"><i class="mdi mdi-menu-up font-size-14"> </i>2.2 %</div>
                                </div>
                                <p class="text-muted text-truncate mb-0">This month</p>
                            </div>
    
                            <div class="col-sm-4">
                                <div class="mt-4 mt-sm-0">
                                    <p class="mb-2 text-muted text-truncate"><i class="mdi mdi-circle text-primary font-size-10 me-1"></i> This Year :</p>
                                    <div class="d-inline-flex">
                                        <h5 class="mb-0 me-2">$ 34,254</h5>
                                        <div class="text-success"><i class="mdi mdi-menu-up font-size-14"> </i>2.1 %</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mt-4 mt-sm-0">
                                    <p class="mb-2 text-muted text-truncate"><i class="mdi mdi-circle text-success font-size-10 me-1"></i> Previous Year :</p>
                                    <div class="d-inline-flex">
                                        <h5 class="mb-0">$ 32,695</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <!-- <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end">
                            <select class="form-select form-select-sm">
                                <option selected>Apr</option>
                                <option value="1">Mar</option>
                                <option value="2">Feb</option>
                                <option value="3">Jan</option>
                            </select>
                        </div>
                        <h4 class="card-title mb-4">Sales Analytics</h4>
    
                        <div id="donut-chart" class="apex-charts"></div>
    
                        <div class="row">
                            <div class="col-4">
                                <div class="text-center mt-4">
                                    <p class="mb-2 text-truncate"><i class="mdi mdi-circle text-primary font-size-10 me-1"></i> Product A</p>
                                    <h5>42 %</h5>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center mt-4">
                                    <p class="mb-2 text-truncate"><i class="mdi mdi-circle text-success font-size-10 me-1"></i> Product B</p>
                                    <h5>26 %</h5>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center mt-4">
                                    <p class="mb-2 text-truncate"><i class="mdi mdi-circle text-warning font-size-10 me-1"></i> Product C</p>
                                    <h5>42 %</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="card">
                    <div class="card-body">
                        <div class="dropdown float-end">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                            </div>
                        </div>
    
                        <h4 class="card-title mb-4">Earning Reports</h4>
                        <div class="text-center">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div>
                                        <div class="mb-3">
                                            <div id="radialchart-1" class="apex-charts"></div>
                                        </div>
    
                                        <p class="text-muted text-truncate mb-2">Weekly Earnings</p>
                                        <h5 class="mb-0">$2,523</h5>
                                    </div>
                                </div>
    
                                <div class="col-sm-6">
                                    <div class="mt-5 mt-sm-0">
                                        <div class="mb-3">
                                            <div id="radialchart-2" class="apex-charts"></div>
                                        </div>
    
                                        <p class="text-muted text-truncate mb-2">Monthly Earnings</p>
                                        <h5 class="mb-0">$11,235</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Latest Orders</h5>
                    </div>
                    <div class="card-body" style="padding:0px">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:50px;text-align:center">S.No.</th>
                                        <th style="width:100px">Order Id</th>
                                        <th>Customer Details</th>
                                        <th>Total Price</th>
                                        <th style="text-align:center">Payment Method</th>
                                        <th style="text-align:center">Payment Status</th>
                                        <th>Date</th>
                                        @if(isset($permissionData) && in_array('DASHVIEW',$permissionData))
                                        <th style="text-align:center">Action</th>@endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($orderData))
                                    @php $counter = 1; @endphp
                                    @foreach($orderData as $orderVal)
                                    <tr>
                                        <td style="width:50px;text-align:center">{{$counter++}}</td>
                                        <td>@if(isset($orderVal->trans_order_number) && $orderVal->trans_order_number !=''){{$orderVal->trans_order_number}}@endif</td>
                                        <td>
                                            @if(isset($orderVal->trans_user_name) && $orderVal->trans_user_name !=''){{$orderVal->trans_user_name}}@endif
                                            <p class="mb-0 text-muted font-size-12">@if(isset($orderVal->trans_user_mobile) && $orderVal->trans_user_mobile !=''){{$orderVal->trans_user_mobile}}@endif</p>
                                        </td>
                                        <td>₹@if(isset($orderVal->trans_amt) && $orderVal->trans_amt !=''){{$orderVal->trans_amt}}@endif</td>
                                        @if(isset($orderVal->trans_method) && $orderVal->trans_method !='')
                                        <td style="text-align:center">
                                            <div class="badge badge-soft-success font-size-12">{{$orderVal->trans_method}}</div>
                                        </td>
                                        @endif
                                        <td style="text-align:center">
                                            @if(isset($orderVal->trans_payment_status) && $orderVal->trans_payment_status ==0)
                                            <span class="badge badge-soft-danger font-size-12">Pending</span>
                                            @else
                                            <span class="badge badge-soft-success font-size-12">Complete</span>
                                            @endif
                                        </td>
                                        <td>{{date('d-m-Y', strtotime($orderVal->created_at));}}
                                            <br><span style="font-size:11px">{{date("h:i:s A",strtotime($orderVal->created_at))}}</span></td>
                                            @if(isset($permissionData) && in_array('DASHVIEW',$permissionData))
                                        <td style="text-align:center">
                                            <a href="{{route('csadmin.ordersview',$orderVal->trans_order_number)}}" class="btn btn-info btn-sm btnaction" title="View" alt="View"><i class="fas fa-eye"></i></a>
                                        </td>@endif
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection