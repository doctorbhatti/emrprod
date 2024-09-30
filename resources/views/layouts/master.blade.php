<?php
$user = \App\Models\User::getCurrentUser();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        @yield('title', $user->clinic->name)
    </title>

    <!-- ========== META TAGS ========== -->
    <link rel="shortcut" href="{{asset('favicon.ico')}}" />

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!--=======Updated Meta Tags For AdminLTE5=-->

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="{{asset('dist/css/overlayscrollbars.min.css')}}">
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css"
        integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous">
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!-- apexcharts -->
    <link rel="stylesheet" href="{{asset('dist/css/apexcharts_3.37.1.css')}}">

    <!-- Bootstrap 5 CSS  -->
    <link rel="stylesheet" href="{{asset('dist/css/bootstrap@5.3.0.min.css')}}">

    <!-- Bootstrap 5 JS  -->
    <script src="{{asset('dist/js/bootstrap@5.3.0.min.js')}}"></script>
    <!-- Replace with Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Use a newer Ionicons version or remove if not necessary -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/5.5.2/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{asset('dist/css/style.css')}}">

    <!-- Tempus Dominus CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempus-dominus/6.2.7/css/tempus-dominus.min.css" />

    {{--Data Tables CSS--}}
    <!-- <link href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet" type="text/css"> -->

    <!-- Replace with jQuery Slim 3.6.0 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Data Tables -->
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.1.5/js/dataTables.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="{{asset('dist/css/select2.min.css')}}" rel="stylesheet" />
    <style>
        .select2-selection--single:focus,
        .select2-selection--single:hover {}

        select {
            min-width: 100px;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary"> <!--begin::App Wrapper-->
    <div class="app-wrapper"> <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body"> <!--begin::Container-->
            <div class="container-fluid"> <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> <i
                                class="bi bi-list"></i> </a> </li>
                </ul> <!--end::Start Navbar Links-->

                <!--begin::End Navbar Links-->
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <ul class="navbar-nav ms-auto"> <!--begin::Navbar Search-->
                        <!--begin::Fullscreen Toggle-->
                        <li class="nav-item"> <a class="nav-link" href="#" data-lte-toggle="fullscreen"> <i
                                    data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i> <i
                                    data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i>
                            </a> </li> <!--end::Fullscreen Toggle-->

                        <!--begin::Notification Dropdown-->
                        <li class="nav-item dropdown" id="notificationDropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="notificationToggle" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell"></i>
                                <span class="badge bg-danger" style="display: none;">0</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end"
                                aria-labelledby="notificationToggle">
                                <li class="dropdown-header">Notifications</li>
                                <!-- Notifications will be dynamically loaded here -->
                                <li><span class="dropdown-item text-muted">Loading...</span></li>
                                <!-- Button to view all notifications -->
                                <li>
                                    <a href="{{ route('admin.allNotifications') }}" class="dropdown-item text-center">
                                        View All Notifications
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--end::Notification Dropdown-->
                        <!--begin::User Menu Dropdown-->
                        <li class="nav-item dropdown user-menu"> <a href="#" class="nav-link dropdown-toggle"
                                data-bs-toggle="dropdown"> <img
                                    src="{{ $user->avatar ? asset($user->avatar) : asset('dist/img/my_avatar.png') }}"
                                    class="user-image rounded-circle shadow" alt="User Image"><span
                                    class="d-none d-md-inline">{{$user->name}}</span> </a>
                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end"> <!--begin::User Image-->
                                <li class="user-header text-bg-primary"><img
                                        src="{{ $user->avatar ? asset($user->avatar) : asset('dist/img/my_avatar.png') }}"
                                        class="rounded-circle shadow" alt="User Image">
                                    <p>
                                        {{$user->name}}
                                        <small>{{$user->clinic->name}}</small>
                                    </p>
                                </li> <!--end::User Image--> <!--begin::Menu Body-->
                                <!--begin::Menu Footer-->
                                <li class="user-footer"> <a href="{{url('/settings')}}"
                                        class="btn btn-default btn-flat">Settings</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>

                                    <a href="#" class="btn btn-default btn-flat"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Sign out
                                    </a>
                                </li> <!--end::Menu Footer-->
                            </ul>
                        </li> <!--end::User Menu Dropdown-->
                    </ul>
                </nav>
            </div>
        </nav>


        <!-- =============================================== -->

        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark"> <!--begin::Sidebar Brand-->
            <div class="sidebar-brand"> <!--begin::Brand Link-->
                <a href="{{ url('/') }}" class="brand-link">
                    <!-- Brand Image -->
                    <!-- Display clinic logo if available, otherwise fallback -->
                    <img src="{{ asset($currentLogo) }}" alt="Clinic Logo" class="brand-image opacity-75 shadow">
                    <!-- Brand Text -->
                    <span class="brand-text fw-light">{{ $clinicName }}</span>
                </a>
                <!--end::Brand Link-->
            </div> <!--end::Sidebar Brand--> <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2"> <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu"
                        data-accordion="false">
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
                        <!--Home Nav Item-->
                        <li @if(url('/') === Request::url()) class="nav-item active" @endif>
                            <a href="{{url('/')}}" class="nav-link active"> <i class="nav-icon bi bi-house"></i>
                                <p>Home</p>

                            </a>
                        </li>
                        <!--Patients Nav Item-->
                        <li @if(strpos(Request::url(), 'patients') != false) class="nav-item active" @endif> <a
                                href="{{url('patients')}}" class="nav-link active"> <i
                                    class="nav-icon bi bi-person-wheelchair"></i>
                                <p>Patients</p>
                            </a>
                        </li>
                        <!--Drugs Nav Item-->
                        <li @if(strpos(Request::url(), 'drugs') != false) class="nav-item active" @endif> <a
                                href="{{url('drugs')}}" class="nav-link active"> <i
                                    class="nav-icon bi bi-prescription"></i>
                                <p>Drugs</p>
                            </a>
                        </li>
                        <!--Issue Presciption Nav Item-->
                        @can('issueMedicine', 'App\Models\Patient')
                            <li @if(strpos(Request::url(), 'issueMedicine') != false) class="nav-item active" @endif> <a
                                    href="{{url('issueMedicine')}}" class="nav-link active"> <i
                                        class="nav-icon bi bi-file-earmark-medical"></i>
                                    <p>Issue Medicine</p>
                                </a>
                            </li>
                        @endcan
                        <!-- Queue Nav Item -->
                        <li @if(strpos(Request::url(), 'queue') != false) class="nav-item " @endif> <a
                                href="{{url('queue')}}" class="nav-link"> <i
                                    class="nav-icon bi bi-person-raised-hand"></i>
                                <p>Queue</p>
                            </a>
                        </li>

                        <li @if(strpos(Request::url(), 'feedback') != false) class="nav-item " @endif>
                            <a href="{{ url('feedback') }}" class="nav-link">
                                <i class="nav-icon bi bi-chat-square"></i>
                                <p>Support</p>
                            </a>
                        </li>

                        <!-- Show Total Payments Nav Item -->
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item" @endif> <a href="#" onclick="showTotalPayments()" class="nav-link"> <i
                                    class="nav-icon bi bi-credit-card-2-back"></i>
                                <p>Show Total Payments</p>
                            </a>
                        </li>
                        <!-- Show Today Payments -->
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item" @endif> <a href="#" onclick="showTodayPayments()" class="nav-link"> <i
                                    class="nav-icon bi bi-credit-card-2-front"></i>
                                <p>Show Today's Payments</p>
                            </a>
                        </li>
                        <!-- Show Hide/Stats -->
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item" @endif> <a href="#" onclick="clinicStatsNew()" class="nav-link"> <i
                                    class="nav-icon bi bi-bar-chart-line"></i>
                                <p>Show/Hide Stats</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- =============================================== -->
        <main class="app-main"> <!--begin::App Content Header-->
            <div class="app-content-header"> <!--begin::Container-->
                <div class="container-fluid">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <h1>
                            @yield('page_header')
                            | <sub>@yield('sub_header', 'Healthy Life Clinic - EMR Systems')</sub>
                        </h1>
                        @yield('breadcrumb', '')
                    </section>
                    <!-- Main content -->
                    <section class="content">
                        {{--Time is shown using a script--}}
                        <h4 id="timer"></h4>
                        @yield('content')
                    </section><!-- /.content -->
                </div><!-- /.content-wrapper -->
            </div>
        </main>
        <!--  ============================================== -->
        <footer class="app-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.0.0(Laravel 11)
            </div>
            <strong>Copyright &copy;
                <script>document.write(new Date().getFullYear())</script><a href="#"> Healthy Life Clinic | EMR
                    Systems</a>.
            </strong> All rights
            reserved.
        </footer>
    </div>
    <!-- Modal for displaying a single notification -->
    <div class="modal fade" id="singleNotificationModal" tabindex="-1" aria-labelledby="singleNotificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="singleNotificationModalLabel">Notification Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="notificationModalContent">
                    <!-- Notification content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{asset('dist/js/select2.min.js')}}"></script>
