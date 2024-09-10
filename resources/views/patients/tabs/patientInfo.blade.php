<div class="container-fluid mt-4">
    <div class="row g-3">

        <div class="col-md-6">
            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Name</label>
                <div class="col-md-8">{{ $patient->first_name }} {{ $patient->last_name }}</div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Age</label>
                <div class="col-md-8">{{ App\Lib\Utils::getAge($patient->dob) }}</div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Address</label>
                <div class="col-md-8">{{ $patient->address }}</div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Gender</label>
                <div class="col-md-8">{{ $patient->gender }}</div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4 col-form-label">NIC</label>
                <div class="col-md-8">{{ $patient->nic }}</div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Contact No.</label>
                <div class="col-md-8">{{ $patient->phone }}</div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Registered on</label>
                <div class="col-md-8">{{ App\Lib\Utils::getTimestamp($patient->created_at) }}</div>
            </div>

        </div>

        <div class="col-md-6">
            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Blood Group</label>
                <div class="col-md-8">{{ $patient->blood_group }}</div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Remarks</label>
                <div class="col-md-8">{{ $patient->remarks ?: '-' }}</div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Allergies</label>
                <div class="col-md-8">{{ $patient->allergies ?: '-' }}</div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Family History</label>
                <div class="col-md-8">{{ $patient->family_history ?: '-' }}</div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Medical History</label>
                <div class="col-md-8">{{ $patient->medical_history ?: '-' }}</div>
            </div>
            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Surgical History</label>
                <div class="col-md-8">{{ $patient->post_surgical_history ?: '-' }}</div>
            </div>
        </div>

    </div>
</div>
