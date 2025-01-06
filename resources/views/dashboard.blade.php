@extends('layouts.admin_layout')

@section('title')
    {{translate_title('Employees')}}
@endsection
@section('content')
    <style>
        #datatable-buttons td{
            overflow-wrap: break-word;
        }
    </style>

    <div class="content mt-4">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="accordion custom-accordion" id="custom-accordion-one">
                @foreach($daily_data as $key => $dailyDate)
                    <div class="card mb-0">
                        <div class="card-header" id="headingNine">
                            <h5 class="m-0 position-relative">
                                <a class="custom-accordion-title text-reset {{$key == 0?'collapsed':''}} d-block"
                                   data-bs-toggle="collapse" href="#collapseNine_{{$key}}"
                                   aria-expanded="{{$key == 0?true:false}}" aria-controls="collapseNine">
                                    {{$dailyDate['employee']['full_name']}} <i
                                        class="mdi mdi-chevron-down accordion-arrow"></i>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseNine_{{$key}}" class="collapse {{$key == 0?'show':''}}"
                             aria-labelledby="headingFour"
                             data-bs-parent="#custom-accordion-one">
                            <div class="card-body">
                                <div class="d-flex overflow-auto width_100_percent">
                                    <div class="width_540_pixel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-around width_100_percent">
                                                    <h4>{{$dailyDate['break_time']}}</h4>
                                                    <h4 class="header-title mt-0 mb-3">{{translate_title('Yesterday\'s break time')}}</h4>
                                                </div>
                                                <div class="chartjs-chart">
                                                    <canvas id="pie_{{$key}}" height="300"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end col-->
                                    <div class="width_540_pixel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-around width_100_percent">
                                                    <h4>{{$dailyDate['formatted_this_month_break_time']}}</h4>
                                                    <h4 class="header-title mt-0 mb-3">{{translate_title('This month break time')}}</h4>
                                                </div>
                                                <div class="chartjs-chart">
                                                    <canvas id="pie_monthly_{{$key}}" height="300"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end col-->
                                    <div class="width_540_pixel">
                                        <div class="card">
                                            <div class="card-body">
                                                <table class="tablesaw table mb-0" data-tablesaw-mode="stack">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">{{translate_title('Month')}}</th>
                                                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">{{translate_title('Break time')}}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($dailyDate['months'] as $daily_date_month)
                                                        <tr>
                                                            <td>{{$daily_date_month['name']}}</td>
                                                            <td>{{$daily_date_month['break_time']}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div><!-- end col-->
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- end row -->

        </div> <!-- container-fluid -->

    </div> <!-- content -->

    <!-- Chart JS -->
    <script src="{{ asset('libs/chart.js/Chart.bundle.min.js') }}"></script>
    <script>
        "use strict";
        !function(s){
            function r(){}
            r.prototype.respChart=function(r,o,a,e){
                Chart.defaults.global.defaultFontColor="#6c7897",
                Chart.defaults.scale.gridLines.color="rgba(108, 120, 151, 0.1)";
                // Canvas kontekstini olish va tekshirish
                var t = r.get(0); // DOM elementni olish
                if (t) {
                    var context = t.getContext("2d");
                    if (!context) {
                        console.error("getContext('2d') null qaytardi! 2D grafik ishlamayapti.");
                        return;
                    }
                } else {
                    console.error("Canvas elementi topilmadi!");
                    return;
                }

                // Ota elementni olish
                var n = s(r).parent();
                function i(){
                    r.attr("width",
                        s(n).width());
                    switch(o){
                        case"Line":new Chart(t,{
                            type:"line",data:a,options:e
                        });
                            break;
                        case"Pie":new Chart(t,{
                            type:"pie",data:a,options:e
                        });
                            break;
                    }
                }
                s(window).resize(i),i()
            },r.prototype.init=function(){
                @foreach($daily_data as $key => $dailyDate)
                    this.respChart(s("#pie_{{$key}}"),"Pie",{
                        labels:["{{translate_title('Working hours')}}","{{translate_title('Break hours')}}"],
                        datasets:[{
                            data:["{{$dailyDate['nine_hour']}}", "{{$dailyDate['interval_day']}}"], backgroundColor:["#13C12D","#F0090D"],
                            hoverBackgroundColor:["#13C12D","#F0090D"],hoverBorderColor:"#fff"
                        }]
                    });
                    this.respChart(s("#pie_monthly_{{$key}}"),"Pie",{
                        labels:["Desktops","Tablets"],
                        datasets:[{
                            data:["{{$dailyDate['month_time']}}", "{{$dailyDate['this_month_break_time_seconds']}}"], backgroundColor:["#13C12D","#F0090D"],
                            hoverBackgroundColor:["#13C12D","#F0090D"],hoverBorderColor:"#fff"
                        }]
                    });
                @endforeach
            },
                s.ChartJs=new r,s.ChartJs.Constructor=r
        }
        (window.jQuery),window.jQuery.ChartJs.init();

    </script>
    <!-- Init js -->
@endsection
