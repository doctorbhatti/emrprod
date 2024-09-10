@extends('layouts.master')

@section('page_header')
    Patients
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Patients</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                    <span>Add Patient</span>
                </button>
            </div>

            <div class="card-body">
                {{-- Success Message --}}
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-check"></i> Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Error Message --}}
                @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-ban"></i> Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <style>
                    .table-hover tbody tr:hover {
                        text-decoration: underline !important;
                        cursor: pointer;
                    }
                </style>

                <table class="table table-bordered table-hover" id="patientsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Contact No.</th>
                            <th>Address</th>
                            <th>Age</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Rows will be populated by DataTables --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('patients.modals.addPatient')
    @include('patients.modals.confirmDelete')

    <script>
        function showConfirmDelete(patientId, name) {
            document.querySelector('#confirmDeletePatientModal .modal-title').innerHTML = name;
            document.querySelector('#confirmDeletePatientModal form').setAttribute("action", "{{ url('patients/deletePatient') }}/" + patientId);
        }

        $(document).ready(function () {
            var table = $('#patientsTable').DataTable({
                'pageLength': 10,
                'processing': true,
                'serverSide': true,
                'ajax': '{{ route("listPatients") }}',
                'order': [[1, 'asc']],
                'columnDefs': [
                    {
                        'targets': [0],
                        'visible': false,
                        'searchable': false
                    }
                ]
            });

            $('#patientsTable').on('click', 'tbody tr', function () {
                var data = table.row(this).data();
                window.location.href = "{{ url('patients/patient') }}/" + data[0];
            });
        });
    </script>
@endsection
