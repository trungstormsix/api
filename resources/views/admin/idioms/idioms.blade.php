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
                <a href="{{url('admin/idioms')}}">Idioms</a>
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
                                    Idiom
                                </th>
                                <th>
                                    Mean
                                </th>
                                <th>
                                    Example
                                </th>
                                <th>Status</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php ($i=0)
                            @foreach($idioms as $idiom)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$idiom->id}}</td>
                                <td>
                                    <a href="{{url('admin/idioms/idiom/'.$idiom->id)}}" target="_blank">
                                        <b>{{$idiom->word}}</b>
                                    </a>
                                </td>                                
                                <td>
                                    {!!$idiom->mean!!}
                                </td>
                                <td>
                                    {{$idiom->example}}
                                </td>
                                <td>
                                    <span class="switchery" {!! ($idiom->published == 1) ? 'style="background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;"' : '' !!}><small {!! ($idiom->updated == 1) ? 'style="left: 20px; transition: left 0.2s;"' : '' !!}></small></span>
                                </td>
                                <td>
                                    {{$idiom->updated}}
                                </td>
                                 
                            </tr>

                            @endforeach


                        </tbody>
                    </table>
                </div>
                {{$idioms->links()}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('search_form')
<form role="search" class="navbar-form-custom" action="{{url('admin/idioms/search')}}">
    <div class="form-group">
        <input type="text" placeholder="Search an idiom..." class="form-control" name="idiom" value="{{!empty($search) ? $search : ""}}" id="top-search">
    </div>
</form>
@endsection