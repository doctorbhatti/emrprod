<div class="container-fluid mt-4" ng-controller="RecordController">

    {{-- Initialize the angular variables in a hidden field --}}
    <input type="hidden"
        ng-init="baseUrl='{{ url('/') }}'; id={{ $patient->id }}; token='{{ csrf_token() }}'; loadMedicalRecords()">

    <!-- Scroll to Top Button -->
    <button type="button" class="btn btn-primary scroll-to-top" ng-click="scrollToTop()" ng-show="showScrollToTop"
        style="position: fixed; bottom: 80px; right: 20px; z-index: 9999; border-radius: 50%; width: 50px; height: 50px; padding: 10px;">
        <i class="fa fa-arrow-up"></i>
    </button>

    <!-- Scroll to Bottom Button -->
    <button type="button" class="btn btn-primary scroll-to-bottom" ng-click="scrollToBottom()"
        ng-show="showScrollToBottom"
        style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; border-radius: 50%; width: 50px; height: 50px; padding: 10px;">
        <i class="fa fa-arrow-down"></i>
    </button>


    <div class="alert alert-success d-none" ng-show="hasSuccess" ng-cloak>
        <h4><i class="icon fa fa-check"></i> Success!</h4>
        [[successMessage]]
    </div>

    <div class="row mb-3">
        <label class="col-md-4 col-form-label text-md-start">Search (by diagnosis, date, etc...)</label>
        <div class="col-md-4">
            <input type="text" ng-model="searchText" class="form-control">
        </div>
    </div>

    {{-- Info message if there are no prescriptions to be issued --}}
    <div class="alert alert-info d-none" ng-if="prescriptions.length == 0" ng-cloak>
        <h4><i class="icon fa fa-info"></i> Sorry!</h4>
        No medical record to be displayed for this patient.
    </div>

    {{-- Prescription --}}
    <div class="card mb-3" ng-repeat="prescription in prescriptions | filter:searchText">
        <div class="card-header">
            <h4 class="card-title">
                [[prescription.created_at | date:"EEEE, d/M/yy h:mm a"]]
            </h4>
        </div>

        <div class="card-body">

            <div class="alert alert-danger d-none" ng-show="prescription.hasError" ng-cloak>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <h4><i class="icon fa fa-ban"></i> Oops!</h4>
                [[error]]
            </div>

            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Complaints</label>
                <div class="col-md-8">[[prescription.complaints]]</div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Investigations</label>
                <div class="col-md-8">[[prescription.investigations]]</div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Diagnosis</label>
                <div class="col-md-8">[[prescription.diagnosis]]</div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Remarks</label>
                <div class="col-md-8">[[prescription.remarks]]</div>
            </div>

            <br>


            <table class="table table-hover table-condensed table-bordered text-center mb-3">
                <thead>
                    <tr class="table-success">
                        <th class="col-sm-4">Drug</th>
                        <th class="col-sm-5">Dose</th>
                        <th class="col-sm-3">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="prescribedDrug in prescription.prescription_drugs" class="table-success">
                        <td>[[prescribedDrug.drug.name]] ([[prescribedDrug.drug.quantity_type.drug_type]])</td>
                        <td>
                            [[prescribedDrug.dosage.description]]<br>
                            [[prescribedDrug.frequency.description]]<br>
                            [[prescribedDrug.period.description]]
                        </td>
                        <td>
                            [[prescribedDrug.quantity | exactNumber]]
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Table to show pharmacy drugs --}}
            <h4 ng-if="prescription.prescription_pharmacy_drugs.length > 0">Pharmacy Drugs</h4>
            <table class="table table-condensed table-bordered table-hover text-center"
                ng-if="prescription.prescription_pharmacy_drugs.length > 0">
                <thead>
                    <tr class="table-success">
                        <th>Drug Name</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="drug in prescription.prescription_pharmacy_drugs track by $index"
                        class="table-success" ng-cloak>
                        <td>[[drug.drug]]</td>
                        <td>
                            [[drug.remarks]]
                        </td>
                    </tr>
                </tbody>
            </table>

            <br>

            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Payment</label>
                <div class="col-md-8">[[prescription.payment.amount]]</div>
            </div>

            <div class="row mb-2">
                <label class="col-md-4 col-form-label">Remarks on Payment</label>
                <div class="col-md-8">[[prescription.payment.remarks]]</div>
            </div>
            <div style="padding:20px;">
                <button class="btn btn-primary btn-lg btn-flat float-end"
                    ng-click="copyDrugsToLocalStorage(prescription.prescription_drugs)">
                    Select Drugs to Repeat
                </button>
            </div>
        </div>
    </div>

</div>