@extends('layouts.admin')

@section('content')
<!-- header -->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-5">
        <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('admin/story/cats')}}">Story Cats</a>
            </li>
            <li class="active">
                <strong>{{!empty($cat) ? $cat->title : "Search"}}</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-7">
        <br>
        <div class="pull-right tooltip-demo">
            <a href="http://ocodereducation.com/admin/stories/create" target="blank" class="btn btn-primary btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Create Story"><i class="fa fa-plus-square"></i> Create</a>

        <a href="{{url('http://localhost/laravel/api/admin/story/en/cat/'.$cat->id)}}" class="btn btn-info btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Download all audios of the cat to localhost to create videos"><i class="fa fa-download"></i> Download Audios</a>
        <a href="{{url('http://localhost/laravel/api/admin/story/video?cat_id='.$cat->id)}}" class="btn btn-success btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Create all videos to upload to youtube"><i class="fa fa-create"></i> Create Videos</a>

        <a href="{{url('admin/story/update-story-orders?cat_id='.$cat->id)}}" class="btn btn-danger btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Refesh Story ordering"><i class="fa fa-refresh"></i> Refresh order</a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel-body">
                    Refresh: <input class="js-switch" id="refresh_update_order" style="display: none;" data-switchery="true" type="checkbox"  />

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>N.o</th>                                
                                <th>Id</th>  
                                <th data-sort="ordering" class="sort">Order<span class="ordering fa fa-sort"></span></th>      
                                <th data-sort="title" class="sort">
                                    Title
                                    <span class="title fa fa-sort"></span>
                                </th>
                                <th>
                                    Audio
                                </th>
                                <th data-sort="liked" class="sort">
                                    Like
                                    <span class="liked fa fa-sort"></span>
                                </th>
                                <th data-sort="status" class="sort">
                                    Status
                                    <span class="status fa fa-sort"></span>
                                </th>
                                 @if($cat)
                                
                                @endif
                                <th data-sort="updated" class="sort">Updated  <span class="updated fa fa-sort"></span></th>
                                 <th data-sort="has_sub" class="sort">
                                    Has Sub
                                    <span class="has_sub fa fa-sort"></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php ($i= (($dialogs->currentPage() -1 ) * $dialogs->perPage()) + 1)
                            @foreach($dialogs as $dialog)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$dialog->id}}</td>
                                <td> <input name="ordering_{{$dialog->id}}_{{rand(1,10)}}" ca class="ordering form-control" value="{{$dialog->ordering}}" data-id="{{$dialog->id}}"/></td>

                                <td>
                                    <a href="{{url('admin/story/story/'.$dialog->id)}}" target="_blank">
                                        <b>{{$dialog->title}}</b>
                                    </a>
                                </td>                                
                                <td>
                                    {!!$dialog->audio!!}<br>
                                    @if($dialog->duration) <a href="{{url('admin/story/duration/'.$dialog->id)}}" target="_blank">
                                        <b>Get Duration</b>
                                    </a>
                                    {!! floor($dialog->duration/60). ":" .  (($dialog->duration) % 60) !!}
                                    @else
                                    <a href="{{url('admin/story/duration/'.$dialog->id)}}" target="_blank">
                                        <b>Get Duration</b>
                                    </a>
                                    @endif
                                </td>
                                <td>
                                    {{$dialog->liked}}
                                </td>
                                <td>
                                    <span class="switchery" {!! ($dialog->status == 1) ? 'style="background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;"' : '' !!}><small {!! ($dialog->status == 1) ? 'style="left: 20px; transition: left 0.2s;"' : '' !!}></small></span>
                                </td>
                                 @if($cat)
                                
                                 @endif
                                <td>
                                    {{$dialog->updated}}
                                </td>
<td>
                                    {{$dialog->has_sub}}
                                </td>
                            </tr>

                            @endforeach


                        </tbody>
                    </table>
                </div>
                {{$dialogs->links()}}
            </div>
        </div>
    </div>
</div>
<form id="sort" style="display: none">
    <input class="sort_by" name="sort_by" value="{{$sort_by}}" />
    <input class="sort_dimen"  name="sort_dimen" value="{{$sort_dimen}}" />
    <input name='search' value='{{ @$search }}' placeholder="Search"  />
</form>
@endsection

@section('search_form')
<form role="search" class="navbar-form-custom" action="{{url('admin/listening/search')}}">
    <div class="form-group">
        <input type="text" placeholder="Search a lesson..." class="form-control" name="idiom" value="{{!empty($search) ? $search : ""}}" id="top-search">
    </div>
</form>
@endsection

@section('content_js')
<script>
    
     var elem = jQuery('.js-switch').each(function (index) {
        new Switchery(this, {color: '#1AB394'});

    });
    
    jQuery('.js-switch').change(function () {
        var voc_id = jQuery(this).data('id');
          var status = jQuery(this).is(':checked');
        if(!voc_id){
            
            if(jQuery(this).prop('id') == 'refresh_update_order'){
//                alert(jQuery(this).prop('id'));
                isRefresh = status;
            }
            return;
        }
    });
    
 $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{!!csrf_token()!!}"
        }
    });
  var isRefresh = false;  
 jQuery(".ordering").change(function () {
        $this = jQuery(this);
         jQuery.ajax({
            url: '{{url("admin/story/update-story-order")}}',
            type: "POST",
            dataType: 'json',
            data: {  ordering: $this.val(), id: $this.data("id")}
        }).done(function (data) {
//            jQuery(that).parent().remove();
            $this.css({"color": "green", "border": "green"})
            if(isRefresh)
            location.reload(true);
        })
                .fail(function () {
                    alert("error");
                });
    });
</script>
<style>
    .ordering {
        max-width: 70px;
        margin: 0 auto;
        text-align: center;
    }
</style>
@endsection