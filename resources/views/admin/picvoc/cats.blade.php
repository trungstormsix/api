@extends('layouts.admin')

@section('content')
<!-- header -->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-6">
        <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>            
            <li class="active">
                <strong>List Categories</strong>
            </li>
        </ol>
    </div>
      <div class="col-lg-6">
            <br>
             <form role="search" class="navbar-form-custom" action="{{url('admin/picvoc/search')}}">
                <div class="form-group">
                    <input type="text" placeholder="Search a word (laravel)..." class="searchvoc form-control" name="search" value="{{!empty($search) ? $search : ""}}"  >
                </div>
            </form>
            <br>
            <div class="pull-right tooltip-demo">
               
                <a href="{{url('/admin/picvoc/update-cat-orders')}}" class="btn btn-danger btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Refesh category ordering"><i class="fa fa-refresh"></i> Update Ordering</a>

                 <a href="{{url('/admin/picvoc/cat/create')}}" class="btn btn-success btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Create new Category"><i class="fa fa-edit"></i> Create Cat</a>
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
                                <th>Order</th>      
                                <th>Image</th>
                                <th data-sort="title" class="sort">
                                    Title
                                    <span class="title fa fa-sort"></span>
                                </th>
                                <th data-sort="parent_id" class="sort">
                                    Parent
                                    <span class="parent_id fa fa-sort"></span>

                                </th>
                                
                                <th data-sort="status" class="sort">
                                    Status
                                    <span class="status fa fa-sort"></span>
                                </th>
                                
                             </tr>
                        </thead>
                        <tbody>
                            @php($i = 0)
                            @foreach($cats as $cat)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$cat->id}}</td>
                                <td> <input  class="ordering form-control" value="{{$cat->ordering}}" data-id="{{$cat->id}}"/></td>
                                <td><img src="{{url('/')}}/../api/image/{{$cat->img}}" style="width: 100px;"></td>
                                <td>
                                    <a href="{{url('admin/picvoc/vocabularies/'.$cat->id)}}" target="_blank">
                                        <b>{{$cat->title}}</b>
                                    </a>
                                    <br>
                                    {{$cat->vocs()->count()}} vocs
                                    <p><a  class="btn btn-info" href="{{url('admin/picvoc/cat/'.$cat->id)}}" target="_blank">
                                        <i class="fa fa-edit"></i>
                                        Edit
                                    </a></p>
                                </td>                                
                                <td>
                                    {!!$cat->parent_id!!} ({!!$cat->parent_id ? $cat->parent()->title: ""!!})
                                </td>                                
                                <td>
                                    <span class="switchery" {!! ($cat->status == 1) ? 'style="background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;"' : '' !!}><small {!! ($cat->status == 1) ? 'style="left: 20px; transition: left 0.2s;"' : '' !!}></small></span>
                                    
                                </td>                              
                                 

                            </tr>

                            @endforeach


                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>
<form id="sort">
    <input class="sort_by" name="sort_by" value="{{$sort_by}}" />
    <input class="sort_dimen"  name="sort_dimen" value="{{$sort_dimen}}" />
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
     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{!!csrf_token()!!}"
        }
    });
    
    jQuery(".ordering").change(function () {
        $this = jQuery(this);
         jQuery.ajax({
            url: '{{url("admin/picvoc/update-cat-order")}}',
            type: "POST",
            dataType: 'json',
            data: {  ordering: $this.val(), id: $this.data("id")}
        }).done(function (data) {
//            jQuery(that).parent().remove();
            $this.css({"color": "green", "border": "green"})
            location.reload(true);
        })
                .fail(function () {
                    alert("error");
                });
    });
    jQuery('document').ready(function(){
     jQuery(".searchvoc").autocomplete({
            source: "{{url('admin/picvoc/auto-complete-vocs')}}",
//            select: function (event, ui) {
//                event.preventDefault();
//                var dl_id = jQuery(event.target).data('id');
//                addCat(ui.item.key, dl_id, ui.item.value)
//            },
        });
    });
</script>
<style>
    .ordering{
        max-width: 50px;
        text-align: center;
        
    }
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection