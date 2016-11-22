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
                <a href="{{url('admin/listening')}}">Idioms</a>
            </li>
            <li class="active">
                <strong>{{!empty($cat) ? $cat->title : "Search"}}</strong>
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
                                <th data-sort="liked" class="sort">
                                    Like
                                    <span class="liked fa fa-sort"></span>
                                </th>
                                <th data-sort="status" class="sort">
                                    Status
                                    <span class="status fa fa-sort"></span>
                                </th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php ($i= (($dialogs->currentPage() -1 ) * $dialogs->perPage()) + 1)
                            @foreach($dialogs as $dialog)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$dialog->id}}</td>
                                <td>
                                    <a href="{{url('admin/listening/dialog/'.$dialog->id)}}" target="_blank">
                                        <b>{{$dialog->title}}</b>
                                    </a>
                                </td>                                
                                <td>
                                    {!!$dialog->audio!!}
                                </td>
                                <td>
                                    {{$dialog->liked}}
                                </td>
                                <td>
                                    <span class="switchery" {!! ($dialog->status == 1) ? 'style="background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;"' : '' !!}><small {!! ($dialog->status == 1) ? 'style="left: 20px; transition: left 0.2s;"' : '' !!}></small></span>
                                </td>
                                <td>
                                    {{$dialog->updated}}
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
<form id="sort">
    <input class="sort_by" name="sort_by" value="{{$sort_by}}" />
    <input class="sort_dimen"  name="sort_dimen" value="{{$sort_dimen}}" />
    
</form>
</form>
@endsection

@section('search_form')
<form role="search" class="navbar-form-custom" action="{{url('admin/listening/search')}}">
    <div class="form-group">
        <input type="text" placeholder="Search an idiom..." class="form-control" name="idiom" value="{{!empty($search) ? $search : ""}}" id="top-search">
    </div>
</form>
@endsection