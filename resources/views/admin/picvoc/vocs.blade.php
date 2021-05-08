@extends('layouts.admin')

@section('content')
<!-- header -->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-6">
        <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/admin')}}">Home</a>
            </li> 
             <li>
                <a href="{{url('/admin/picvoc/cats')}}">Cats</a>
            </li>  
            <li class="active">
                <strong>List vocs - {{$cat->title}}</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-6">
        <br>
         <form role="search" class="navbar-form-custom" action="{{url('admin/picvoc/search')}}">
                <div class="form-group">
                    <input type="text" placeholder="Search a word (laravel)..." class="searchvoc form-control" name="search" value="{{!empty($search) ? $search : ""}}"  >
                </div>
            </form>
        <br>
        <div class="pull-right tooltip-demo">
                <a href="http://ocodereducation.com/admin/picvoc/vocs/{{$cat->id}}" class="btn btn-primary btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Create Vocabulary">Cake Vocs</a>

                <a href="http://ocodereducation.com/admin/picvoc/createvoc" class="btn btn-info btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Create Vocabulary"><i class="fa fa-pencil"></i> Create Voc</a>
                <a href="{{url('admin/picvoc/update-voc-orders?cat_id='.$cat->id)}}" class="btn btn-danger btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Refesh Vocabulary ordering"><i class="fa fa-refresh"></i> Refresh order</a>

        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div>
                    Refresh: <input class="js-switch" id="refresh_update_order" style="display: none;" data-switchery="true" type="checkbox"  />
                    <form style="float: right" class="form-inline" action="http://ocodereducation.com/admin/picvoc/search">
                        <div class="form-group">
                            <input class="form-control searchvoc ui-autocomplete-input" type="text" name="search" value="" placeholder="Search Vocabulary..." autocomplete="off">

                            <button class="btn btn-small btn-success"><i class="glyphicon glyphicon-search"> </i>&nbsp;</button>
                        </div>
                    </form>
                    </div>
            <div class="panel-body">

                <div class="table-responsive">
                    
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>N.o</th>                                
                                <th>Id</th>      
                                <th data-sort="ordering" class="sort">Order<span class="title fa fa-sort"></span></th>      

                                <th>Size</th>
                                <th>Image</th>
                                <th data-sort="en_us" class="sort">
                                    Voc
                                    <span class="en_us fa fa-sort"></span>
                                </th>
 
                                <th data-sort="status" class="sort">
                                    Status
                                    <span class="status fa fa-sort"></span>
                                </th>
                                 <th data-sort="liked" class="sort">
                                    Liked
                                    <span class="liked fa fa-sort"></span>
                                </th>
                                 <th  >
                                Cats
                                </th>
                                
                             </tr>
                        </thead>
                        <tbody>
                            @php($i = 0)
                            @foreach($vocs as $voc)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$voc->id}}</td>
                                <td> <input  class="ordering form-control" value="{{$voc->ordering}}" data-id="{{$voc->id}}"/></td>

                                <td id="image_data_{{$voc->id }}">@if($voc->img_data)
                                    <b>{{$voc->img_size}}</b><br>
                                     {{$voc->img_data[0]}} x {{$voc->img_data[1]}} px<br>  {{$voc->img_data['mime']}}<br>                   
                                @endif
                                </td>
                                <td> 
                                    <img id="image_{{$voc->id }}" src="{{url('/')}}/../api/image/picvoc/{{$voc->image}}" style="width: 200px;">
                                      
                                </td>
                                <td>
                                    <a href="{{url('admin/picvoc/voc/'.$voc->id)}}" target="_blank">
                                        <b>{{$voc->en_us}}</b>
                                    </a>
                                      
                                      
                                    <p class="well" style="padding: 3px; margin-top: 5px;">
                                          <b> ({{$voc->en_us_type}})  {{$voc->en_us_mean}}</b><br>
                                      {!! $voc->en_us_ex !!}
                                      </p>
                                    <br>
                                    <a target="_blank" href="https://www.google.com/search?q={{$voc->en_us}}&tbm=isch" class="btn btn-sm btn-success">Google Img</a>
                                  
                                    <input name="image" data-id="{{$voc->id}}" class="image-link" placeholder="Paste Image Link"/>
                                    <img height="27" style="display: none" id="loading_{{$voc->id }}"
                                         src="{{'../template/img/loading_spinner.gif'}}" />
                                </td>                                
                                                               
                                <td>
                               
                               <input class="js-switch" style="display: none;" data-switchery="true" type="checkbox"
                                      data-id="{{ $voc->id}}"  name="status{{ $voc->id}}" {{ $voc->status == 1 ? 'checked' : '' }} /><br>
                               <p class="alert alert-warning" style="margin-top: 5px; margin-bottom: 0px;">
                                  US: {{$voc->en_us_pr}} <audio  id="audio_us_{{ $voc->id}}">
                                        <source src="{{url('/')}}/../api/audio/picvoc/{{$voc->en_us_audio}}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                               <button  class="btn btn-sm btn-primary"  onclick="playAudio('audio_us_{{ $voc->id}}')" type="button">Play {{$voc->en_us_pr}}</button>
                            </p>
                             
                               <p class="alert alert-info" style="margin-top: 5px; margin-bottom: 0px;" >
                                UK: {{$voc->en_uk_pr}} <audio  id="audio_uk_{{ $voc->id}}">
                                        <source src="{{url('/')}}/../api/audio/picvoc/{{$voc->en_uk_audio}}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                 <button class="btn btn-sm btn-primary" onclick="playAudio('audio_uk_{{ $voc->id}}')" type="button">Play {{$voc->en_uk_pr}}</button>
                               </p>
                                </td>                              
                                 
                                <td>{{$voc->liked}}<br>
                                <a  class="btn btn-sm btn-primary" href="{{url('admin/picvoc/update-pron-by-id/'.$voc->id)}}" target="_blank">
                                        Crawl pron
                                    </a>
                                    <a onclick="return confirm('Are you sure ? \nAll Mean, Examples, Type... will be lost.')" target="_blank" href="{{url('admin/picvoc/get-oxford-word/'.$voc->id)}}" class="btn btn-sm btn-success">Crawl Mean</a>
                                <a onclick="return confirm('Vocabulary will be deleted and can not be store\n Are you Sure.')" target="_blank" href="{{url('admin/picvoc/delete-voc/'.$voc->id)}}" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                                <td>
                                    @foreach($voc->cats as $tmp_cat)
                                    @if($tmp_cat->id != $cat->id)
                                    <a class="btn btn-info" href="{{url('admin/picvoc/vocabularies/'.$tmp_cat->id)}}" target="_blank">
                                        <b>{{$tmp_cat->title}}</b>
                                    </a>
                                    @else
                                    <b class="btn btn-default">{{$tmp_cat->title}}</b>
                                    @endif
                                    @endforeach
                                </td>
                            </tr>

                            @endforeach


                        </tbody>
                    </table>
                </div>
                {{$vocs->links()}}

            </div>
        </div>
    </div>
