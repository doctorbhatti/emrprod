
<div class="container">
    <h1 class="no-print">Prescription Print Preview</h1>

    <!-- Print Preview Option Handling -->
    @if($printPreviewOption == 'option1')
        @include('prescriptions.partials.printPreviewOption1', ['patient' => $patient, 'prescription' => $prescription])
    @elseif($printPreviewOption == 'option2')
        @include('prescriptions.partials.printPreviewOption2', ['patient' => $patient, 'prescription' => $prescription])
    @elseif($printPreviewOption == 'option3')
        @include('prescriptions.partials.printPreviewOption3', ['patient' => $patient, 'prescription' => $prescription])
    @else
        <p>Showing default print preview layout</p>
        <div class="prescription-details">
            <p><strong>Patient Name:</strong> {{ $patient->name }}</p>
            <p><strong>Prescription:</strong> {{ $prescription->details }}</p>
            <!-- Add more default prescription details here -->
        </div>
    @endif

</div>