<!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script>
<!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script>
<!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script>
<!--end::Required Plugin(Bootstrap 5)-->
<!--begin::OverlayScrollbars Configure-->
<script>
    const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
    const Default = {
        scrollbarTheme: "os-theme-light",
        scrollbarAutoHide: "leave",
        scrollbarClickScroll: true,
    };
    document.addEventListener("DOMContentLoaded", function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (
            sidebarWrapper &&
            typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
        ) {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });
</script> <!--end::OverlayScrollbars Configure-->
<!-- apexcharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
    integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
<!-- Bootstrap 3.3.5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
<!-- SlimScroll -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"
    integrity="sha512-cJMgI2OtiquRH4L9u+WQW+mz828vmdp9ljOcm/vKTQ7+ydQUktrPVewlykMgozPP+NUBbHdeifE6iJ6UVjNw5Q=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- FastClick -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fastclick/0.6.0/fastclick.min.js"
    integrity="sha512-oljyd1wg75alHReTpDvNIQ4Yj1wZwGxxZhJhId3vr2dKY+26/r/wmMrImwDgin03+7wxyhX+adOQB/2BTvO5tQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- AdminLTE App -->
<script src="{{'/dist/js/adminlte.min.js'}}"></script>

<!-- Tempus Dominus JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempus-dominus/6.2.7/js/tempus-dominus.min.js"></script>

