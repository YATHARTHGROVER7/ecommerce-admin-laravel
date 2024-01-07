<div class="card-footer bg-white">
    <div class="row">
        <div class="col-sm-6">
            <div> 
                <p class="mb-sm-0 mt-2">Showing {{(isset($pagination) && count($pagination)>0)?$pagination->firstItem():'0'}} to {{(isset($pagination) && count($pagination)>0)?$pagination->lastItem():'0'}} of {{$pagination->total()}} entries</p>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="float-sm-end">
                <ul class="pagination pagination-rounded mb-sm-0"> {{$pagination->links()}} </ul>
            </div>
        </div>
    </div>
</div>