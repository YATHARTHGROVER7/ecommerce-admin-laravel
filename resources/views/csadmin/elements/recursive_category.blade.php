@foreach($getcategories as $value)
<div class="form-check mb-1">
	<input class="form-check-input" type="checkbox" id="formCheck1" name="menu_categoryid[]" value="{{$value->cat_id}}"/>
	<label class="form-check-label font-size-13" for="formCheck1">{{$value->cat_name}}</label>
</div>

@if(count($value->children)>0)
@include('csadmin.elements.recursive_category',['getcategories'=>$value->children])
@endif
@endforeach