<!-- Moment.js (dependency) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dobPicker = new tempusDominus.TempusDominus(document.getElementById('dob-picker'), {
            display: {
                components: {
                    calendar: true,
                    date: true,
                    month: true,
                    year: true,
                    decades: true,
                    clock: false,
                    hours: false,
                    minutes: false,
                    seconds: false,
                    useTwentyfourHour: undefined
                }
            },
            restrictions: {
                maxDate: new Date(), // Restrict date to today or earlier (no future dates)
            },
        });
        dobPicker.dates.formatInput = date => moment(date).format('YYYY/MM/DD')

    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const datepicker = new tempusDominus.TempusDominus(document.getElementById('dob-picker'), {
            display: {
                components: {
                    calendar: true,
                    date: true,
                    month: true,
                    year: true,
                    decades: true,
                    clock: false,
                    hours: false,
                    minutes: false,
                    seconds: false,
                    useTwentyfourHour: undefined
                }
            },
            restrictions: {
                maxDate: new Date(), // Restrict date to today or earlier (no future dates)
            },
        });
        datepicker.dates.formatInput = date => moment(date).format('YYYY/MM/DD')

    });
</script>
<script>
    $(document).ready(function () {
        // Initialize all select elements as Select2
        $("select").select2({
            selectOnClose: true // Keeps the selection behavior on close
        });

        // Focus on the search field when Select2 is opened
        $('select').on('select2:open', function () {
            // Wait for the dropdown to render before focusing the search field
            focusSelect2SearchField();
        });

        // Open the Select2 dropdown on down arrow key press
        $(document).on('keydown', '.select2', function (e) {
            if (e.originalEvent && e.which == 40) { // Down arrow key
                e.preventDefault();
                $(this).siblings('select').select2('open');
            }
        });

        function focusSelect2SearchField() {
            // Attempt to focus the search input immediately
            let searchField = document.querySelector('.select2-container--open .select2-search__field');

            // If the field is not immediately available, use MutationObserver to detect it
            if (!searchField) {
                const observer = new MutationObserver(() => {
                    searchField = document.querySelector('.select2-container--open .select2-search__field');
                    if (searchField) {
                        searchField.focus();
                        observer.disconnect(); // Stop observing once the field is focused
                    }
                });

                // Start observing the body for changes when the dropdown opens
                observer.observe(document.body, {
                    childList: true,
                    subtree: true,
                });
            } else {
                // If available immediately, just focus
                searchField.focus();
            }
        }
    });

</script>

{{--The script to show time on the top--}}
<script>
    $(document).ready(function () {
        setInterval(function () {
            // Create a new Date object
            var d = new Date();
            // Extract hours, minutes, and seconds
            var hours = d.getHours();
            var mins = d.getMinutes() < 10 ? "0" + d.getMinutes() : d.getMinutes();
            var seconds = d.getSeconds() < 10 ? "0" + d.getSeconds() : d.getSeconds();
            var year = d.getFullYear();
            // Get the month and ensure it's formatted correctly
            var tempMonths = d.getMonth() + 1;
            var month = tempMonths < 10 ? "0" + tempMonths : tempMonths;
            // Get the day of the month and ensure it's formatted correctly
            var day = d.getDate() < 10 ? "0" + d.getDate() : d.getDate();

            // Set AM or PM based on the current hour
            var ampm = hours >= 12 ? "PM" : "AM";
            // Convert hours to 12-hour format
            hours = hours % 12;
            // Display '12' instead of '0' when hours are 0
            hours = hours ? hours : 12;
            // Add a leading zero to hours if necessary
            hours = hours < 10 ? "0" + hours : hours;

            // Array of weekday names
            var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            // Format the date and time strings
            var date = year + "/" + month + "/" + day;
            var time = hours + ":" + mins + ":" + seconds + " " + ampm;
            // Display the formatted date and time inside the #timer element
            $("#timer").html(days[d.getDay()] + ", " + date + " | " + time);
        }, 1000); // Update every second
    });

