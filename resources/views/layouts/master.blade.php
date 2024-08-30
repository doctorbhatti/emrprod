<?php
$user = \App\Models\User::getCurrentUser();
?>

        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        @yield('title',$user->clinic->name)
    </title>

    <!-- ========== META TAGS ========== -->

    <link rel="shortcut" href="{{asset('favicon.ico')}}"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.min.css')}}">
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{asset('dist/css/style.css')}}">

    {{--Data Tables CSS--}}
    <link href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet" type="text/css">

    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.min.css')}}">

    {{--Date Time Picker CSS--}}
    <link rel="stylesheet" href="{{asset('plugins/datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}">

    <!-- jQuery 2.1.4 Moved to the top to load without an error-->
    <script src="{{asset('plugins/jQuery/jQuery-2.1.4.min.js')}}"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .select2-selection--single:focus, .select2-selection--single:hover{
            box-shadow: 0px 0px 5px 2px #00a65a !important;
        }
        select {
            min-width: 100px;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="{{url('/')}}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>HLC</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Healthy Life</b> Clinc</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{asset('dist/img/my_avatar.png')}}" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{$user->name}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="{{asset('dist/img/my_avatar.png')}}" class="img-circle" alt="User Image">

                                <p>
                                    {{$user->name}}
                                    <small>{{$user->clinic->name}}</small>
                                </p>
                            </li>

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{url('/settings')}}" class="btn btn-default btn-flat">Settings</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{url('logout')}}" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
    </header>

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{asset('dist/img/my_avatar.png')}}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{$user->name}}</p>
                </div>
            </div>

            <!-- search form -->
            <form action="{{route('search')}}" method="get" class="sidebar-form">
                {{csrf_field()}}
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search..." required>
                    <span class="input-group-btn">
                        <button type="submit" id="search-btn" class="btn btn-flat">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </form>
            <!-- /.search form -->

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li @if(url('/')===Request::url()) class="active" @endif>
                    <a href="{{url('/')}}">
                        <i class="fa fa-h-square"></i> <span>Home</span>
                        <i class="fa fa-question-circle-o fa-lg pull-right" data-toggle="tooltip"
                           data-placement="bottom" title="" data-original-title="Get an overview of your
                           clinic including number of patients and patient visits"></i>
                    </a>
                </li>

                <li @if(strpos(Request::url(),'patients')!=false) class="active" @endif>
                    <a href="{{url('patients')}}">
                        <i class="fa fa-wheelchair"></i> <span>Patients</span>
                        <i class="fa fa-question-circle-o fa-lg pull-right" data-toggle="tooltip"
                           data-placement="bottom" title="" data-original-title="View all the patients and
                           patient profiles, prescribe medicine and view medical records of each patient"></i>
                    </a>
                </li>

                <li @if(strpos(Request::url(),'drugs')!=false) class="active" @endif>
                    <a href="{{url('drugs')}}">
                        <i class="fa fa-stethoscope"></i> <span>Drugs</span>
                        <i class="fa fa-question-circle-o fa-lg pull-right" data-toggle="tooltip"
                           data-placement="bottom" title="" data-original-title="Manage your drug inventory.
                           The drugs in your drug inventory will be available under drugs
                       when 'prescribing medicine'. Also, you can manage each drug's stocks in this section."></i>
                    </a>
                </li>

                @can('issueMedicine','App\Models\Patient')
                    <li @if(strpos(Request::url(),'issueMedicine')!=false) class="active" @endif>
                        <a href="{{url('issueMedicine')}}">
                            <i class="fa fa-medkit"></i> <span>Issue Medicine</span>
                            <i class="fa fa-question-circle-o fa-lg pull-right" data-toggle="tooltip"
                               data-placement="bottom" title=""
                               data-original-title="An interface focused on the 'Nurse' type users.
                               All the prescriptions to be issued once the doctor has prescribed medicine to a patient
                               are visible here"></i>
                        </a>
                    </li>
                @endcan

                <li @if(strpos(Request::url(),'queue')!=false) class="active" @endif>
                    <a href="{{url('queue')}}">
                        <i class="fa fa-ambulance"></i> <span>Queue</span>
                        <i class="fa fa-question-circle-o fa-lg pull-right" data-toggle="tooltip"
                           data-placement="bottom" title=""
                           data-original-title="Interface to manage the session's patient queue.
                           You can add patients to the queue from each patient's profile.
                           Then use this interface to update the queue as patients visit the doctor"></i>
                    </a>
                </li>
                @if(auth()->user()->isAdmin())
                <li @if(strpos(Request::url(),'queue')!=false) class="active" @endif>
                    <a href="#" onclick="showTotalPayments()">
                        <i class="fa fa-gg-circle"></i> <span>Show Total Payments</span>
                        <i class="fa fa-question-circle-o fa-lg pull-right" data-toggle="tooltip"
                           data-placement="bottom" title=""
                           data-original-title="Show/Hide total payments on dashboard."></i>
                    </a>
                </li>
                @endif
                @if(auth()->user()->isAdmin())
                <li @if(strpos(Request::url(),'queue')!=false) class="active" @endif>
                    <a href="#" onclick="showTodayPayments()">
                        <i class="fa fa-gg-circle"></i> <span>Show Today's Payments</span>
                        <i class="fa fa-question-circle-o fa-lg pull-right" data-toggle="tooltip"
                           data-placement="bottom" title=""
                           data-original-title="Show/Hide today's payments on dashboard."></i>
                    </a>
                </li>
                @endif
                 @if(auth()->user()->isAdmin())
                <li @if(strpos(Request::url(),'queue')!=false) class="active" @endif>
                    <a href="#" onclick="clinicStatsNew()">
                        <i class="fa fa-gg-circle"></i> <span>Show/Hide Stats</span>
                        <i class="fa fa-question-circle-o fa-lg pull-right" data-toggle="tooltip"
                           data-placement="bottom" title=""
                           data-original-title="Show/Hide visit stats for last 5 months on dashboard."></i>
                    </a>
                </li>
                @endif
                <!-- <li @if(strpos(Request::url(),'feedback')!=false) class="active" @endif>
                    <a href="{{url('feedback')}}">
                        <i class="fa fa-thumbs-o-up"></i> <span>Feedback</span>
                        <i class="fa fa-question-circle-o fa-lg pull-right" data-toggle="tooltip"
                           data-placement="bottom" title=""
                           data-original-title="We need your feedback (complaints, suggestions) to improve this system.
                           Please be kind enough to provide us with your feedback"></i>
                    </a>
                </li> -->
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @yield('page_header')
                <small>@yield('sub_header','Healthy Life Clinic - EMR Systems')</small>
            </h1>
            @yield('breadcrumb','')
        </section>
        <!-- Main content -->
        <section class="content">
            {{--Time is shown using a script--}}
            <h4 id="timer"></h4>
            @yield('content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!--  ============================================== -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; <a href="#">Healthy Life Clinic</a>.</strong> All rights
        reserved.
    </footer>
</div><!-- ./wrapper -->

</body>

<!-- Bootstrap 3.3.5 -->
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
<!-- SlimScroll -->
<script src="{{asset('plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('plugins/fastclick/fastclick.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/app.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('dist/js/demo.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
{{--Date Range Picker--}}
<script src="{{asset('plugins/datetimepicker/build/js/moment.min.js')}}"></script>
<script src="{{asset('plugins/datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $('.birthdaypicker').datetimepicker({
            viewMode: 'years',
            format: 'YYYY/MM/DD',
            maxDate: '{{date('Y-m-d')}}',
            minDate: '{{date('Y-m-d',strtotime(date('Y-m-d').' -150 year'))}}'
        });

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'YYYY/MM/DD'
        });
    });
