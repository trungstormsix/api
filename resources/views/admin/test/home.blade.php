@extends('layouts.admin')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading m-b">
    <div class="col-lg-10">
        <h2>{{empty($title) ?  'oCoder' : $title}} Tests</h2>
       
    </div>
     
</div>
<div id="home_categories" >
    <div class="ibox-content">
        <a href="{{ url('admin/tests/create')}}" type="button" class="btn btn-primary btn-lg">Add new Test</a>
        <a href="{{ url('admin/tests/delete-all-tests/'.$cat->id)}}" type="button" class="btn btn-danger btn-lg">Delete All Tests</a>
        @php($params = json_decode($cat ? $cat->params : ""))
        @if(@$params->test_link)
        <a href="{{ url('admin/content/crawl-jp-test?cat_id='.$cat->id.'&link='.$params->test_link)}}" target="_blank" type="button" class="btn btn-primary btn-lg">Get Tests</a>
            @if(@$params->test_pages > 1)
                @for($i = 2; $i <= $params->test_pages ; $i++)
                <a href="{{ url('admin/content/crawl-jp-test?cat_id='.$cat->id.'&link='.$params->test_link."/page/".$i)}}" target="_blank" type="button" class="btn btn-primary btn-lg">Get Tests {{$i}}</a>
                @endfor
            @endif
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th >Id</th>
                    <th >Title</th>
                    <th >Published</th>
                    <th >Link</th>
                    <th >&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tests as $test)
                <tr>				
                    <td > {{$test->id}} </td>
                    <td ><a href="{!! URL::route('tests.test', $test->id)!!}">{{$test->title}}</a> </td>
                    <td > {{$test->status}} </td>
                     <td style="width: 162px;">
                         <a href="{{ $test->link }}" target="_blank" class="btn btn-info">Link</a>
                     </td>
                    <td style="width: 162px;">
                        <a href="{{ url('admin/tests/test/edit/'. $test->id) }}" class="btn btn-info">Update</a>
                        <a href="{{ url('admin/tests/delete/'. $test->id.'?cat_id='.$cat->id.'&page='.$page) }}" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="menu_pagination">{{$tests->links()}}</div>
    </div>
</div>
@endsection