</script>

<!-- The script to hide/show the total & daily payments, also for clinic stats -->
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
<script>
    $(document).ready(function () {
        // Fetch notifications function
        function fetchNotifications() {
            $.ajax({
                url: '{{ route('admin.fetchNotifications') }}',
                method: 'GET',
                success: function (data) {
                    var dropdownMenu = $('#notificationDropdown .dropdown-menu');
                    var badge = $('.badge.bg-danger');
                    dropdownMenu.empty();

                    if (data.length > 0) {
                        var unreadNotifications = data.filter(n => !n.read_status);
                        var readNotifications = data.filter(n => n.read_status);

                        // Unread notifications section
                        if (unreadNotifications.length > 0) {
                            dropdownMenu.append('<li class="dropdown-header">New Notifications</li>');
                            unreadNotifications.slice(0, 5).forEach(function (notification) {
                                dropdownMenu.append(`
                                <li>
                                    <a class="dropdown-item notification-item" href="#" data-id="${notification.id}">
                                        ${notification.message}
                                        <br>
                                        <small class="text-muted">${new Date(notification.created_at).toLocaleString()}</small>
                                    </a>
                                </li>
                            `);
                            });
                        } else {
                            dropdownMenu.append('<li><span class="dropdown-item text-muted">No new notifications</span></li>');
                        }

                        // Read notifications section
                        if (readNotifications.length > 0) {
                            dropdownMenu.append('<li class="dropdown-header">Opened</li>');
                            readNotifications.forEach(function (notification) {
                                dropdownMenu.append(`
                                <li>
                                    <a class="dropdown-item" href="#" data-id="${notification.id}">
                                        ${notification.message}
                                        <br>
                                        <small class="text-muted">${new Date(notification.created_at).toLocaleString()}</small>
                                    </a>
                                </li>
                            `);
                            });
                        }

                        // Add the View All Notifications button
                        dropdownMenu.append(`
                        <li>
                            <a href="{{ route('admin.allNotifications') }}" class="dropdown-item text-center">
                                View All Notifications
                            </a>
                        </li>
                    `);

                        // Update badge count
                        var unreadCount = unreadNotifications.length;
                        if (unreadCount > 0) {
                            badge.text(unreadCount).show();
                        } else {
                            badge.hide();
                        }

                    } else {
                        dropdownMenu.append('<li><span class="dropdown-item text-muted">No notifications</span></li>');
                        badge.hide();
                    }
                },
                error: function () {
                    $('#notificationDropdown .dropdown-menu').html('<li><span class="dropdown-item text-muted">Failed to load notifications</span></li>');
                }
            });
        }

        // Initial fetch
        fetchNotifications();

        // Periodically fetch notifications
        setInterval(fetchNotifications, 30000);

        // Only show dropdown first, prevent modal opening
        $('#notificationToggle').on('click', function (e) {
            e.stopPropagation(); // Prevent modal trigger
        });

        // Open modal with notification content when clicking a notification
        $(document).on('click', '.notification-item', function (e) {
            e.preventDefault();
            var notificationId = $(this).data('id');
            var notificationItem = $(this);

            $.ajax({
                url: `/mark-notification-read/${notificationId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function () {
                    // Mark the clicked notification as read
                    notificationItem.closest('li').remove();

                    // Fetch all notifications again to update badge and dropdown
                    fetchNotifications();

                    // Load notification content into modal
                    $.ajax({
                        url: `/get-notification/${notificationId}`, // Ensure this matches the route in web.php
                        method: 'GET',
                        success: function (data) {
                            // Check for a URL in the message and convert it to a clickable link
                            var messageContent = data.message.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>');
                            $('#notificationModalContent').html(`
                            <p>${messageContent}</p>
                            <p><small>${new Date(data.created_at).toLocaleString()}</small></p>
                        `);
                            $('#singleNotificationModal').modal('show'); // Show modal with notification content
                        },
                        error: function () {
                            alert('Failed to load notification details.');
                        }
                    });
                },
                error: function () {
                    alert('Failed to mark notification as read.');
                }
            });
        });
    });

</script>

{{-- Google Analytics --}}
@include('analytics.googleAnalytics')

</html>