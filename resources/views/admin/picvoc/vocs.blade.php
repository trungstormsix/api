@extends('layouts.admin')

@section('content')
<!-- header -->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/admin')}}">Home</a>
            </li> 
             <li>
                <a href="{{url('/admin/picvoc/cats')}}">Cats</a>
            </li>  
            <li class="active">
                <strong>List vocs - {{$cat->title}}</strong>
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
                                <th data-sort="en_us" class="sort">
                                    Voc
                                    <span class="en_us fa fa-sort"></span>
                                </th>
                                 
                                
                                <th data-sort="status" class="sort">
                                    Status
                                    <span class="status fa fa-sort"></span>
                                </th>
                                 <th data-sort="liked" class="sort">
                                    Liked
                                    <span class="liked fa fa-sort"></span>
                                </th>
                             </tr>
                        </thead>
                        <tbody>
                            @php($i = 0)
                            @foreach($vocs as $voc)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$voc->id}}</td>
                                <td><img src="http://ocodereducation.com/api/image/picvoc/{{$voc->image}}" style="width: 200px;"></td>
                                <td>
                                    <a href="{{url('admin/picvoc/voc/'.$voc->id)}}" target="_blank">
                                        <b>{{$voc->en_us}}</b>
                                    </a>
                                </td>                                
                                                               
                                <td>
                                    <span class="switchery" {!! ($voc->status == 1) ? 'style="background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;"' : '' !!}><small {!! ($voc->status == 1) ? 'style="left: 20px; transition: left 0.2s;"' : '' !!}></small></span>
                                </td>                              
                                 
                                <td>{{$voc->liked}}</td>
                            </tr>

                            @endforeach


                        </tbody>
                    </table>
                </div>
                {{$vocs->links()}}

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