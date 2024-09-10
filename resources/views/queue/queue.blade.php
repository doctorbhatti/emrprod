@extends('layouts.master')

@section('page_header')
    Queue
@endsection

@section('content')
    <!-- AngularJS Scripts -->
    <script src="{{ asset('plugins/angularjs/angular.min.js') }}"></script>
    <script src="{{ asset('js/services.js') }}"></script>
    <script src="{{ asset('js/filters.js') }}"></script>
    <script src="{{ asset('js/QueueController.js') }}"></script>

    <div class="container" ng-app="HIS" ng-controller="QueueController">

        <div class="d-flex justify-content-between align-items-center mb-3">
            {{--
            Check if the user has permissions to create a new queue.
            If yes, a confirmation is taken when creating a new queue
            --}}
            @can('create', 'App\Models\Queue')
            <button class="btn btn-primary" onclick="createQueue()">
                <i class="fa fa-plus fa-lg"></i> Create New Queue
            </button>

            <script>
                function createQueue() {
                    if (window.confirm("Creating a new queue will discard all the existing queues. Are you sure?")) {
                        window.location = "{{ route('createQueue') }}";
                    }
                }
            </script>
            @endcan

            @can('close', $queue)
            <button class="btn btn-danger" onclick="closeQueue()">
                <i class="fa fa-close fa-lg"></i> Close Queue
            </button>

            <script>
                function closeQueue() {
                    if (window.confirm("Are you sure you want to close this queue? " +
                                    "You won't be able to roll back this action.")) {
                        window.location = "{{ route('closeQueue') }}";
                    }
                }
            </script>
            @endcan
        </div>

        <div class="alert-container">
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Error Message --}}
            @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-ban"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        {{-- Initialize the angular variables in a hidden field --}}
        <input type="hidden" ng-init="baseUrl='{{ url('/') }}';token='{{ csrf_token() }}';getQueue()">

        @if(empty($queue))
            {{-- Info message if there is no queue --}}
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fa fa-warning"></i> No active queues at the moment! Please create a new queue to continue.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @else
            {{-- Info message if there are patients in the queue --}}
            <div class="alert alert-info" ng-if="patients.length==0" ng-cloak>
                <i class="fa fa-info"></i> No Patient in the queue at the moment.
            </div>

            <div class="alert alert-danger" ng-show="hasError" ng-cloak>
                <i class="fa fa-ban"></i> [[error]]
            </div>

            {{-- Queue --}}
            <table class="table table-hover table-bordered text-center" ng-cloak>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="patient in patients track by $index" class="info">
                        <td class="col-md-1">[[ $index + 1 ]]</td>
                        <td class="col-md-7">
                            <a href="[[ baseUrl ]] /patients/patient/[[ patient.id ]]">
                                [[ patient.first_name ]] [[ patient.last_name ]]
                            </a>
                        </td>
                        <td class="col-md-4">
                            <button class="btn btn-sm"
                                    ng-class="{'btn-secondary': patient.type == 0, 'btn-success': patient.type == 1, 'btn-danger': patient.type == 2}"
                                    ng-mouseenter="enter([[$index]])" ng-mouseleave="leave([[$index]])"
                                    ng-click="updateQueue([[$index]])">
                                [[ patient.status ]]
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
@endsection
