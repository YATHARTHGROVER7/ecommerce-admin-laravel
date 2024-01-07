@extends('csadmin.layouts.master') @section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <div>
        <h4 class="mb-sm-2">E-Commerce Settings</h4>
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">E-Commerce Settings</a></li>
            <li class="breadcrumb-item active">Multi-Currency</li>
        </ol>
    </div>
    <div class="page-title-right"></div>
</div>
<div class="page-content">
    <div class="container-fluid">
        @include('csadmin.elements.message')
        <div class="row" id="table-striped">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Multi-Currency</h5>
                    </div>
                    <form method="post" action="{{route('csadmin.multicurrencysettings')}}">
                        @csrf
                        <!-- <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="mb-3">
                                        <label class="form-label"><b>Default Currency:</b></label>
                                        <select class="form-select" name="currency_name">
                                            <option value="INR" @php echo (isset($rowCurrencyData->currency_name) && $rowCurrencyData->currency_name == 'INR')?'selected="selected"':''@endphp>INR</option>
                                            <option value="USD" @php echo (isset($rowCurrencyData->currency_name) && $rowCurrencyData->currency_name == 'USD')?'selected="selected"':''@endphp>USD</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <hr>
                        <h6 class="mb-0" style="padding-left: 15px">Currency Options</h6>
                        <hr>-->
                        <div class="card-body" style="padding:0px">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Currency</th>
                                            <th style="text-align: center;">Rate + Exchange Rate</th>
                                            <th style="text-align: center;">Decimal</th>
                                            <th style="text-align: center;">Symbol</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="currencyTableBody">
                                             @foreach($currencyRatesData as $value)
                                            <tr>
                                                <input type="hidden" value="{{$value->cr_id}}" name="cr_id[]">
                                                <td>
                                                    <select class="form-select" name="cr_currency_select[]">
                                                        <option value="INR" @php echo (isset($value->cr_currency_select) && $value->cr_currency_select == 'INR')?'selected="selected"':''@endphp>INR</option>
                                                        <option value="USD" @php echo (isset($value->cr_currency_select) && $value->cr_currency_select == 'USD')?'selected="selected"':''@endphp>USD</option>
                                                    </select>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="number" class="form-control" name="cr_rate[]" value="{{$value->cr_rate}}">
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="number" class="form-control" name="cr_decimal[]" value="{{$value->cr_decimal}}">
                                                </td> 
                                                <td style="text-align: center;">
                                                    <input type="text" class="form-control" name="cr_symbol[]" value="{{$value->cr_symbol}}">
                                                </td> 
                                                <td style="text-align: center;">
                                                <a href="javascript:void(0)" class="btn btn-danger me-1" onclick="removeCurrencyRow(this);"><i class="fas fa-minus"></i></a>
                                                </td> 
                                            </tr> 
                                            @endforeach
                                         
                                        <tr>
                                            <input type="hidden" value="0" name="cr_id[]">
                                            <td>
                                                <select class="form-select" name="cr_currency_select[]">
                                                    <option value="INR">INR</option>
                                                    <option value="USD">USD</option>
                                                </select>
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="number" class="form-control" name="cr_rate[]" value="">
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="number" class="form-control" name="cr_decimal[]" value="">
                                            </td> 
                                            <td style="text-align: center;">
                                                <input type="text" class="form-control" name="cr_symbol[]" value="">
                                            </td> 
                                            <td style="text-align: center;">
                                                <a href="javascript:void(0)" class="btn btn-primary me-1" onclick="addMoreCurrency();"><i class="fas fa-plus"></i></a>
                                            </td> 
                                        </tr> 
                                     </tbody>
                                </table>
                            </div>
                        </div> 
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Save</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--  -->
<script>
    function addMoreCurrency() {
        const tableBody = document.getElementById('currencyTableBody');

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <select class="form-select" name="cr_currency_select[]">
                    <option value="USD">USD</option>
                </select>
            </td>
            <td style="text-align: center;">
                <input type="number" class="form-control" name="cr_rate[]" value="">
            </td>
            <td style="text-align: center;">
                <input type="number" class="form-control" name="cr_decimal[]" value="">
            </td> 
            <td style="text-align: center;">
                <input type="text" class="form-control" name="cr_symbol[]" value="">
            </td> 
            <td style="text-align: center;">
                <a href="javascript:void(0)" class="btn btn-danger me-1" onclick="removeCurrencyRow(this);"><i class="fas fa-minus"></i></a>
            </td>
        `;

        tableBody.appendChild(newRow);
    }

    function removeCurrencyRow(rowElem) {
        var condida = confirm("Are you sure you want to delete?");
        if(condida) {
            const tableBody = document.getElementById('currencyTableBody');
            if (tableBody.children.length > 1) {
                // Make sure there is at least one row before removing
                tableBody.removeChild(rowElem.closest('tr'));
            }
    }
    }
</script>
@endsection
