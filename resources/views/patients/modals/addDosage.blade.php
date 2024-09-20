<script src="{{ asset('js/DrugController.js') }}?{{ App\Lib\Utils::getCachePreventPostfix() }}"></script>

<div class="modal fade" id="addDosageModal" tabindex="-1" aria-labelledby="addDosageModalLabel" aria-hidden="true" ng-controller="DrugController">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDosageModalLabel">Add New Drug to Prescription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid">
                    <div class="mb-3" ng-class="{'is-invalid': error.drug.has || error.quantityType.has}">
                        <label class="form-label">Drug</label>
                        <div>
                            <span class="text-danger" ng-if="error.drug.has">[[error.drug.msg]]</span>
                            <select class="form-select" ng-model="drug">
                                <option value="">None</option>
                                <option ng-repeat="drug in drugs" value="[[drug.id]]" ng-cloak>
                                    [[drug.name]]
                                </option>
                            </select>
                        </div>

                        <label class="form-label mt-3">or add a new drug</label>
                        <input type="text" class="form-control" ng-change="predictDrug()" ng-model="drugName"
                               placeholder="New drug to be added" list="drugPredictionList" required>
                        <datalist id="drugPredictionList">
                            <option ng-repeat="drug in drugPredictions">[[drug.trade_name]]</option>
                        </datalist>

                        <label class="form-label mt-3">Quantity type of the drug</label>
                        <span class="text-danger" ng-if="error.quantityType.has">[[error.quantityType.msg]]</span>
                        <input type="text" class="form-control" ng-model="quantityType"
                               placeholder="Quantity type" list="quantityTypesList" required>
                        <datalist id="quantityTypesList" ng-init="getQuantityTypes()">
                            <option ng-repeat="q in quantityTypes">[[q.drug_type]]</option>
                        </datalist>
                    </div>

                    <!-- Dosage Form Group -->
                    <div class="mb-3" ng-class="{'is-invalid': error.dosage.has}">
                        <label class="form-label">Dosage</label>
                        <span class="text-danger" ng-if="error.dosage.has">[[error.dosage.msg]]</span>
                        <select class="form-select" ng-model="dosage">
                            <option value="">None</option>
                            <option ng-repeat="dose in dosages track by dose.id" value="[[dose.id]]" ng-cloak>
                                [[dose.description]]
                            </option>
                        </select>
                    </div>

                    <!-- Frequency Form Group -->
                    <div class="mb-3" ng-class="{'is-invalid': error.frequency.has}">
                        <label class="form-label">Dosage Frequency</label>
                        <span class="text-danger" ng-if="error.frequency.has">[[error.frequency.msg]]</span>
                        <select class="form-select" ng-model="frequency">
                            <option value="">None</option>
                            <option ng-repeat="f in frequencies track by f.id" value="[[f.id]]" ng-cloak>
                                [[f.description]]
                            </option>
                        </select>
                    </div>

                    <!-- Period Form Group -->
                    <div class="mb-3" ng-class="{'is-invalid': error.period.has}">
                        <label class="form-label">Dosage Period</label>
                        <span class="text-danger" ng-if="error.period.has">[[error.period.msg]]</span>
                        <select class="form-select" ng-model="period">
                            <option value="">None</option>
                            <option ng-repeat="p in periods track by p.id" value="[[p.id]]" ng-cloak>
                                [[p.description]]
                            </option>
                        </select>
                    </div>

                    <!-- Error & Success Messages -->
                    <div class="mb-3">
                        <div class="alert alert-danger" ng-show="error.hasError" ng-cloak>
                            <h5><i class="fas fa-ban"></i> Sorry!</h5>
                            [[error.msg]]
                        </div>
                        <div class="alert alert-success" ng-show="success.hasSuccess" ng-cloak>
                            <h5><i class="fas fa-check"></i> Success!</h5>
                            [[success.msg]]
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="text-end">
                        <button class="btn btn-success" ng-click="save()">Add to Prescription</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" ng-click="pharmacyDrugs=[];">Cancel</button>
                <button class="btn btn-primary" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>
