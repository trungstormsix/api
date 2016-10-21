@extends('layouts.front')
@section('content')
<div class="row features">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="col-lg-12 text-center">
                <h1>English Grammar</h1>

            </div>
            <div class="ibox-content">
                <form method="POST" >
                        {{ csrf_field() }}

                <div>
                    <div class="feed-activity-list">
                        @if(isset($totalCorrect))                     
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Your Score: {{$totalCorrect}} / {{count($questions)}}</h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                        
                                        <a class="close-link">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <div class="flot-chart">
                                        <div class="flot-chart-pie-content" id="flot-pie-chart"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @php ($i = 1)
                        @foreach($questions as $question)
                        <div class="feed-element">                             
                            <div class="media-body ">
                                <h4>{{$i++}}. {{$question->question}}</h4>                                
                                @php ($answers = json_decode($question->answers))
                                @foreach($answers as $answer)                                
                                    @if($question->answered == $answer)
                                        @php ($error = ($question->answered == $question->correct) ? false : true)
                                    @else
                                        @php ($error = null)
                                    @endif
                                    <div   class="i-checks col-md-3 {{ $error == true ? 'has-error' : ($error === false ? 'has-success' : '') }}">
                                        <label class="control-label"> <input type="radio" {{$question->answered == $answer ? 'checked' : ''}} value="{{$answer}}" name="{{$question->id}}"> <i></i> 
                                            <span style="padding-left: 5px">{{ucfirst($answer)}}</span> </label>
                                    </div>
                                @endforeach   
                                @if(isset($answered))
                                Correct answer: {{$question->correct}}<br>
                                Why?<br>
                                    {{$question->explanation}}
                                @endif
                            </div>
                        </div>                                                     
                        @endforeach
                        
                         
                    </div>

                    <button class="btn btn-primary btn-block m-t"><i class="fa fa-check"></i> Check</button>

                </div>
                </form>
            </div>
        </div>

    </div>
    {{ $questions->links() }}
</div>
@endsection

@section('content_script')
<script type="text/javascript" src="{!! asset('assets/js/plugins/iCheck/icheck.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/js/plugins/flot/jquery.flot.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/js/plugins/flot/jquery.flot.pie.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/js/plugins/flot/jquery.flot.tooltip.min.js') !!}"></script>
   
<script>
    
    $(document).ready(function () {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
        @if(isset($totalCorrect))  
        //Flot Pie Chart
        $(function() {

            var data = [{
                label: "Correct Answer",
                data: {{$totalCorrect}},
                color: "#1ab394",
            }, {
                label: "Wrong Answer",
                data: {{ count($questions) - $totalCorrect}},
                color: "#ed5565",
            } ];

            var plotObj = $.plot($("#flot-pie-chart"), data, {
                series: {
                    pie: {
                        show: true
                    }
                },
                grid: {
                    hoverable: true
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                    shifts: {
                        x: 20,
                        y: 0
                    },
                    defaultTheme: false
                }
            });

        });
        @endif
    });
        
</script>
@endsection