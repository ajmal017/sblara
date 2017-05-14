@extends('layouts.metronic.default')

@section('content')
<div class="row margin-bottom-20">
<div class="col-lg-6 col-md-3 col-sm-6 col-xs-12">
<h4>{{$instrumentInfo->name}}</h4>
</div>
<div class="col-lg-6 col-md-3 col-sm-6 col-xs-12">
@include('html.instrument_list_bs_select',['bs_select_id'=>'instruments'])
</div>

</div>
<div class="mt-element-step">
    <div class="row step-thin margin-bottom-20">

        <div class="col-md-3 bg-grey mt-step-col ">
            <div class="mt-step-number bg-white font-grey">O</div>
            <div class="mt-step-title uppercase font-grey-cascade">Open</div>
            <div class="mt-step-content font-grey-cascade">{{$lastTradeInfo->open_price}}</div>
        </div>
        <div class="col-md-3 bg-grey mt-step-col">
            <div class="mt-step-number bg-white font-grey">H</div>
            <div class="mt-step-title uppercase font-grey-cascade">High</div>
            <div class="mt-step-content font-grey-cascade">{{$lastTradeInfo->high_price}}</div>
        </div>
        <div class="col-md-3 bg-grey mt-step-col">
            <div class="mt-step-number bg-white font-grey">L</div>
            <div class="mt-step-title uppercase font-grey-cascade">Low</div>
            <div class="mt-step-content font-grey-cascade">{{$lastTradeInfo->low_price}}</div>
        </div>

        <div class="col-md-3 bg-grey done mt-step-col active">
            <div class="mt-step-number bg-white font-grey">C</div>
            <div class="mt-step-title uppercase font-grey-cascade">Close</div>
            <div class="mt-step-content font-grey-cascade">{{$lastTradeInfo->close_price}}</div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat2 ">
            <div class="display">
                <div class="number">
                    <h3 class="{{fontCss($lastTradeInfo->close_price)}}">
                        <span data-counter="counterup" data-value="{{$lastTradeInfo->close_price}}">{{$lastTradeInfo->close_price}} ({{$lastTradeInfo->close_price}})</span>
                        <small class="{{fontCss($lastTradeInfo->close_price)}}"></small>
                    </h3>
                    <small>Ltp </small>
                </div>
                <div class="icon">
                    <i class="icon-pie-chart"></i>
                </div>
            </div>
            <div class="progress-info">
                <div class="progress">
                                        <span style="width: 76%;" class="progress-bar progress-bar-success {{barCss($lastTradeInfo->close_price)}}">
                                            <span class="sr-only">76% progress</span>
                                        </span>
                </div>
                <div class="status">
                    <div class="status-title"> Change </div>
                    <div class="status-number">{{$lastTradeInfo->close_price}}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat2 ">
            <div class="display">
                <div class="number">
                    <h3 class="{{fontCss($currentVolDiffThenYday)}}">
                        <span data-counter="counterup" data-value="1349">{{$lastTradeInfo->total_volume}} </span></span>
                    </h3>
                    <small>Trade Volume</small>
                </div>
                <div class="icon">
                    <i class="icon-like"></i>
                </div>
            </div>
            <div class="progress-info">
                <div class="progress">
                                        <span style="width: 85%;" class="progress-bar progress-bar-success {{barCss($currentVolDiffThenYday)}}">
                                            <span class="sr-only">85% change</span>
                                        </span>
                </div>
                <div class="status">
                    <div class="status-title"> Compare with yesterday </div>
                    <div class="status-number"> {{$currentVolDiffThenYday}}
                        ({{$currentVolDiffThenYdayPer}}%)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat2 ">
            <div class="display">
                <div class="number">
                    <h3 class="{{fontCss($avgVolCompareWithToday)}}">
                        <span data-counter="counterup" data-value="{{$avgVol}}">{{$avgVol}}</span>
                    </h3>
                    <small>Avg Vol(1 week)</small>
                </div>
                <div class="icon">
                    <i class="icon-basket"></i>
                </div>
            </div>
            <div class="progress-info">
                <div class="progress">
                                        <span style="width: 45%;" class="progress-bar progress-bar-success {{barCss($avgVolCompareWithToday)}}">
                                            <span class="sr-only">45% grow</span>
                                        </span>
                </div>
                <div class="status">
                    <div class="status-title"> Compare with current volume </div>
                    <div class="status-number"> {{$avgVolCompareWithToday}}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat2 ">
            <div class="display">
                <div class="number">
                    <h3 class="{{fontCss($lastTradeInfo->close_price)}}">
                        <span data-counter="counterup" data-value="276">{{$lastTradeInfo->total_volume_difference}}</span>
                    </h3>
                    <small>Last minute traded vol</small>
                </div>
                <div class="icon">
                    <i class="icon-user"></i>
                </div>
            </div>
            <div class="progress-info">
                <div class="progress">
                                        <span style="width: 57%;" class="progress-bar progress-bar-success {{barCss($lastTradeInfo->close_price)}}">
                                            <span class="sr-only">56% change</span>
                                        </span>
                </div>
                <div class="status">
                    <div class="status-title"> change </div>
                    <div class="status-number"> 57% </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <div class="clearfix"></div>


 {{--@include('html.instrument_list_bs_select',['bs_select_id'=>'instruments'])--}}
    <div class="row">

        <div class="col-md-6">
            <!-- BEGIN Portlet PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-graph font-yellow-casablanca"></i>
								<span class="caption-subject bold font-yellow-casablanca uppercase">
								Minute chart </span>
                        <span class="caption-helper">Watch every minute's price movement</span>
                    </div>
                    <div class="tools">
                        <a href="" class="collapse">
                        </a>

                        </a>
                        <a href="" class="remove">
                        </a>
                    </div>

                </div>
                <div class="portlet-body">

                    @include('block.minute_chart', array('instrument_id' => $instrumentInfo->id))

                </div>
            </div>
            <!-- END Portlet PORTLET-->
        </div>
        <div class="col-md-6">
            <!-- BEGIN Portlet PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-graph font-yellow-casablanca"></i>
								<span class="caption-subject bold font-yellow-casablanca uppercase">
								Sector chart </span>
                        <span class="caption-helper">Watch every minute's sector movement</span>
                    </div>
                    <div class="tools">
                        <a href="" class="collapse">
                        </a>

                        </a>
                        <a href="" class="remove">
                        </a>
                    </div>

                </div>
                <div class="portlet-body">

                     @include('block.sector_minute_chart', array('instrument_id' => $instrumentInfo->id))

                </div>
            </div>
            <!-- END Portlet PORTLET-->
        </div>
    </div>

