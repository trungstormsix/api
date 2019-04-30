@extends('layouts.admin')

@section('content')

<style type="text/css">
    .table > tbody > tr > td {
        word-break: break-all;
    }
</style>

<div id="home_articles" >
    <div class="ibox-content">
        <div class="table-responsive">
            <div class="m-b-sm">
                <a href="{!! URL::route('image.createImg')!!}" type="button" class="btn btn-primary btn">Add new Image</a>
                @if(!$trash)
                <a href="{!! URL::route('image.listItem',['trash'=>1,'cat_id'=>$cat->id]) !!}" class="btn btn-warning btn demo1"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i> View Trash</a>
                @else
                <a href="{!! URL::route('image.listItem',['cat_id'=>$cat->id]) !!}" class="btn btn-primary  "><i class="glyphicon glyphicon-check" aria-hidden="true"></i> View All</a>
                @endif

                <select id="action" class="form-control">
                    <option value="action">Action</option>
                    <option value="0">UnPublish</option>
                    <option value="1">Publish</option>

                    @if($trash)
                    <option value="3">Delete</option>
                    @else
                    <option value="2">Trash</option>
                    @endif
                </select>
             
            </div>
            <form action="{!! URL::route('image.deleteImgs') !!}" action="" method="POST" id="delete_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="delete" id="hidden_delete" value="{{ $trash }}" />
                <table class="table">
                    <thead>
                        <tr>
                            <th class="no-sort" ><input  type="checkbox" id="checkall_ids"  class="checkbox_id"  /></th>
                            <th>#</th>
                            <th >Id</th>
                            <th >Title</th>
                            <th >Thumb</th>
                            <th >Link</th>
                            <th >Category</th>
                            <th >published</th>
                            <th >Updated_at</th>
                            <th >&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = $articles->firstItem())
                        @foreach ($articles as $post)
                        <tr>	
                            <td class="no-sort"><input type="checkbox" class="checkbox_id" name="ids[]" value="{{$post->id}}" /></td>

                            <td>{{$i++}}</td>
                            <td > {{$post->id}} </td>
                            <td > <a href="{!! URL::route('image.editImg', $post->id) !!}" class="btn btn-primary">{{$post->title}}</a> </td>
                            <td > {!!$post->thumb ? "<img width='120' src='".$post->thumb."' />" : ""!!} </td>

                            <td > <a href="{{$post->link}}" target="_blank">Link</a> </td>
                             <td > {{$post->cat->name}} </td>
                            <td>
                                <span class="switchery" {!! ($post->published == 1) ? 'style="background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;"' : '' !!}><small {!! (($post->published == 1)) ? 'style="left: 20px; transition: left 0.2s;"' : '' !!}></small></span>
                            </td>
                            <td > {{$post->updated_at}} </td>
                            <td style="width: 162px;">
                                <a href="{!! URL::route('image.editImg', $post->id) !!}" class="btn btn-info">Update</a>
                                <a href="{!! URL::route('image.deleteImg', $post->id) !!}" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="no-sort" ><input  type="checkbox" id="checkall_ids1"  class="checkbox_id"  /></th>
                            <th>#</th>
                            <th >Id</th>
                            <th >Title</th>
                            <th >Thumb</th>
                            <th >Link</th>
                            <th style="width: 25%" >Content</th>
                            <th >Category</th>
                            <th >published</th>
                            <th >Updated_at</th>
                            <th >&nbsp;</th>
                        </tr>
                    </tfoot>
                </table>
            </form>
            <div class="menu_pagination">{{$articles->links()}}</div>
        </div>
    </div>
</div>
@endsection
@section("content_js")
<script src="{!! asset('assets/js/plugins/sweetalert/sweetalert.min.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/sweetalert/sweetalert.css')!!}" rel="stylesheet">
<script>
$(document).ready(function () {
    $('#action').change(function () {

        if ($(this).val() === "" || $(this).val() == 'action') {
            return;
        }

        is_selected = false;
        $('tbody .checkbox_id').each(function () {
            if (this.checked) {
                is_selected = true;
                return;
            }
            ;
        });
        if (!is_selected) {
            swal("Warning", "Chọn bài học.", "error");
            $(this).val(0);
            return;
        }
        var that = this;
        if ($(this).val() === "2" || $(this).val() === "3") {
            swal({
                title: "Are you sure?",
                text: "Tất cả các bài học này sẽ bị {!! $trash ? 'xóa' : 'đưa vào thùng rác.' !!}!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, {{ $trash ? 'Delete them' : 'Move to trash' }}!",
                closeOnConfirm: false
            }, function (is_confirm) {
                if (is_confirm) {
                    $('#hidden_delete').val($(that).val());
                    $("#delete_form").submit();
                } else {
                    $(that).val(0);
                }
            });
            return;
        }
        if ($(this).val() === "1" || $(this).val() === "0") {
            swal({
                title: "Are you sure?",
                text: "Pubish các bài học này!",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#1ab394",
                confirmButtonText: $(this).val() === "1" ? "Yes, publish them!" : "Yes, Un-publish them!",
                closeOnConfirm: false
            }, function (is_confirm) {
                //re-publish
                if (is_confirm) {
                    $('#hidden_delete').val($(that).val());
                    $("#delete_form").submit();
                } else {
                    $(that).val(0);
                }
            });
            return;
        }
    });


    $('#checkall_ids,#checkall_ids1').click(function (event) {
        if (this.checked) {
            // Iterate each checkbox
            $('.checkbox_id').each(function () {
                this.checked = true;
            });
        } else {
            $('.checkbox_id').each(function () {
                this.checked = false;
            });
        }
    });
    $('.checkbox_id').click(function (event) {
        if (!this.checked) {
            // Iterate each checkbox
            $('#checkall_ids, #checkall_ids1').each(function () {
                this.checked = false;
            });
        }
    });


});

</script>
@endsection
