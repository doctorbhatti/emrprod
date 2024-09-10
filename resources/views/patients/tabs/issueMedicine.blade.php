<div class="container-fluid mt-4" ng-controller="IssueMedicineController">

    {{-- Initialize the Angular variables in a hidden field --}}
    <input type="hidden"
           ng-init="baseUrl='{{ url('/') }}'; id={{ $patient->id }}; token='{{ csrf_token() }}'; loadPrescriptions()">

    {{-- Success Message --}}
    <div class="alert alert-success" ng-show="hasSuccess" ng-cloak>
        <h4><i class="icon fa fa-check"></i> Success!</h4>
        [[successMessage]]
    </div>

    {{-- Info Message if there are no prescriptions to be issued --}}
    <div class="alert alert-info" ng-if="prescriptions.length == 0" ng-cloak>
        <h4><i class="icon fa fa-info"></i> Sorry!</h4>
        No Prescription to be issued for this patient.
    </div>

    {{-- Prescription --}}
    <div class="card mb-3" ng-repeat="prescription in prescriptions track by $index">
        <div class="card-header">
            <h4 class="card-title">
                [[prescription.created_at | date:"EEEE, d/M/yy h:mm a"]]
            </h4>
            <button class="btn btn-sm btn-danger float-end ms-2" ng-click="deletePrescription([[$index]])">
                Delete Prescription
            </button>
            <a href="{{ url("/patients/patient/{$patient->id}/printPrescription") }}/[[prescription.id]]"
               class="btn btn-sm btn-secondary float-end" style="margin-right: 10px;" target="_blank"
               ng-if="prescription.prescription_pharmacy_drugs.length > 0 || prescription.prescription_drugs.length > 0">
                Print Prescription
                <i class="fa fa-question-circle-o fa-lg" data-bs-toggle="tooltip"
                   data-bs-placement="bottom" title=""
                   data-bs-original-title="Opens a new tab in the browser to print the prescription where the prescription can be printed"></i>
            </a>
        </div>

        <div class="card-body">
            <div class="alert alert-danger" ng-show="prescription.hasError" ng-cloak>
                <h4><i class="icon fa fa-ban"></i> Oops!</h4>
                [[error]]
            </div>

            <table class="table table-hover table-bordered text-center"
                   ng-if="prescription.prescription_drugs.length > 0">
                <thead>
                <tr class="table-success">
                    <th>Drug
                        <i class="fa fa-question-circle-o fa-lg" data-bs-toggle="tooltip"
                           data-bs-placement="bottom" title=""
                           data-bs-original-title="The name of the drug to be issued. (The quantity type used to measure the drug's quantity is in the brackets)"></i>
                    </th>
                    <th>Dose</th>
                    <th>Quantity
                        <i class="fa fa-question-circle-o fa-lg" data-bs-toggle="tooltip"
                           data-bs-placement="bottom" title=""
                           data-bs-original-title="The actual quantity of the drug issued. Type '0' in the field to neglect the quantity"></i>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr ng-class="{'table-danger': prescribedDrug.outOfStocks}"
                    ng-repeat="prescribedDrug in prescription.prescription_drugs">
                    <td>[[prescribedDrug.drug.name]] ([[prescribedDrug.drug.quantity_type.drug_type]])</td>
                    <td>
                        [[prescribedDrug.dosage.description]]<br>
                        [[prescribedDrug.frequency.description]]<br>
                        [[prescribedDrug.period.description]]
                    </td>
                    <td>
                        <div ng-class="{'has-error': prescribedDrug.outOfStocks}">
                            <span class="text-danger" ng-show="prescribedDrug.outOfStocks">
                                <strong>
                                    You have only [[prescribedDrug.drug.quantity | exactNumber]] units of stock available.
                                    Continue at your own risk!
                                </strong>
                            </span>
                            <input class="form-control" type="number" step="0.01"
                                   ng-model="prescribedDrug.issuedQuantity" min="0"
                                   ng-change="checkStockAvailability([$parent.$index])">
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            {{-- Table to show pharmacy drugs --}}
            <h4 ng-if="prescription.prescription_pharmacy_drugs.length > 0">Pharmacy Drugs</h4>
            <table class="table table-bordered table-hover text-center"
                   ng-if="prescription.prescription_pharmacy_drugs.length > 0">
                <thead>
                <tr class="table-success">
                    <th>Drug Name</th>
                    <th>Remarks</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="drug in prescription.prescription_pharmacy_drugs track by $index" class="table-success">
                    <td>[[drug.drug]]</td>
                    <td>[[drug.remarks]]</td>
                </tr>
                </tbody>
            </table>

            {{-- Input to add payment information --}}
            <div class="mb-3">
                <label class="form-label">Payment</label>
                <input type="number" class="form-control" min="0" ng-model="prescription.payment" step="0.01">
            </div>
            <div class="mb-3">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" ng-model="prescription.paymentRemarks"></textarea>
            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-lg btn-success float-end" ng-click="issuePrescription([[$index]])">
                Mark as Issued
                <i class="fa fa-question-circle-o fa-lg" data-bs-toggle="tooltip"
                   data-bs-placement="bottom" title=""
                   data-bs-original-title="Mark the prescription as 'Issued'. Once a prescription is issued, it cannot be reversed."></i>
            </button>
        </div>
    </div>

</div>
