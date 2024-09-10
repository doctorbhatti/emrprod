@extends('layouts.master')

@section('page_header')
    {{ $patient->first_name }} {{ $patient->last_name ?: '' }} (Age: {{ App\Lib\Utils::getAge($patient->dob) }})
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('root') }}"><i class="fa fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('patients') }}">Patients</a></li>
        <li class="breadcrumb-item active">{{ $patient->first_name }}</li>
    </ol>
@endsection

@section('content')
    {{-- AngularJS Scripts --}}
    <script src="{{ asset('plugins/angularjs/angular.min.js') }}"></script>
    <script src="{{ asset('js/services.js') }}"></script>
    <script src="{{ asset('js/filters.js') }}"></script>
    <script src="{{ asset('js/PrescriptionController.js') }}"></script>
    <script src="{{ asset('js/IssueMedicineController.js') }}"></script>
    <script src="{{ asset('js/RecordController.js') }}"></script>

    <div class="card">
        <!-- Card Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            {{-- Check permissions for actions --}}
            @can('edit', $patient)
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editPatientModal">
                    <i class="fa fa-edit"></i> Edit Info
                </button>
            @endcan

            @can('issueMedical', $patient)
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                    <i class="fa fa-stethoscope"></i> Issue Medical
                </button>
            @endcan

            @can('issueID', $patient)
                <a class="btn btn-primary me-2" href="{{ route('IDPreview', ['id' => $patient->id]) }}">
                    <i class="fa fa-tag"></i> Issue ID
                </a>
            @endcan

            @can('addToQueue', $patient)
                <a class="btn btn-primary me-2" href="{{ route('addToQueue', ['patientId' => $patient->id]) }}">
                    <i class="fa fa-plus"></i> Add to Queue
                    <i class="fa fa-question-circle ms-2" data-bs-toggle="tooltip" data-bs-placement="bottom"
                       title="Add this patient to the queue. You should start a queue before adding patients to it."></i>
                </a>
            @endcan
        </div>

        <!-- Card Body -->
        <div class="card-body">

            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><i class="fa fa-check"></i> Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fa fa-ban"></i> Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Nav tabs -->
            <div class="tab-content" ng-app="HIS">
                <ul class="nav nav-tabs" role="tablist">
                    @can('view', $patient)
                        <li class="nav-item">
                            <a class="nav-link @cannot('prescribeMedicine', $patient) active @endcannot"
                               id="info-tab" data-bs-toggle="tab" href="#info" role="tab" aria-controls="info">Info</a>
                        </li>
                    @endcan

                    @can('prescribeMedicine', $patient)
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile"
                               role="tab" aria-controls="profile">Prescribe Medicine</a>
                        </li>
                    @endcan

                    @can('issueMedicine', $patient)
                        <li class="nav-item">
                            <a class="nav-link" id="messages-tab" data-bs-toggle="tab" href="#messages" role="tab"
                               aria-controls="messages">Issue Medicine</a>
                        </li>
                    @endcan

                    @can('viewMedicalRecords', $patient)
                        <li class="nav-item">
                            <a class="nav-link" id="settings-tab" data-bs-toggle="tab" href="#settings" role="tab"
                               aria-controls="settings">Medical Records</a>
                        </li>
                    @endcan
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade @cannot('prescribeMedicine', $patient) show active @endcannot" id="info" role="tabpanel" aria-labelledby="info-tab">
                        @include('patients.tabs.patientInfo')
                    </div>

                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        @include('patients.tabs.prescribeMedicine')
                    </div>

                    <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                        @include('patients.tabs.issueMedicine')
                    </div>

                    <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                        @include('patients.tabs.medicalRecords')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('edit', $patient)
        @include('patients.modals.editPatient')
    @endcan
@endsection
