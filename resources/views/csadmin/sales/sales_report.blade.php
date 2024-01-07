@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <div>
        <h4 class="mb-sm-2">Manage Sales Report</h4>
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
            <li class="breadcrumb-item active">Sales Report</li>
        </ol>
    </div> 
</div>
<div class="page-content">
    <div class="container-fluid">
        @include('csadmin.elements.message')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form action="{{route('csadmin.sales.salesReport')}}" method="post" accept-charset="utf-8">
                        @csrf
                        <div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
                            <div class="row align-items-center">
                                <div class="col-lg-4">
                                    <label class="form-label">From:</label>
                                    <input class="form-control" type="date" name="from" value="@php echo (!empty($aryFilterSession) && $aryFilterSession['from']!='')?$aryFilterSession['from']:''; @endphp">
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">To:</label>
                                    <input class="form-control" type="date" name="to" value="@php echo (!empty($aryFilterSession) && $aryFilterSession['to']!='')?$aryFilterSession['to']:''; @endphp">
                                </div>
                                <div class="col-lg-2">
                                    <label class="form-label">&nbsp</label>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" style="margin-left: 5px;display: flex;align-items: center;justify-content: center;float: left;">Search</button>
                                    @if(!empty($aryFilterSession))
                                        <a href="{{route('csadmin.sales.salesReportReset')}}" class="btn btn-danger waves-effect waves-light" style="margin-left: 5px;align-items: center;justify-content: center;">Reset</a>
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
                    <div class="card-header">
                        <h5>Manage Sales Report Listings</h5>
                    </div>
                    <div class="card-body" style="padding: 0px;">
                        <div class="table-responsive">
                            <table class="table mb-0 table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 50px; text-align: center;">Order ID</th>
                                        <th>Product Title/Description</th>
                                        <th>SKU</th>
                                        <th>HSN Code</th>
                                        <th>Order Date</th>
                                        <th>Item Quantity</th>
                                        <th>Taxable Value</th>
                                        <th>IGST Rate(%)</th>
                                        <th>IGST Amount(₹)</th>
                                        <th>CGST Rate(%)</th>
                                        <th>CGST Amount(₹)</th>
                                        <th>SGST Rate(%)</th>
                                        <th>SGST Amount(₹)</th>
                                        <th>Buyer Invoice NO</th>
                                        <th>Buyer Invoice Amount(₹)</th>
                                        <th>Customer's Delivery Pincode</th>
                                        <th>Customer's Delivery State</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($resOrderItems)>0) 
                                    @php $counter = 1;$taxPercentage = 0; $percentage=0; $taxableValue = 0; $invoiceAmount=0; $IGSTRate = 0;$IGSTAmount = 0;$CGSTRate = 0;$CGSTAmount = 0;$SGSTRate = 0;$SGSTAmount = 0;@endphp 
                                    @foreach($resOrderItems as $rowOrderItems)
                                    @php 
                                        $taxPercentage = $rowOrderItems->taxrate->tax_rate;
                                        if($rowOrderItems->td_state=='Rajasthan'){
                                            $percentage = $taxPercentage/2;
                                            $CGSTRate = $percentage;
                                            $CGSTAmount = ($percentage/100)*$rowOrderItems->td_item_sellling_price*$rowOrderItems->td_item_qty;
                                            $SGSTRate = $percentage;
                                            $SGSTAmount = ($percentage/100)*$rowOrderItems->td_item_sellling_price*$rowOrderItems->td_item_qty;
                                            $IGSTRate = 0;$IGSTAmount = 0;
                                            $taxableValue = ($rowOrderItems->td_item_sellling_price*$rowOrderItems->td_item_qty-$CGSTAmount-$SGSTAmount);
                                            $invoiceAmount = $rowOrderItems->td_item_sellling_price*$rowOrderItems->td_item_qty;
                                        }else{
                                            $IGSTRate = $taxPercentage;
                                            $IGSTAmount = ($taxPercentage/100)*$rowOrderItems->td_item_sellling_price*$rowOrderItems->td_item_qty;
                                            $CGSTRate = 0;$CGSTAmount = 0;$SGSTRate = 0;$SGSTAmount = 0;
                                            $taxableValue = ($rowOrderItems->td_item_sellling_price*$rowOrderItems->td_item_qty-$IGSTAmount);
                                            $invoiceAmount = $rowOrderItems->td_item_sellling_price*$rowOrderItems->td_item_qty;
                                        }
                                    @endphp
                                    <tr>
                                        <td style="width: 50px; text-align: center;" scope="row">{{$rowOrderItems->td_order_id}}</td>
                                        <td>{{$rowOrderItems->td_item_title}}</td>
                                        <td>{{$rowOrderItems->td_item_sku}}</td>
                                        <td>{{$rowOrderItems->td_item_hsn}}</td>
                                        <td>{{date('d-m-Y',strtotime($rowOrderItems->created_at))}}</td>
                                        <td>{{$rowOrderItems->td_item_qty}}</td>
                                        <td>₹{{$taxableValue}}</td>
                                        <td>{{$IGSTRate}}</td>
                                        <td>₹{{$IGSTAmount}}</td>
                                        <td>{{$CGSTRate}}</td>
                                        <td>₹{{$CGSTAmount}}</td>
                                        <td>{{$SGSTRate}}</td>
                                        <td>₹{{$SGSTAmount}}</td>
                                        <td>{{$rowOrderItems->td_invoice_no}}</td>
                                        <td>₹{{$invoiceAmount}}</td>
                                        <td>{{$rowOrderItems->td_pincode}}</td>
                                        <td>{{$rowOrderItems->td_state}}</td>
                                    </tr>
                                    @endforeach @else
                                    <tr>
                                        <td colspan="17" class="text-center">No Data Found</td>
                                    </tr>
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