</script>

<script>
   $(document).ready(function () {
    //change selectboxes to selectize mode to be searchable
        $("select").select2();
        
    });
    $(document).on('keydown', '.select2', function(e) {
        if (e.originalEvent && e.which == 40) {
            e.preventDefault();
            $(this).siblings('select').select2('open');
        }
    });

    $('select').select2({
        selectOnClose: true
        
    });
</script>

{{--The script to show time on the top--}}
<script>
    $(document).ready(function () {
        setInterval(function () {
            var d = new Date();
            var hours = d.getHours();
            var mins = d.getMinutes() < 10 ? "0" + d.getMinutes() : d.getMinutes();
            var seconds = d.getSeconds() < 10 ? "0" + d.getSeconds() : d.getSeconds();
            var year = d.getFullYear();
            var tempMonths = d.getMonth() + 1;
            var month = tempMonths < 10 ? "0" + tempMonths : tempMonths;
            var day = d.getDate() < 10 ? "0" + d.getDate() : d.getDate();
            var ampm = "AM";
            if (hours > 12) {
                hours = (hours - 12);
                ampm = "PM";
            }
            var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            hours = hours < 10 ? "0" + hours : hours;
            var date = year + "/" + month + "/" + day;
            var time = hours + ":" + mins + ":" + seconds + " " + ampm;
            $("#timer").html(days[d.getDay()] + ", " + date + " | " + time);
        }, 1000);
    });
</script>
<script>
    function showTotalPayments() {
        var x = document.getElementById("totalPayments");
        if (x.style.visibility === "hidden") {
            x.style.visibility = "visible";
        } else {
            x.style.visibility = "hidden";
        }
    }

    function showTodayPayments() {
        var x = document.getElementById("todayPayments");
        if (x.style.visibility === "hidden") {
            x.style.visibility = "visible";
        } else {
            x.style.visibility = "hidden";
        }
    }
    
    function clinicStatsNew() {
        var x = document.getElementById("clinicStatsnew");
        if (x.style.visibility === "hidden") {
            x.style.visibility = "visible";
        } else {
            x.style.visibility = "hidden";
        }
    }
</script>
{{-- Google Analytics --}}
@include('analytics.googleAnalytics')
</html>
