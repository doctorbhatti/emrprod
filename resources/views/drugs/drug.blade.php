@extends('layouts.master')

@section('page_header')
    {{ $drug->name }}
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('root') }}"><i class="fa fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('drugs') }}">Drugs</a></li>
        <li class="breadcrumb-item active">{{ $drug->name }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card mb-4">
            <!-- Card Header -->
            <div class="card-header d-flex justify-content-between align-items-center">
                {{-- Check whether the user has permissions to access these tasks --}}
                @can('edit', $drug)
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDrugModal">
                    <i class="fa fa-edit"></i> Edit Info
                </button>
                @endcan

                @can('add', 'App\Models\Stock')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockModal">
                    <i class="fa fa-plus"></i> Add Stock
                </button>
                @endcan
            </div>
            <!-- Card Body -->
            <div class="card-body">
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><strong>Drug Name</strong></label>
                            <p>{{ $drug->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Ingredient</strong></label>
                            <p>{{ $drug->ingredient ?: 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Manufacturer</strong></label>
                            <p>{{ $drug->manufacturer }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Quantity</strong></label>
                            <p>{{ App\Lib\Utils::getFormattedNumber($drug->quantity) }} {{ $drug->quantityType->drug_type }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><strong>Created By</strong></label>
                            <p>{{ $drug->creator->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Created At</strong></label>
                            <p>{{ App\Lib\Utils::getTimestamp($drug->created_at) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Stocks --}}
        <div class="card">
            <!-- Card Header -->
            <div class="card-header">
                <h4 class="card-title">Recent Stocks</h4>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <table class="table table-hover table-bordered text-center" id="recentStocksTable">
                    <thead>
                        <tr>
                            <th>Quantity</th>
                            <th>Expiry Date</th>
                            <th>Purchased Date</th>
                            <th>Manufactured Date</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drug->getStocks(10) as $stock)
                            <tr>
                                <td>{{ App\Lib\Utils::getFormattedNumber($stock->quantity) }}</td>
                                <td>{{ App\Lib\Utils::getFormattedDate($stock->expiry_date) }}</td>
                                <td>{{ App\Lib\Utils::getFormattedDate($stock->received_date) }}</td>
                                <td>{{ App\Lib\Utils::getFormattedDate($stock->manufactured_date) }}</td>
                                <td>{{ $stock->remarks }}</td> {{-- Displaying remarks --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>

    @include('drugs.modals.editDrug')
    @include('drugs.stocks.modals.addStock')
@endsection
