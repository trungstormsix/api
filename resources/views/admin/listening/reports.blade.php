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
            <li>
                <a href="{{url('admin/listening')}}">Listening</a>
            </li>
            <li class="active">
                <strong>Reports</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <br>
        <br>
        <div class="pull-right tooltip-demo">
            <!--<a href="{{url('/admin/playlist/add')}}" class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new playlist"><i class="fa fa-plus"></i> Add Playlist</a>-->
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
                                    Title
                                </th>
                                <th>
                                    Audio
                                </th>
                                <th>
                                    Liked
                                </th>
                                <th>
                                    message
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php ($i= (($reports->currentPage() -1 ) * $reports->perPage()) + 1)
                            @foreach($reports as $report)
                            @php ($dialog = $report->dialog)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$report->id}}</td>
                                <td>
                                    <a href="{{url('admin/listening/dialog/'.$dialog->id)}}" target="_blank">
                                        <b>{{$dialog->title}}</b>
                                    </a>
                                    <span class="switchery" {!! ($dialog->status == 1) ? 'style="background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;"' : '' !!}><small {!! ($dialog->status == 1) ? 'style="left: 20px; transition: left 0.2s;"' : '' !!}></small></span>

                                </td>                                
                                <td>
                                    {!!$dialog->audio!!}
                                    <audio controls="">
                                        <source src="http://ocodereducation.com/api/audio/{!!$dialog->audio!!}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </td>
                                <td>
                                    {{$dialog->liked}}
                                </td>
                                <td>
                                    {{$report->message}}
                                </td>
                                <td>
                                    {{$report->email}}
                                </td>
                                <td>          
                                    <input class="js-switch" style="display: none;" data-switchery="true" type="checkbox"
                                           data-id="{{ $report->id}}"  name="status{{ $report->id}}" {{ $report->status ? 'checked' : '' }} >
                                </td>
                                <td>
                                    {{$report->updated}}
                                </td>

                            </tr>

                            @endforeach


                        </tbody>
                    </table>
                </div>
                {{$reports->links()}}
            </div>
        </div>
    </div>
</div>

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
        var report_id = jQuery(this).data('id');
        if (jQuery(this).is(':checked')) {
            var that = this;
            if (report_id) {
                var that = this;
                jQuery.ajax({
                    url: "{{url('admin/listening/report/fix')}}",
                    type: "GET",
                    dataType: 'json',
                    data: {report_id: report_id}
                }).done(function (data) {
//                    $(that).click();
                })
                        .fail(function () {
//                            $(that).click();
                            alert("error");
                        });
            }
        }
    })
</script>
@endsection