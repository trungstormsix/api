@extends('layouts.admin')

@section('content')
<!-- header -->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Search Word</h2>

    </div>
    <div class="col-lg-2">
        <br>
        <br>
           <form type="GET" action="{!! url('admin/dictionary/search') !!}">
             <input name='search' value='{{ @$search }}' placeholder="Search for word..." required class='form-control'/>
         </form>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel-body">
                @if($words)
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
                                    UK
                                </th>
                                <th>
                                    US
                                </th>
                               
                                <th>Count</th>
                                 <th>Updated</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php ($i= (($words->currentPage() -1 ) * $words->perPage()) + 1)
                            @foreach($words as $idiom)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$idiom->id}}</td>
                                <td>
                                    <b>{{$idiom->word}}</b>
                                </td>    
                                <td>
                                    @if($idiom->en_us_pro)
                                        <span class="click2copy">/{!!$idiom->en_uk_pro!!}/</span>
                                    @else
                                        <a href="{{url('admin/dictionary/delete/'.$idiom->id )}}" target="_blank">Delete</a>
                                    @endif
                                </td>
                                <td>
                                    @if($idiom->en_us_pro)
                                    <span class="click2copy">/{!!$idiom->en_us_pro!!}/</span>
                                    @else
                                    <a href="{{url('api/looked-up/crawl?word_id='.$idiom->id )}}" target="_blank">Crawl</a>
                                    @endif
                                </td>
                                <td>
                                    {{$idiom->count}}
                                </td>
 
                                <td>
                                    {{$idiom->updated_at}}
                                </td>

                            </tr>

                            @endforeach


                        </tbody>
                    </table>
                </div>
                {{$words->links()}}
                @endif
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
    .click2copy{
        display: inline-block;
        border: 1px solid #cacaca;
        cursor: copy;
        padding: 4px;
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
jQuery(".click2copy").click(function(){    
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(this).text()).select();
    document.execCommand("copy");
    $temp.remove();
//    $(this).style("color","#ff0000");
})
</script>
@endsection