@extends('layouts.admin')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{@$cat ? $cat->title : "Search Lesson by \"$search\""}}</h2>

    </div>
    <div class="col-lg-2">
        <br>
        <br>
		<form type="GET" action="{!! URL::route('grammar.search_lessons') !!}">
            Search: <input name='search' value='{{ @$search }}' placeholder="Search lessons" required />
        </form>
    </div>
</div>
<br>
<div id="home_categories" >
    <div class="ibox-content">
        <a href="{{ URL::route('grammar.create_lesson')}}" type="button" class="btn btn-primary btn-lg">Add new Lessons</a>
        <div class="table-responsive">
            <table class="table table-stripped  ">
                <thead>
                    <tr>
                        <th >Id</th>
                        <th >Thumb Nail </th>

                        <th >Title</th>
                        <th >Published</th>

                        <th >&nbsp;</th>
                    </tr>
                    </tr>
                </thead>
                <tbody>  
                    @php($t = "")
                    @foreach ($lessons as $lesson)
                    <tr class='{{$t == $lesson->pcat ? "" : "title" }}'>	
                        @php($t = $lesson->pcat)

                        <td > {{$lesson->id}} </td>
                        <td > {{$lesson->intro_img}} </td>

                        <td ><a href="{!! URL::route('grammar.edit_lesson', $lesson->id)!!}">{{$lesson->title}}</a> </td>
                        <td> <input class="js-switch" style="display: none;" data-switchery="true" type="checkbox"
                                    data-id="{{ $lesson->id}}"  name="status{{ $lesson->id}}" {{ $lesson->published ? 'checked' : '' }} >

                        <td style="width: 262px;">
                                    <a href="{{URL::route('grammar.list_lesson_question', $lesson->id) }}" class="btn btn-info">Questions <i>({!!$lesson->questions->count()!!})</i></a>

                            <a href="{{ URL::route('grammar.edit_lesson', $lesson->id) }}" class="btn btn-info">Update</a>
                            @if(false && !$lesson->in_drawable)
                            <a href="{{URL::route('pronunciation.delete_cat', $lesson->id) }}" class="btn btn-danger">Delete</a>
                            @endif


                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="menu_pagination"> </div>
        </div>
    </div>
</div>

@endsection
@section("content_js")
<script src="{!! asset('assets/js/plugins/dataTables/datatables.min.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/dataTables/datatables.min.css')!!}" rel="stylesheet">
<style>
    tr.title{
        background:  #2f4050;
        font-weight: bold;
        color: #fff;
        padding-top: 10px;
    }
    tr.title td{

        padding-top: 20px !important;
    }
</style>
<script>

var elem = jQuery('.js-switch').each(function (index) {
    new Switchery(this, {color: '#1AB394'});

});

</script>
@endsection
