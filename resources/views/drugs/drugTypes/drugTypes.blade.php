@extends('layouts.master')

@section('page_header')
    Quantity Types
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('root')}}"><i class="fa fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{route('drugs')}}">Drugs</a></li>
        <li class="breadcrumb-item active" href="#">Drug Types</li>
    </ol>
@endsection

@section('content')

    {{--Data Tables CSS--}}
    <link href="{{ asset('plugins/datatables/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css">
    {{--//Data Tables CSS--}}

    <div class="box box-primary">
        <!-- Box Header -->
        <div class="box-header with-border">
            @can('add','App\Models\DrugType')
                <button style="margin: 10px;" class="btn btn-primary btn-lg btn-flat"data-bs-toggle="modal" data-bs-target="#addDrugTypeModal">
                    Add Quantity Type
                    <i class="fa fa-question-circle-o fa-lg pull-right" data-bs-toggle="tooltip"
                       data-bs-placement="bottom" title="The measurements used to measure the available quantity (stock) of a drug. e.g., Number of 'Pills', 'Bottles', 'Litres'"></i>
                </button>
            @endcan
        </div>

        <!-- Box Body -->
        <div class="box-body">
            {{--Success Message--}}
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{--Error Message--}}
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
                    text-decoration: underline !important;
                }
            </style>

            <table class="table table-responsive table-condensed table-hover text-center" id="drugsTable">
                <thead>
                <tr>
                    <th>Quantity Type</th>
                    <th>Created By</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($drugTypes as $drugType)
                    <tr class="tableRow">
                        <td>{{ $drugType->drug_type }}</td>
                        <td>{{ $drugType->creator->name }}</td>
                        <td>
                            @can('delete', $drugType)
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteDrugTypeModal"
                                        onclick="showConfirmDelete({{ $drugType->id }}, '{{ $drugType->drug_type }}')">
                                    <i class="fa fa-recycle fa-lg" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete this?"></i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No data available</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('drugs.drugTypes.modals.addDrugType')
    @include('drugs.drugTypes.modals.confirmDelete')

    <script>
        function showConfirmDelete(drugTypeId, name) {
            document.querySelector('#confirmDeleteDrugTypeModal .modal-title').textContent = name;
            document.querySelector('#confirmDeleteDrugTypeModal form').action = "{{ url('drugs/deleteDrugType') }}/" + drugTypeId;
        }
    </script>

    {{--Data Tables Scripts--}}
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#drugsTable').DataTable({
                'pageLength': 10
            });
        });
    </script>
    {{--//Data Tables--}}
@endsection
