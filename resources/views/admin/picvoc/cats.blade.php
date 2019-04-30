@extends('layouts.admin')

@section('content')
<!-- header -->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
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
    <div class="col-lg-2">
        <br>
        <br>
 
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
                                <td><img src="http://ocodereducation.com/api/image/picvoc/cat/{{$cat->img}}" style="width: 100px;"></td>
                                <td>
                                    <a href="{{url('admin/picvoc/vocabularies/'.$cat->id)}}" target="_blank">
                                        <b>{{$cat->title}}</b>
                                    </a>
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
    jQuery(".ordering").change(function () {
        $this = jQuery(this);
         
        jQuery.ajax({
            url: '{{url("admin/picvoc/cats")}}',
            type: "GET",
            dataType: 'json',
            data: {  ordering: $this.val()}
        }).done(function (data) {
//            jQuery(that).parent().remove();
            $this.css({"color": "green", "border": "green"})
        })
                .fail(function () {
                    alert("error");
                });
    })
</script>
@endsection