<div class="modal fade" id="addPharmacyDrugsModal" tabindex="-1" aria-labelledby="addPharmacyDrugsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPharmacyDrugsLabel">Add Pharmacy Drugs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="container-fluid">
                    <div class="mb-3">
                        <label class="form-label">Drug</label>
                        <input class="form-control" type="text" ng-model="pharmacyDrug" list="drugList"
                               ng-change="predictDrug()" placeholder="Drug to be taken from a pharmacy">
                        <datalist id="drugList">
                            <option ng-repeat="drug in drugPredictions" value="[[drug.trade_name]]"></option>
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea id="presentingComplaints" placeholder="Additional details (Dosages, precautions)"
                                  ng-model="pharmacyDrugRemarks" class="form-control"></textarea>
                    </div>

                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-success btn-lg" ng-click="addPharmacyDrug()">Add</button>
                    </div>

                </div>

                {{-- Area to show drugs --}}
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title">Pharmacy Drugs</h5>
                    </div>
                    <div class="card-body">
                        {{-- Table to show pharmacy drugs --}}
                        <table class="table table-striped table-bordered table-hover text-center"
                               ng-if="pharmacyDrugs.length > 0">
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
                                <td>
                                    [[d.remarks]]
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" ng-click="removePharmacyDrug($index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="alert alert-success" ng-if="pharmacyDrugs.length == 0" ng-cloak>
                            No Drugs Prescribed!
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" ng-click="pharmacyDrugs=[];">Cancel</button>
                <button class="btn btn-primary" data-bs-dismiss="modal">Done</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
