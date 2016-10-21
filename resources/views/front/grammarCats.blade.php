@extends('layouts.front')
@section('content')
<div class="row features">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="col-lg-12 text-center">
                <h1>English Grammar</h1>

            </div>
            <div class="ibox-content">

                <div>
                    <div class="feed-activity-list">
                        @foreach($cats as $cat)
                        <div class="feed-element">                             
                            <div class="media-body ">
                                <small class="pull-right text-navy">5h ago</small>
                                <h2>{{$cat->title}}</h2>
<!--                                <div class="well">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                    Over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                                </div>-->
                                <div class="actions">
                                    <a class="btn btn-xs btn-white"><i class="fa fa-thumbs-up"></i> Like </a>
                                    <a class="btn btn-xs btn-white"><i class="fa fa-heart"></i> Love</a>
                                    <a class="btn btn-xs btn-white" href="{{url('/grammar/test/'.$cat->id)}}"><i class="fa fa-heart"></i> Test</a>
                                </div>
                            </div>
                        </div>                                                     
                        @endforeach
                    </div>

                    <button class="btn btn-primary btn-block m-t"><i class="fa fa-arrow-down"></i> Show More</button>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection