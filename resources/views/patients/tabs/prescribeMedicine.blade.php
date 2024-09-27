<div class="container-fluid" ng-app="HIS">
    <!-- Main Box -->
    <div class="card card-default card-solid" ng-controller="PrescriptionController">
        <div class="card-header">
            <h4 class="card-title">Prescription</h4>
        </div>

        <!-- Main Box Body -->
        <div class="card-body">
            <div class="alert alert-info alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <h4><i class="fas fa-info-circle"></i> Print Prescriptions</h4>
                <p>You can print the prescription as soon as you save it after adding the required
                    medicine and inspections. You can print the previous prescriptions from the
                    <strong>Issue Medicine</strong> tab above.
                </p>
            </div>

            <div class="alert alert-danger" ng-show="hasError" ng-cloak>
                <h4><i class="fas fa-ban"></i> Oops!</h4>
                [[error]]
            </div>

            <div class="alert alert-success" ng-show="hasSuccess" ng-cloak>
                <h4><i class="fas fa-check"></i> Success!</h4>
                <p>Prescription saved successfully. You can print the drugs to be taken from a pharmacy by clicking the
                    button below.</p>
                <a href="{{url("/patients/patient/{$patient->id}/printPrescription")}}/[[printPrescriptionId]]"
                    class="btn btn-outline-secondary" target="_blank">
                    <i class="fas fa-print"></i> Print Prescription
                </a>
            </div>

            <div class="form-horizontal mt-4">
                <!-- Initialize the angular variables in a hidden field -->
                <input type="hidden"
                    ng-init="baseUrl='{{url('/')}}';id={{$patient->id}};token='{{csrf_token()}}';init()">

                <div class="mb-3 row">
                    <label class="col-md-3 col-form-label">Presenting Complaints</label>
                    <div class="col-md-9">
                        <textarea id="presentingComplaints" placeholder="Presenting Complaints" ng-model="complaints"
                            class="form-control"></textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-3 col-form-label">Investigations</label>
                    <div class="col-md-9">
                        <textarea id="prescriptionInvestigations" placeholder="Investigations" ng-model="investigations"
                            class="form-control"></textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-3 col-form-label">
                        Diagnosis
                        <i class="fas fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Start typing to get suggestions for the diagnosis"></i>
                    </label>
                    <div class="col-md-9">
                        <input id="prescriptionDiagnosis" placeholder="Start typing to get suggestions..."
                            ng-model="diagnosis" class="form-control" type="text" ng-change="predictDisease()"
                            list="diseaseList">
                        <datalist id="diseaseList">
                            <option ng-repeat="disease in diseasePredictions">[[disease.disease]]</option>
                        </datalist>
                    </div>
                </div>

                <!-- Amount Section -->
                <div class="mb-3 row">
                    <label class="col-md-3 col-form-label">Amount(For Claim)</label>
                    <div class="col-md-9">
                        <input type="number" id="prescriptionAmount" placeholder="Enter Amount For Claim" ng-model="amount"
                            class="form-control">
                    </div>
                </div>


                <div class="mb-3 row">
                    <label class="col-md-3 col-form-label">Other Remarks</label>
                    <div class="col-md-9">
                        <textarea id="prescriptionRemarks" ng-model="remarks" placeholder="Remarks"
                            class="form-control"></textarea>

                        <div class="d-flex justify-content-between w-100" style="margin-top: 20px;">
                            <!-- Repeat Prescription Button -->
                            <button class="btn btn-primary btn-lg btn-flat"
                                ng-click="loadPrescribedDrugsFromLocalStorage()">
                                <i class="fa fa-repeat"></i> Repeat Prescription
                            </button>

                            <!-- Print Investigations Button -->
                            <button class="btn btn-primary btn-lg btn-flat" ng-click="printInvestigations()">
                                <i class="fa fa-print"></i> Print Investigations
                            </button>

                            <!-- Print Bill For Claim Button -->
                            <button class="btn btn-primary btn-lg btn-flat" ng-click="printBillForClaim()">
                                <i class="fa fa-print"></i> Print Bill
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Area to add drugs -->
                <div class="card card-success card-solid">
                    <div class="card-header">
                        <h4 class="card-title">Drugs</h4>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-danger" ng-show="hasDrugError" ng-cloak>
                            <h4><i class="fas fa-ban"></i> Oops!</h4>
                            [[error]]
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-label">
                                    Drug
                                    <i class="fas fa-question-circle" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom"
                                        title="Select the drug to be added to the prescription."></i>
                                </label>
                                <select id="prescriptionDrug" class="form-select" ng-model="drug" size="6">
                                    <option value="">None</option>
                                    <option ng-repeat="drug in drugs" value="[[drug.id]]" ng-cloak>
                                        [[drug.name]] ([[drug.quantity | exactNumber]] Available)
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    Can't find the drug?
                                    <i class="fas fa-question-circle" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom"
                                        title="Add a new drug, dosage, frequency and period which is not present in the lists."></i>
                                </label>
                                <button class="btn btn-outline-secondary btn-lg btn-flat" data-bs-toggle="modal"
                                    data-bs-target="#addDosageModal">Add
                                </button>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label">
                                    Dose
                                    <i class="fas fa-question-circle" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom"
                                        title="The quantity of the drug to be taken at a time."></i>
                                </label>
                                <select id="prescriptionDose" class="form-select" ng-model="dosage" size="6">
                                    <option value="">None</option>
                                    <option ng-repeat="dose in dosages track by dose.id" value="[[dose.id]]" ng-cloak>
                                        [[dose.description]]</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Frequency (Optional)</label>
                                <select id="prescriptionFrequency" class="form-select" ng-model="frequency" size="6">
                                    <option value="">None</option>
                                    <option ng-repeat="f in frequencies track by f.id" value="[[f.id]]" ng-cloak>
                                        [[f.description]]
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Period (Optional)</label>
                                <select id="prescriptionPeriod" class="form-select" ng-model="period" size="6">
                                    <option value="">None</option>
                                    <option ng-repeat="p in periods track by p.id" value="[[p.id]]" ng-cloak>
                                        [[p.description]]
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-outline-secondary btn-lg btn-flat" data-bs-toggle="modal"
                            data-bs-target="#addPharmacyDrugsModal">
                            Pharmacy Drugs
                        </button>
                        <button class="btn btn-success btn-lg btn-flat float-end" ng-click="add()">
                            Add
                        </button>
                    </div>
                </div>
                <!-- /Area to add drugs -->

                <!-- Area to show drugs -->
                <div class="card card-success card-solid mt-3">
                    <div class="card-header">
                        <h4 class="card-title">Prescribed Drugs</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover text-center" ng-if="prescribedDrugs.length>0">
                            <thead>
                                <tr class="table-success">
                                    <th>Drug Name</th>
                                    <th>Dose</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="d in prescribedDrugs track by $index" class="table-success" ng-cloak>
                                    <td>[[d.drug.name]]</td>
                                    <td>[[d.dose.description]]<br>[[d.frequency.description]]<br>[[d.period.description]]
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" ng-click="removeDrug([[$index]])">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <h4 ng-if="pharmacyDrugs.length>0">Pharmacy Drugs</h4>
                        <table class="table table-bordered table-hover text-center" ng-if="pharmacyDrugs.length>0">
                            <thead>
                                <tr class="table-success">
                                    <th>Drug Name</th>
                                    <th>Remarks</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="d in pharmacyDrugs track by $index" class="table-success" ng-cloak>
                                    <td>[[d.name]]</td>
                                    <td>[[d.remarks]]</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" ng-click="removePharmacyDrug([[$index]])">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="alert alert-success" ng-if="prescribedDrugs.length==0 && pharmacyDrugs.length==0"
                            ng-cloak>
                            No Drugs Prescribed!
                        </div>
                    </div>
                </div>
                <!-- /Area to show drugs -->
            </div>
        </div>

        <!-- Overlay to be shown when submitted -->
        <div class="overlay" ng-show="submitted" ng-cloak>
            <i class="fas fa-sync fa-spin"></i>
        </div>

        <div class="card-footer">
            <button class="btn btn-primary btn-lg float-end" ng-click="savePrescription()">Save Prescription</button>
            <button class="btn btn-danger btn-lg" ng-click="clearPrescription()">Cancel Prescription</button>
        </div>

        <!-- Modal to add pharmacy Drugs -->
        @include('patients.modals.addPharmacyDrugs')
        @include('patients.modals.addDosage')
    </div>
    <!-- /Main Box -->
</div>