</div>
<form id="sort">
    <input class="sort_by" name="sort_by" value="{{$sort_by}}" />
    <input class="sort_dimen"  name="sort_dimen" value="{{$sort_dimen}}" />
</form>
 @endsection

@section('search_form')
<form role="search" class="navbar-form-custom" action="{{url('admin/listening/search')}}">
    <div class="form-group">
        <input type="text" placeholder="Search a lesson..." class="searchvoc form-control" name="idiom" value="{{!empty($search) ? $search : ""}}" id="top-search">
    </div>
</form>
@endsection

@section('content_js')
<script>
     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{!!csrf_token()!!}"
        }
    });
  var isRefresh = false;  
 jQuery(".ordering").change(function () {
        $this = jQuery(this);
         jQuery.ajax({
            url: '{{url("admin/picvoc/update-voc-order")}}',
            type: "POST",
            dataType: 'json',
            data: {  ordering: $this.val(), id: $this.data("id")}
        }).done(function (data) {
//            jQuery(that).parent().remove();
            $this.css({"color": "green", "border": "green"})
            if(isRefresh)
            location.reload(true);
        })
                .fail(function () {
                    alert("error");
                });
    });
    
function playAudio(id) { 
    var x = document.getElementById(id); 
  x.play(); 
} 

     var elem = jQuery('.js-switch').each(function (index) {
        new Switchery(this, {color: '#1AB394'});

    });
    
    jQuery('.js-switch').change(function () {
        var voc_id = jQuery(this).data('id');
          var status = jQuery(this).is(':checked');
        if(!voc_id){
            
            if(jQuery(this).prop('id') == 'refresh_update_order'){
//                alert(jQuery(this).prop('id'));
                isRefresh = status;
            }
            return;
        }
      
         
            var that = this;
            if (voc_id) {
                var that = this;
                jQuery.ajax({
                    url: "{{url('admin/picvoc/ajax/update-status')}}",
                    type: "GET",
                    dataType: 'json',
                    data: {voc_id: voc_id, status: status}
                }).done(function (data) {
//                    $(that).click();
                })
                        .fail(function () {
                            $(that).click();
                            alert("error");
                        });
            }
       
    })
     
     
      jQuery('.image-link').change(function () {
        var id = jQuery(this).data("id");
        var that = this;
        var link = jQuery(this).val();
        if (link == '') {
            return;
        }
        jQuery("#loading_" + id).show();
        jQuery("#loading_" + id).attr("src", "/template/images/loading.gif");

        jQuery.ajax({
            type: "post",
            url:  '{{url("admin/picvoc/update-voc-image-link")}}',
            dataType: 'json',
            data: {"id": id, "link": link},
            success: function (response) {
//                alert(response.status)
                if (response.status == true) {
                    jQuery("#image_" + id).attr("src", response.src);
                    jQuery("#image_data_"+ id).html(response.size);
                } else {

                }
                jQuery("#loading_" + id).attr("src",  "/template/images/success.png");

                hideLoading(id);
                jQuery(that).val('');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                jQuery(that).val('');
                jQuery("#loading_" + id).attr("src",  "/template/images/error.png");

                hideLoading(id);  
            }
        });
        return false;
    })
     function hideLoading(id) {
        jQuery("#loading_" + id).delay(500).hide(500);

    }
    
      function copyToClipboard(element) {

        var $temp = $("<input>")
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $(element).css("color", "green");
        setTimeout(function () {
            $(element).css("color", "red");
        }, 3000);
        $temp.remove();
    }
    jQuery(".click_copy").on("click", function () {
        copyToClipboard(this);
    })
    jQuery('document').ready(function(){
     jQuery(".searchvoc").autocomplete({
            source: "{{url('admin/picvoc/auto-complete-vocs')}}",
//            select: function (event, ui) {
//                event.preventDefault();
//                var dl_id = jQuery(event.target).data('id');
//                addCat(ui.item.key, dl_id, ui.item.value)
//            },
        });
    });
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection