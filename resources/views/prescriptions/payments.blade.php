@extends('layouts.master')

@section('page_header')
    Payments
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('root') }}"><i class="fa fa-home"></i> Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Payments</li>
        </ol>
    </nav>
@endsection

@section('content')

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">Payments</h4>
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

            <table class="table table-responsive table-hover text-center" id="paymentsTable">
                <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Amount Paid</th>
                    <th>Remarks</th>
                    <th>Issued At</th>
                </tr>
                </thead>
                <tbody>
                @forelse($prescriptions as $prescription)
                    @if($prescription->hasIssued())
                        <tr class="tableRow"
                            onclick="window.location='{{ route('patient', ['id' => $prescription->patient->id]) }}'">
                            <td>{{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</td>
                            <td>{{ !empty($prescription->payment->amount) ? $prescription->payment->amount : '' }}</td>
                            <td>{{ !empty($prescription->payment->remarks) ? $prescription->payment->remarks : '' }}</td>
                            <td>{{ App\Lib\Utils::getTimestamp($prescription->issued_at) }}</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="4">No payments available</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- DataTables Scripts -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}" />
    <script>
        $(document).ready(function () {
            $('#paymentsTable').DataTable({
                pageLength: 10,
                responsive: true
            });
        });
    </script>
@endsection
