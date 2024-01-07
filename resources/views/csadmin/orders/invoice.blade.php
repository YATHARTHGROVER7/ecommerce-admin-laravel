<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{$site_settings->site_title}}</title>
    <link rel="shortcut icon"
        href="https://heartswithfingers.com/csadmin/public/img/uploads/settings/169095659264c9f3307c983.png" />
    <style>
        * {
            box-sizing: border-box;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #ddd;
            padding: 10px;
            word-break: break-all;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 16px;
        }

        .h4-14 h4 {
            font-size: 12px;
            margin-top: 0;
            margin-bottom: 5px;
        }

        .img {
            margin-left: "auto";
            margin-top: "auto";
            height: 30px;
        }

        pre,
        p {
            /* width: 99%; */
            /* overflow: auto; */
            /* bpicklist: 1px solid #aaa; */
            padding: 0;
            margin: 0;
        }

        table {
            font-family: arial, sans-serif;
            width: 100%;
            border-collapse: collapse;
            padding: 1px;
        }

        .hm-p p {
            text-align: left;
            padding: 1px;
            padding: 5px 4px;
        }

        td,
        th {
            text-align: left;
            padding: 8px 6px;
        }

        .table-b td,
        .table-b th {
            border: 1px solid #ddd;
        }

        th {
            /* background-color: #ddd; */
        }

        .hm-p td,
        .hm-p th {
            padding: 3px 0px;
        }

        .cropped {
            float: right;
            margin-bottom: 20px;
            height: 100px;
            /* height of container */
            overflow: hidden;
        }

        .cropped img {
            width: 400px;
            margin: 8px 0px 0px 80px;
        }

        .main-pd-wrapper {
            box-shadow: 0 0 10px #ddd;
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <section class="main-pd-wrapper" style="width: 1000px; margin: auto;">
        <div style="display: table-header-group;">
            <h4 style="text-align: center; margin: 0;">
                <b>Tax Invoice</b>
            </h4>
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td style="border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
                        <div
                            style="text-align: center; margin: auto; line-height: 1.5; font-size: 14px; color: #4a4a4a;">
                            <img src="@if(isset($settingData->logo) && $settingData->logo!=''){{env('SETTING_IMAGE')}}/{{$settingData->logo}}@else{{env('NO_IMAGE')}}@endif"
                                width="125" height="52" />
                            <p style="font-weight: bold; margin-top: 15px;">
                                GSTIN : {{$site_settings->admin_gst_no}}
                            </p>
                            <p style="font-weight: bold;">Mobile No. : <a href="tel:{{$site_settings->admin_mobile}}"
                                    style="color: #00bb07;">{{$site_settings->admin_mobile}}</a></p>
                        </div>
                    </td>
                    <td align="right" style="text-align: right; padding-left: 50px; line-height: 1.5; color: #323232;">
                        <div>
                            <h4 style="margin-top: 5px; margin-bottom: 5px;">
                                Bill to/ Ship to
                            </h4>
                            <p>NAME : {{$rowOrderData->trans_user_name}} </p>
                            <p>ADDRESS: {{$rowOrderData->trans_billing_address}} </p>
                            <p>MOBILE : {{$rowOrderData->trans_user_mobile}} </p>
                            <p>OrderID : {{$rowOrderData->trans_order_number}}</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <table class="table table-bordered h4-14" style="width: 100%; -fs-table-paginate: paginate; margin-top: 15px;">
            <thead style="display: table-header-group;">
                <tr
                    style="margin: 0; background: #fcbd021f; padding: 15px; padding-left: 20px; -webkit-print-color-adjust: exact;">
                    <td colspan="4">
                        <h3>
                            <span style="font-weight: 300; font-size: 85%; color: #626262;">Order ID:
                                {{$rowOrderData->trans_order_number}}</span>
                            <p style="font-weight: 300; font-size: 85%; color: #626262; margin-top: 7px;">
                                Delivery Estimate:
                                <span style="color: #00bb07;">-</span><br />
                            </p>
                        </h3>
                    </td>
                    <td colspan="5">
                        <p>Invoice No:- {{$rowOrderData->trans_id}}</p>
                        <p style="margin: 5px 0;">Invoice Date:-  {{date('d-m-Y',strtotime($rowOrderData->trans_datetime))}} </p>
                    </td>
                    <td colspan="4" style="width: 300px;">
                        <h4 style="margin: 0;">Sold By:</h4>
                        <p>{{$site_settings->site_title}}</p>
                        <p>
                            {{$site_settings->address}}
                        </p>
                    </td>
                </tr>

                <tr>
                    <th style="width: 250px;">
                        <h4>Item Name</h4>
                    </th>
                    <th style="width: 80px;">
                        <h4>HSN</h4>
                    </th>
                    <th style="width: 60px;">
                        <h4>QTY</h4>
                    </th>
                    <th style="width: 100px;">
                        <h4>MRP</h4>
                    </th>
                    <th style="width: 100px;">
                        <h4>Discount</h4>
                    </th>

                    <th style="width: 100px;">
                        <h4>Taxable Amount</h4>
                    </th>
                    <th style="width: 100px;">
                        <h4>IGST (%)</h4>
                    </th>
                    <th style="width: 100px;">
                        <h4>CGST (%)</h4>
                    </th>
                    <th style="width: 100px;">
                        <h4>SGST (%)</h4>
                    </th>
                    <th style="width: 80px;">
                        <h4>TOTAL</h4>
                    </th>
                </tr>
            </thead>
            <tbody>
                @php 
                $subTotal = 0; $total = 0; $discount = 0; $count = 1; $totalCgst = 0; $totalSgst = 0; $totalIgst = 0; $totaldelamt = 0; $finalTotal = 0; $cgst = 0; $sgst = 0; @endphp
                @foreach($rowOrderData->items as $value) 
                @php 
                    $total += $value->td_item_sellling_price; 
                    $cgstper = $value->td_gst/2; 
                    $cgst = $value->td_gst/2; 
                    $igst = $value->td_gst; 
                    $sgst = $value->td_gst/2;
                    $gstPerItem = $value->td_gst/2;
                    if($rowOrderData->trans_state == 'Rajasthan'){
                        $totalCgst += ($value->td_item_net_price * $cgst)/100 * $value->td_item_qty;
                        $totalSgst += ($value->td_item_net_price * $sgst)/100 * $value->td_item_qty;
                    }else{
                        $totalIgst += ($value->td_item_net_price * $value->td_gst)/100 * $value->td_item_qty;
                    }

                    $subTotal += $value->td_item_net_price * $value->td_item_qty;
                    $discount += ($value->td_item_net_price - $value->td_item_sellling_price) * $value->td_item_qty;
                    
                    $finalTotal = $subTotal - $discount - $rowOrderData->trans_coupon_dis_amt + $rowOrderData->trans_shipping_amount; 
                @endphp
                <tr>
                    <td>{{$value->td_item_title}}</td>
                    <td>{{$value->td_item_hsn}}</td>
                    <td>{{$value->td_item_qty}}</td>
                    <td>{{$rowOrderData->trans_currency}}{{number_format($value->td_item_net_price * $value->td_item_qty,2)}}</td>
                    <td>{{$rowOrderData->trans_currency}}{{number_format(($value->td_item_net_price - $value->td_item_sellling_price) * $value->td_item_qty,2)}}</td>
                    @if($rowOrderData->trans_state == 'Rajasthan')
                    <td>{{$rowOrderData->trans_currency}}{{number_format(($value->td_item_net_price - ($value->td_item_net_price * $value->td_gst)/100) * $value->td_item_qty,0)}}</td>
                    <td>-</td>
                    <td>{{$rowOrderData->trans_currency}}{{number_format(($value->td_item_net_price * $gstPerItem)/100 * $value->td_item_qty,2)}} ({{$gstPerItem}}%)</td>
                    <td>{{$rowOrderData->trans_currency}}{{number_format(($value->td_item_net_price * $gstPerItem)/100 * $value->td_item_qty,2)}} ({{$gstPerItem}}%)</td>
                    @else
                    <td>{{$rowOrderData->trans_currency}}{{number_format(($value->td_item_net_price - ($value->td_item_net_price * $value->td_gst)/100) * $value->td_item_qty,0)}}</td>
                    <td>{{$rowOrderData->trans_currency}}{{number_format(($value->td_item_net_price * $value->td_gst)/100 * $value->td_item_qty,2)}} ({{$value->td_gst}}%)</td>
                    <td>-</td>
                    <td>-</td>
                    @endif
                    <td>{{$rowOrderData->trans_currency}}{{number_format($value->td_item_net_price * $value->td_item_qty,2)}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot></tfoot>
        </table>

        <table class="hm-p table-bordered" style="width: 100%; margin-top: 30px;">
            <tr>
                <th style="vertical-align: top;">Sub Total</th>
                <td style="vertical-align: top; color: #000;">
                    <b>{{$rowOrderData->trans_currency}}{{number_format($subTotal,2)}}</b>
                </td>
            </tr>
            <!--@if($rowOrderData->trans_state == 'Rajasthan')
                <tr>
                    <th style="vertical-align: top;">CGST</th>
                    <td style="vertical-align: top; color: #000;">
                        <b>{{$rowOrderData->trans_currency}}{{number_format($totalCgst,2)}}</b>
                    </td>
                </tr> 
                <tr>
                    <th style="vertical-align: top;">SGST</th>
                    <td style="vertical-align: top; color: #000;">
                        <b>{{$rowOrderData->trans_currency}}{{number_format($totalSgst,2)}}</b>
                    </td>
                </tr> 
            @else
                <tr>
                    <th style="vertical-align: top;">IGST</th>
                    <td style="vertical-align: top; color: #000;">
                        <b>{{$rowOrderData->trans_currency}}{{number_format($totalIgst,2)}}</b>
                    </td>
                </tr> 
            @endif-->
            <tr>
                <th style="vertical-align: top;">Discount</th>
                <td style="vertical-align: top; color: #000;">
                    <b>{{$rowOrderData->trans_currency}}{{number_format($discount,2)}}</b>
                </td>
            </tr>
            
            <tr>
                <th style="vertical-align: top;">Coupon Discount</th>
                <td style="vertical-align: top; color: #000;">
                    <b>{{$rowOrderData->trans_currency}}{{number_format($rowOrderData->trans_coupon_dis_amt,2)}}</b>
                </td>
            </tr> 
            <tr>
                <th style="vertical-align: top;">Shipping</th>
                <td style="vertical-align: top; color: #000;">
                    <b>{{$rowOrderData->trans_currency}}{{number_format($rowOrderData->trans_shipping_amount,2)}}</b>
                </td>
            </tr> 
            
            <tr>
                <th style="vertical-align: top;">Total</th>
                <td style="vertical-align: top; color: #000;">
                    <b>{{$rowOrderData->trans_currency}}{{number_format($finalTotal,2)}}</b>
                </td>
            </tr>
        </table>

        <table class="hm-p table-bordered" style="width: 100%; margin-top: 30px;">
            <tr>
                <td>
                    <p>Payment Mode:</p>
                    <p>{{$rowOrderData->trans_method}}</p>
                </td> 
            </tr>
        </table>

        <table style="width: 100%;" cellspacing="0" cellspadding="0" border="0">
            <tr>
                <td>
                    <p>
                        This is computer generated invoice and hence signature is not required
                    </p>
                </td>
            </tr>
        </table>
    </section>
</body>

</html>