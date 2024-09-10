@extends('layouts.master')

@section('page_header')
    Drugs with Stocks Running Low
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Stocks with Quantity Less Than 100 Units</h4>
        </div>
        <!-- Card Body -->
        <div class="card-body">

            <style>
                .tableRow {
                    cursor: pointer;
                }

                .tableRow:hover {
                    text-decoration: underline !important;
                }
            </style>

            <table class="table table-responsive table-hover text-center" id="stocksTable">
                <thead>
                <tr>
                    <th>Drug Name</th>
                    <th>Manufacturer</th>
                    <th>Available Quantity</th>
                </tr>
                </thead>
                <tbody>
                @forelse($drugs as $drug)
                    <tr class="tableRow" onclick="window.location='{{ route('drug', ['id' => $drug->id]) }}'">
                        <td>{{ $drug->name }}</td>
                        <td>{{ $drug->manufacturer }}</td>
                        <td>{{ $drug->quantity }} ({{ $drug->quantityType->drug_type }})</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No drugs found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- DataTables Scripts --}}
    <script>
        $(document).ready(function () {
            $('#stocksTable').DataTable({
                pageLength: 10
            });
        });
    </script>
    {{-- //DataTables --}}
@endsection