@include('block.market_depth_single', array('instrument_id' => $instrumentInfo->id))


<div class="row">

        <div class="col-md-6">
            <!-- BEGIN Portlet PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-graph font-yellow-casablanca"></i>
								<span class="caption-subject bold font-yellow-casablanca uppercase">
								News chart </span>
                        <span class="caption-helper">Watch every minute's price movement</span>
                    </div>
                    <div class="tools">
                        <a href="" class="collapse">
                        </a>

                        </a>
                        <a href="" class="remove">
                        </a>
                    </div>

                </div>
                <div class="portlet-body">

                    @include('block.news_chart', array('instrument_id' => $instrumentInfo->id))

                </div>
            </div>
            <!-- END Portlet PORTLET-->
        </div>
        <div class="col-md-6">
            <!-- BEGIN Portlet PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-graph font-yellow-casablanca"></i>
								<span class="caption-subject bold font-yellow-casablanca uppercase">
								Sector detail chart </span>
                        <span class="caption-helper">Eagle view of the sector</span>
                    </div>
                    <div class="tools">
                        <a href="" class="collapse">
                        </a>

                        </a>
                        <a href="" class="remove">
                        </a>
                    </div>

                </div>
                <div class="portlet-body">
                     @include('block.market_frame_old_site', array('height' =>'400','base' =>'total_value','instrument_id' => $instrumentInfo->id))

                </div>
            </div>
            <!-- END Portlet PORTLET-->
        </div>
    </div>
<div class="row">
        <div class="col-md-6">
            <!-- BEGIN Portlet PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-graph font-yellow-casablanca"></i>
								<span class="caption-subject bold font-yellow-casablanca uppercase">
								News</span>
                        <span class="caption-helper">News by tag</span>
                    </div>
                    <div class="tools">
                        <a href="" class="collapse">
                        </a>

                        </a>
                        <a href="" class="remove">
                        </a>
                    </div>

                </div>
                <div class="portlet-body">
                     {{--@include('block.news_box', array('instrument_id' => $instrumentInfo->id,'limit' =>30))--}}
                     @include('block.news_box_today')
                     {{--@include('block.news_box', array('instrument_id' => array(12,13),'limit' =>5))--}}
                </div>
            </div>
            <!-- END Portlet PORTLET-->
        </div>
    </div>


@endsection

@push('scripts')
<script type="text/javascript">

   $( "#instruments" ).change(function() {
      var insId = $("#instruments").selectpicker("val");
      var url = "{{ url('/company-details/') }}/"+insId;
      window.location = url;
     });


</script>
@endpush