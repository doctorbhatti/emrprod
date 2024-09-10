@extends('layouts.master')

@section('page_header')
    Issue Medicine
@endsection

@section('content')
    <!-- Include AngularJS and custom scripts -->
    <script src="{{ asset('plugins/angularjs/angular.min.js') }}"></script>
    <script src="{{ asset('js/services.js') }}"></script>
    <script src="{{ asset('js/filters.js') }}"></script>
    <script src="{{ asset('js/GlobalIssueMedicineController.js') }}"></script>

    <div class="container-fluid" ng-app="HIS">
        <div class="card" ng-controller="IssueMedicineController">
            <div class="card-body">
                <!-- Initialize AngularJS variables -->
                <input type="hidden"
                       ng-init="baseUrl='{{ url('/') }}'; token='{{ csrf_token() }}'; loadAllPrescriptions()">

                <!-- Success Message -->
                <div class="alert alert-success alert-dismissible fade show" role="alert" ng-show="hasSuccess" ng-cloak>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    [[successMessage]]
                </div>

                <!-- No Prescriptions Info -->
                <div class="alert alert-info alert-dismissible fade show" role="alert" ng-if="prescriptions.length==0" ng-cloak>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <h4><i class="icon fa fa-info"></i> Sorry!</h4>
                    No Prescription to be issued at the moment.
                </div>

                <!-- New Prescriptions Alert -->
                <div class="alert alert-warning alert-dismissible fade show" role="alert" ng-show="hasAlert" ng-cloak>
                    <h4>New Prescriptions Available!</h4>
                    <p>There are new prescriptions available. Please load them.</p>
                    <button class="btn btn-default" ng-click="hasAlert=false; loadAllPrescriptions()">
                        Load
                    </button>
                </div>

                <!-- Prescriptions List -->
                <div class="card" ng-repeat="prescription in prescriptions track by $index" ng-cloak>
                    <div class="card-header">
                        <h4 class="card-title">
                            [[prescription.patient.first_name]] [[prescription.patient.last_name]]<br>
                            [[prescription.created_at | date:"EEEE, d/M/yy h:mm a"]]
                            <br  >
                            <strong>[[prescription.remarks]]</strong>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" ng-show="prescription.hasError" ng-cloak>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <h4><i class="icon fa fa-ban"></i> Oops!</h4>
                            [[error]]
                        </div>

                        <table class="table table-hover table-bordered text-center">
                            <thead>
                            <tr>
                                <th class="col-sm-4">Drug
                                    <i class="fa fa-question-circle-o fa-lg" data-bs-toggle="tooltip"
                                       data-bs-placement="bottom"
                                       title="The name of the drug to be issued. (The quantity type used to measure the drug's quantity is in the brackets)"></i>
                                </th>
                                <th class="col-sm-5">Dose</th>
                                <th class="col-sm-3">Quantity
                                    <i class="fa fa-question-circle-o fa-lg" data-bs-toggle="tooltip"
                                       data-bs-placement="bottom"
                                       title="The actual quantity of the drug issued. Type '0' in the field to neglect the quantity"></i>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="bg-success" ng-class="{'bg-danger':prescribedDrug.outOfStocks}"
                                ng-repeat="prescribedDrug in prescription.prescription_drugs">
                                <td>[[prescribedDrug.drug.name]] ([[prescribedDrug.drug.quantity_type.drug_type]])</td>
                                <td>
                                    [[prescribedDrug.dosage.description]]<br>
                                    [[prescribedDrug.frequency.description]]<br>
                                    [[prescribedDrug.period.description]]
                                </td>
                                <td>
                                    <div ng-class="{'has-error':prescribedDrug.outOfStocks}">
                                        <span class="text-danger" ng-show="prescribedDrug.outOfStocks">
                                            <strong>
                                            You have only [[prescribedDrug.drug.quantity | exactNumber]] units of
                                                stocks available. Continue at your own risk!
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

                        <!-- Pharmacy Drugs -->
                        <h4 ng-if="prescription.prescription_pharmacy_drugs.length > 0">Pharmacy Drugs</h4>
                        <table class="table table-bordered table-hover text-center"
                               ng-if="prescription.prescription_pharmacy_drugs.length > 0">
                            <thead>
                            <tr class="bg-success">
                                <th>Drug Name</th>
                                <th>Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="drug in prescription.prescription_pharmacy_drugs track by $index"
                                class="bg-success"
                                ng-cloak>
                                <td>[[drug.drug]]</td>
                                <td>
                                    [[drug.remarks]]
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <!-- Payment and Remarks Inputs -->
                        <div class="mb-3">
                            <label for="payment" class="form-label">Payment</label>
                            <input type="number" class="form-control" id="payment" min="0" ng-model="prescription.payment"
                                   step="0.01">
                        </div>
                        <div class="mb-3">
                            <label for="paymentRemarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="paymentRemarks" ng-model="prescription.paymentRemarks"></textarea>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button class="btn btn-success btn-lg float-end" ng-click="issuePrescription([[$index]])">
                            Mark as Issued
                            <i class="fa fa-question-circle-o fa-lg" data-bs-toggle="tooltip"
                               data-bs-placement="bottom"
                               title="Mark the prescription as 'Issued'. Once a prescription is issued, it cannot be reversed."></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Initialize tooltips -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
