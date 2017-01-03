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


        </ol>
    </div>
    <div class="col-lg-2">
        <br>
        <br>
        <div class="pull-right tooltip-demo">
            <a href="{{url('/admin/playlist/add')}}" class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new playlist"><i class="fa fa-plus"></i> Add Playlist</a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox-content inspinia-timeline">
                test                

            </div>
        </div>
    </div>
</div>

@endsection

@section('content_js')
<!--<script>
    jQuery.ajax({url: "api/auth/create-user", type: "POST", dataType: 'json', data: {username: "trung1", email: "trungstormsix1@gmail.com", password: "test"}}).done(function (data) {
        jQuery(that).parent().remove();
    }).fail(function () {
        alert("error");
    });
</script>-->
<!--<script>
    jQuery.ajax({url: "funny/like", type: "POST", dataType: 'json', data: {id: 1026,   like: -1}}).done(function (data) {
        jQuery(that).parent().remove();
    }).fail(function () {
        alert("error");
    });
</script>-->
@endsection