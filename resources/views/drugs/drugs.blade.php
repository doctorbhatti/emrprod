@extends('layouts.master')

@section('page_header')
Drugs
@endsection

@section('content')
<div ng-app="HIS" ng-controller="DrugController">
    {{-- Initialize AngularJS variables --}}
    <input type="hidden" ng-init="baseUrl='{{ url('/') }}'; token='{{ csrf_token() }}';">

    <div class="container-fluid">
        <div class="card mb-4">
            <!-- Card Header -->
            <div class="card-header d-flex justify-content-between align-items-center">
                @can('add', 'App\Models\Drug')
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDrugModal">
                        Add Drug
                        <i class="fa fa-question-circle-o fa-lg ms-2" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Add a new drug to the inventory. Added drugs will be available to be prescribed as soon as you add them"></i>
                    </button>
                @endcan

                <a class="btn btn-primary" href="{{ route('drugTypes') }}">
                    Quantity Types
                    <i class="fa fa-question-circle-o fa-lg ms-2" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="The measurements used to measure the available quantity (stock) of a drug. Example: Number of 'Pills', number of 'Bottles', 'Litres'"></i>
                </a>

                <a class="btn btn-primary" href="{{ route('dosages') }}">
                    Dosages
                    <i class="fa fa-question-circle-o fa-lg ms-2" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="A pool of dosages to be used when prescribing medicine to patients."></i>
                </a>
            </div>

            <!-- Card Body -->
            <div class="card-body table-responsive">
                {{-- Success Message --}}
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

                <style>
                    .tableRow {
                        cursor: pointer;
                    }

                    .tableRow:hover {
                        text-decoration: underline;
                    }
                </style>

                <table class="table table-hover text-center" id="drugsTable">
                    <thead>
                        <tr>
                            <th>Drug Name</th>
                            <th>Ingredient</th>
                            <th>Quantity Type</th>
                            <th>Manufacturer</th>
                            <th>Quantity</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drugs as $drug)
                            <tr class="tableRow">
                                <td onclick="window.location='{{ route('drug', ['id' => $drug->id]) }}'">{{ $drug->name }}
                                </td>
                                <td onclick="window.location='{{ route('drug', ['id' => $drug->id]) }}'">
                                    {{ $drug->ingredient ?: 'N/A' }}
                                </td>
                                <td onclick="window.location='{{ route('drug', ['id' => $drug->id]) }}'">
                                    {{ $drug->quantityType->drug_type }}
                                </td>
                                <td onclick="window.location='{{ route('drug', ['id' => $drug->id]) }}'">
                                    {{ $drug->manufacturer }}
                                </td>
                                <td onclick="window.location='{{ route('drug', ['id' => $drug->id]) }}'">
                                    {{ App\Lib\Utils::getFormattedNumber($drug->quantity) }}
                                </td>
                                <td>
                                    @can('delete', $drug)
                                        <!-- Delete Button: Separate from row's onclick event -->
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteDrugModal"
                                            onclick="showConfirmDelete({{ $drug->id }}, '{{ $drug->name }}'); event.stopPropagation();">
                                            <i class="fa fa-recycle fa-lg" data-bs-toggle="tooltip" data-placement="bottom"
                                                title="Delete this Drug?"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No drugs found</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('drugs.modals.addDrug')
@include('drugs.modals.confirmDelete')

<script>
    /**
     * Show delete confirmation modal.
     * @param {number} drugId
     * @param {string} name
     */
    function showConfirmDelete(drugId, name) {
        document.querySelector('#confirmDeleteDrugModal .modal-title').innerHTML = name;
        document.querySelector('#confirmDeleteDrugModal form').setAttribute('action', "{{ url('drugs/deleteDrug') }}/" + drugId);
    }
</script>

{{-- DataTables Scripts --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#drugsTable').DataTable({
            'pageLength': 10,
            'columnDefs': [{
                "defaultContent": "-",
                "targets": "_all"
            }]
        });
    });
</script>

{{-- AngularJS Scripts --}}
<script src="{{ asset('plugins/angularjs/angular.min.js') }}"></script>
<script src="{{ asset('js/services.js') }}"></script>
<script src="{{ asset('js/DrugController.js') }}"></script>
@endsection