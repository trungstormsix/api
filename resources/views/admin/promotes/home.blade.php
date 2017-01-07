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
            <a href="{{url('/admin/promote/app/add')}}" class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new app"><i class="fa fa-plus"></i> Add App</a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="tabs-container">

                <div class="tabs-left">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-curent" aria-expanded="true">Published apps</a></li>
                        @foreach($groups as $group)
                        <li><a data-toggle="tab" href="#tab-{{$group->id}}" aria-expanded="true">{{$group->title}}</a></li>
                        @endforeach
                    </ul>
                    <div class="tab-content ">
                        <div id="tab-curent" class="tab-pane active">
                            <div class="panel-body">

                                <div class="table-responsive">
                                    <table id="promote_apps" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>N.o</th>
                                                <th>
                                                    Icon
                                                </th>
                                                <th>
                                                    Status
                                                </th>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    Package
                                                </th>
                                                <th>Publish Up</th>
                                                <th>Publish Down</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php ($i=1)
                                            @foreach($published_apps as $app)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <th><a href="{{url('/admin/promote/app/'.$app->id)}}" target="_blank"><img src='http://ocodereducation.com{{$app->image}}' /></a></th>
                                                <td>
                                                    <span class="switchery" {!! ($app->status == 1) ? 'style="background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;"' : '' !!}><small {!! ($app->status == 1) ? 'style="left: 20px; transition: left 0.2s;"' : '' !!}></small></span>
                                                </td>
                                                <td>{{$app->title}}</td>
                                                <td>{{$app->package}}</td>
                                                <td>{{$app->publish_up}}</td>
                                                <td>{{$app->publish_down}}</td>
                                            </tr>

                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @foreach($groups as $group)
                        <div id="tab-{{$group->id}}" class="tab-pane">
                            <div class="panel-body">

                                <div class="table-responsive">
                                    <table id="promote_apps" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>N.o</th>
                                                <th>
                                                    Icon
                                                </th>
                                                <th>
                                                    Status
                                                </th>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    Package
                                                </th>
                                                <th>Publish Up</th>
                                                <th>Publish Down</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php ($i=1)
                                            @foreach($group->apps as $app)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <th><a href="{{url('/admin/promote/app/'.$app->id)}}" target="_blank"><img src='http://ocodereducation.com{{$app->image}}' /></a></th>
                                                <td>
                                                    <span class="switchery" {!! ($app->status == 1) ? 'style="background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;"' : '' !!}><small {!! ($app->status == 1) ? 'style="left: 20px; transition: left 0.2s;"' : '' !!}></small></span>
                                                </td>
                                                <td>{{$app->title}}</td>
                                                <td>{{$app->package}}</td>
                                                <td>{{$app->publish_up}}</td>
                                                <td>{{$app->publish_down}}</td>
                                            </tr>

                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
