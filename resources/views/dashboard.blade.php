@extends('layouts.master')


@section('page_header')
Home
@endsection


@section('content')

@can('view', 'App\Payment')
    <div class="row">
        {{--Patient Info--}}
        <div class="col-lg-3 col-6"> <!--begin::Small Box Widget 1-->
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3>{{$clinic->patients()->count()}}</h3>
                    <p>Patients Registered</p>
                </div> <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path
                        d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z">
                    </path>
                </svg>
                <a href="{{route('patients')}}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="bi bi-link-45deg"></i> </a>
            </div>
        </div>
        {{--Prescription Info--}}
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>{{$prescriptionCount}}</h3>
                    <p>Prescriptions Issue</p>
                </div>
                <svg class="small-box-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M5.5 6a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 1 0V9h.293l2 2-1.147 1.146a.5.5 0 0 0 .708.708L9 11.707l1.146 1.147a.5.5 0 0 0 .708-.708L9.707 11l1.147-1.146a.5.5 0 0 0-.708-.708L9 10.293 7.695 8.987A1.5 1.5 0 0 0 7.5 6zM6 7h1.5a.5.5 0 0 1 0 1H6z" />
                    <path
                        d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z" />
                </svg><a href="{{route('issueMedicine')}}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="bi bi-link-45deg"></i> </a>
            </div>
        </div>
        {{--Stocks--}}
        <div class="col-lg-3 col-6"> <!--begin::Small Box Widget 4-->
            <div class="small-box text-bg-danger">
                <div class="inner">
                    <h3>{{$clinic->drugs()->where('quantity', '<', 40)->count()}}</h3>
                    <p>Stocks Running Low</p>
                </div> <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path
                        d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z">
                    </path>
                </svg> <a href="{{route('stocksRunningLow')}}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="bi bi-link-45deg"></i> </a>
            </div> <!--end::Small Box Widget 4-->
        </div> <!--end::Col-->
        {{--Total Payments--}}
        <div class="col-lg-3 col-6"> <!--begin::Small Box Widget 3-->
            <div class="small-box text-bg-warning" id="totalPayments" style="visibility: hidden">
                <div class="inner">
                    <h3>Rs. {{$payments}}</h3>
                    <p>Total Payments</p>
                </div> <svg style="padding-top: 30px;" class="small-box-icon" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 16 16">
                    <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                    <path
                        d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2z" />
                </svg> <a href="{{route('payments')}}"
                    class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="bi bi-link-45deg"></i> </a>
            </div> <!--end::Small Box Widget 3-->
        </div> <!--end::Col-->

        {{--Today Payments--}}
        <div class="col-lg-3 col-6"> <!--begin::Small Box Widget 3-->
            <div class="small-box text-bg-info" id="todayPayments" style="visibility: hidden">
                <div class="inner">
                    @foreach($paymentsToday as $p)
                        <h3>Rs. {{$p->total_cost}}</h3>
                    @endforeach
                    <p>Payments For Today <br>{{$mytime}} {{$dt->toDateString()}}</p>

                    {{ logger(json_decode($paymentsToday)) }}
                </div> <svg style="padding-top: 30px;" class="small-box-icon" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                    <path
                        d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2z" />
                </svg> <a href="{{route('payments')}}"
                    class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="bi bi-link-45deg"></i> </a>
            </div> <!--end::Small Box Widget 3-->
        </div> <!--end::Col-->
    </div>
@endcan
<div class="row container-fluid" style="visibility: hidden" id="clinicStatsnew">
    <div class="box box-primary">
        <div class="box-header">
            <h4 class="box-title">Patient Visits - Last 5 months</h4>
        </div>
        <div class="box-body">
            @if(count($stats['visits']['m']) < 2)
                {{-- When no stats available to be shown --}}
                <div class="callout callout-info">
                    <h4>Not Enough Records Found!</h4>
                    <p>
                        There are no enough records to show statistic of the clinic. This will be available once
                        you start inserting prescriptions.
                    </p>
                </div>
            @else

                <div class="chart">
                    <div id="patientChart" style="height: 350px;"></div>
                    <div id="legend"></div>
                </div>
            @endif

        </div>
    </div>
</div>

@if(count($stats['visits']['m']) >= 2)
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
    // Define chart options
    var options = {
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: true
            }
        },
        series: [{
            name: 'Patient Visits',
            data: {!! json_encode($stats['visits']['c']) !!} // Ensure this returns valid data (array of numbers)
        }],
        xaxis: {
            categories: {!! json_encode($stats['visits']['m']) !!}, // Ensure this returns valid data (array of strings)
        },
        stroke: {
            curve: 'smooth', // Create smooth line curves
            width: 2 // Set line thickness
        },
        markers: {
            size: 0 // Hide markers on points
        },
        fill: {
            type: 'gradient', // Apply gradient fill
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.9,
                stops: [0, 90, 100]
            }
        },
        grid: {
            show: true, // Enable grid lines
            borderColor: '#e7e7e7',
            strokeDashArray: 4
        },
        legend: {
            show: true // Display chart legend
        },
        responsive: [{
            breakpoint: 1000,
            options: {
                chart: {
                    width: '100%',
                },
                maintainAspectRatio: true
            }
        }]
    };

    // Check if the chart element exists
    var chartElement = $("#patientChart")[0];

    if (chartElement) {
        // Initialize and render the chart
        var chart = new ApexCharts(chartElement, options);
        chart.render();
    } else {
        console.error('Element with ID "patientChart" not found');
    }
});

        </script>
            
@endif

@endsection