@extends('layouts.admin')

@section('content')
<!-- header -->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Looked up</h2>

    </div >
	<div class="col-lg-2">
	<br>
	
		<a href="{{url('admin/dictionary/word/get-delete-mean')}}" class="btn btn-info">View Delete Mean</a>
		<br>
	@if($lang == "delete")
		<a href="{{url('admin/dictionary/word/delete-mean-search')}}" class="btn btn-danger">Delete Mean</a>
	@endif
            <a href="{{url('admin/dictionary/delete-for-recrawl')}}" class="btn btn-info">Delete 100 error Mean</a>

	
	</div>
    <div class="col-lg-2">
        <br>
        
        <div class="pull-right tooltip-demo">
            <select name="lang" id="selectLang" data-placeholder="Chọn Ngôn Ngữ..." class="chosen-select" style="width:350px;" tabindex="2">
                <option value=""  >All</option>
                @foreach($langs as $l)
                <option value="{{$l->lang}}" {{$l->lang == $lang ? "selected='selected'" : "" }}>{{$l->lang." (".$l->total.")"}}</option>
                @endforeach
            </select>
            <br>
             <form style="margin-top: 5px;" type="GET" action="{!! url('admin/dictionary/search') !!}">
				 <input name='search'   placeholder="Search for word..." required class='form-control'/>
			 </form>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>N.o</th>                                
                                <th>Id</th>                                
                                <th>
                                    Word
                                </th>
                                <th>
                                    Lang
                                </th>

                                <th>
                                    Count
                                </th>

                                <th>Created</th>
                                <th>Updated</th>
																<th>Option</th>

								
                            </tr>
                        </thead>
                        <tbody>
                            @php ($i= (($words->currentPage() -1 ) * $words->perPage()) + 1)
                            @foreach($words as $word)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$word->id}}</td>
                                <td>
                                    <a href="{{url("admin/dictionary/edit/".$word->id)}}" target="_blank"><b>{{$word->word->word}}</b></a>                                </td>                                

                                <td>
                                    {!!$word->lang!!}
                                </td>
                                <td>
                                    {!!$word->count!!}
                                </td>
                                <td>
                                    {{$word->created_at}}
                                </td>
                                <td>
                                    {{$word->updated_at}}
                                </td>       
<td>
									<a href="{{url('admin/dictionary/word/delete-mean/'.$word->id)}}" target="_blank" class="btn btn-small btn-danger delete-mean">Delete Mean</a>
								</td>								
                            </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
                {{$words->links()}}
            </div>
        </div>
    </div>
</div>
@endsection

@section("content_js")
<script src="{!! asset('assets/ckeditor/ckeditor.js') !!}"></script>
<script src="{!! asset('assets/js/plugins/chosen/chosen.jquery.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/chosen/chosen.css')!!}" rel="stylesheet">
<style>
	.delete-mean:visited{
		background: green !important;
	}
</style>
<script>
$('#selectLang').change(function() {
var url = ("{{url('/admin/dictionary/')}}/" + $(this).val());
window.location = url;
});
var elem = document.querySelector('.js-switch');
var switchery = new Switchery(elem, {color: '#1AB394'});
var config = {
'.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
        '.chosen-select-width'     : {width:"95%"}
}
for (var selector in config) {
$(selector).chosen(config[selector]);
}
</script>
@endsection