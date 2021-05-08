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
                                                <th data-sort="lang" class="sort">Lang
                                                    <span class="lang fa fa-sort"></span>
                                                </th>
                                                <th  data-sort="mean" class="sort">Mean 
                                                    <span class="mean fa fa-sort"></span>
                                                </th>
                                                <th  data-sort="rate" class="sort">rate
                                                    <span class="rate fa fa-sort"></span>
                                                </th>
                                                <th  data-sort="dis_like" class="sort">dis_like
                                                    <span class="dis_like fa fa-sort"></span>
                                                </th>
												<th>Word</th>
                                                <th  data-sort="updated" class="sort">updated
                                                    <span class="updated fa fa-sort"></span>
                                                </th>


                                             </tr>
                                        </thead>
                                        <tbody>
                                            @php($i = 0)
                                            @foreach($means as $mean)
                                            @if($mean)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$mean->id}}</td>
                                                <td>{{$mean->lang}}</td>
                                                <td>{{$mean->mean}}</td>
                                                <td>{{$mean->rate}}</td>
                                                <td>{{$mean->dis_like}}</td>
                                                <td><a href="{{url('admin/picvoc/voc/'.@$mean->voc->id)}}" target="_blank">
													<b>{{@$mean->voc->en_us}}</b>
												</a></td>
                                                <td>{{$mean->updated}}</td>
                                            </tr>
                                            @endif
                                            @endforeach


                                        </tbody>
                                    </table>
                </div>
				{{$means->links()}}

            </div>
        </div>
    </div>
</div>
<form id="sort" style="display: none">
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