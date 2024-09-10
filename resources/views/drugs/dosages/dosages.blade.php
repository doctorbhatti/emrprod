@extends('layouts.master')

@section('page_header')
    Dosages
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('root') }}"><i class="fa fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('drugs') }}">Drugs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dosages</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Card for action buttons -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                @can('add', 'App\Models\Dosage')
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDosageModal">
                        Add Dosage
                        <i class="fa fa-question-circle-o fa-lg ms-2" data-bs-toggle="tooltip" data-bs-placement="bottom"
                           title="The quantities of drugs to be taken at a time. Example: 1 pill at a time, 1 tablespoon at a time"></i>
                    </button>
                    
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFrequencyModal">
                        Add Dosage Frequency
                        <i class="fa fa-question-circle-o fa-lg ms-2" data-bs-toggle="tooltip" data-bs-placement="bottom"
                           title="How often a drug is to be taken. Example: 3 times per day, every 8 hours"></i>
                    </button>
                    
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPeriodModal">
                        Add Dosage Period
                        <i class="fa fa-question-circle-o fa-lg ms-2" data-bs-toggle="tooltip" data-bs-placement="bottom"
                           title="For how long a drug is to be taken. Example: For 3 weeks, For 6 months"></i>
                    </button>
                @endcan
            </div>

            @if(session()->has('success') || session()->has('error'))
                <div class="card-body">
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
                </div>
            @endif
        </div>

        <!-- Dosages Table -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-title">
                    Dosages
                    <i class="fa fa-question-circle-o fa-lg float-end" data-bs-toggle="tooltip" data-bs-placement="bottom"
                       title="The quantities of drugs to be taken at a time. Example: 1 pill at a time, 1 tablespoon at a time"></i>
                </h4>
            </div>
            <div class="card-body">
                <table class="table table-hover text-center" id="dosagesTable">
                    <thead>
                        <tr>
                            <th>Dosage</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dosages as $dosage)
                            <tr>
                                <td>
                                    {{ $dosage->description }}
                                    <form action="{{ route('editDosage', ['id' => $dosage->id]) }}" id="dosage{{ $dosage->id }}" method="post" hidden>
                                        @csrf
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="dosage" value="{{ $dosage->description }}">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    @can('edit', $dosage)
                                        <button class="btn btn-sm btn-primary" onclick="toggleForm('dosage{{ $dosage->id }}')">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    @endcan
                                    @can('delete', $dosage)
                                        <a class="btn btn-sm btn-danger" href="{{ route('deleteDosage', ['id' => $dosage->id]) }}">
                                            <i class="fa fa-recycle"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No dosages found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Frequencies Table -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-title">
                    Dosage Frequencies
                    <i class="fa fa-question-circle-o fa-lg float-end" data-bs-toggle="tooltip" data-bs-placement="bottom"
                       title="How often a drug is to be taken. Example: 3 times per day, every 8 hours"></i>
                </h4>
            </div>
            <div class="card-body">
                <table class="table table-hover text-center" id="frequenciesTable">
                    <thead>
                        <tr>
                            <th>Frequency</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($frequencies as $frequency)
                            <tr>
                                <td>
                                    {{ $frequency->description }}
                                    <form action="{{ route('editFrequency', ['id' => $frequency->id]) }}" id="frequency{{ $frequency->id }}" method="post" hidden>
                                        @csrf
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="frequency" value="{{ $frequency->description }}">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    @can('edit', $frequency)
                                        <button class="btn btn-sm btn-primary" onclick="toggleForm('frequency{{ $frequency->id }}')">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    @endcan
                                    @can('delete', $frequency)
                                        <a class="btn btn-sm btn-danger" href="{{ route('deleteFrequency', ['id' => $frequency->id]) }}">
                                            <i class="fa fa-recycle"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No frequencies found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Periods Table -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-title">
                    Dosage Periods
                    <i class="fa fa-question-circle-o fa-lg float-end" data-bs-toggle="tooltip" data-bs-placement="bottom"
                       title="For how long a drug is to be taken. Example: For 3 weeks, For 6 months"></i>
                </h4>
            </div>
            <div class="card-body">
                <table class="table table-hover text-center" id="periodsTable">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periods as $period)
                            <tr>
                                <td>
                                    {{ $period->description }}
                                    <form action="{{ route('editPeriod', ['id' => $period->id]) }}" id="period{{ $period->id }}" method="post" hidden>
                                        @csrf
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="period" value="{{ $period->description }}">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    @can('edit', $period)
                                        <button class="btn btn-sm btn-primary" onclick="toggleForm('period{{ $period->id }}')">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    @endcan
                                    @can('delete', $period)
                                        <a class="btn btn-sm btn-danger" href="{{ route('deletePeriod', ['id' => $period->id]) }}">
                                            <i class="fa fa-recycle"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No periods found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('drugs.dosages.modals.addDosage')
    @include('drugs.dosages.modals.addFrequency')
    @include('drugs.dosages.modals.addPeriod')

    {{-- DataTables Scripts --}}
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#dosagesTable').DataTable({
                'pageLength': 10
            });

            $('#periodsTable').DataTable({
                'pageLength': 10
            });

            $('#frequenciesTable').DataTable({
                'pageLength': 10
            });
        });

        function toggleForm(formId) {
            $('#' + formId).slideToggle();
        }
    </script>
@endsection
