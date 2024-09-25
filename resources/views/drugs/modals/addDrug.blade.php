<div class="modal fade" id="addDrugModal" tabindex="-1" aria-labelledby="addDrugModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDrugModalLabel">Add Drug</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="form-horizontal" method="post" action="{{ route('addDrug') }}">

                <div class="modal-body">

                    {{-- Warning when there's no quantity type pre-entered --}}
                    @if(\App\Models\Clinic::getCurrentClinic()->quantityTypes()->count() == 0)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <h4><i class="icon fa fa-warning"></i> No Quantity Types Available!</h4>
                            In order to add drugs, quantity types are required. Quantity Types are used to manage stocks.
                            Go to <a href="{{ route('drugTypes') }}"><strong>Quantity Types</strong></a> to add quantity types.
                        </div>
                    @endif

                    {{-- General error message --}}
                    @if ($errors->has('general'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <h4><i class="icon fa fa-ban"></i> Oops!</h4>
                            {{ $errors->first('general') }}
                        </div>
                    @endif

                    {{ csrf_field() }}

                    <div class="mb-3">
                        <label for="drugName" class="form-label">
                            Drug Name
                            <i class="fa fa-question-circle-o" data-bs-toggle="tooltip" data-bs-placement="bottom"
                               title="Commercial/Brand name of the drug. Type at least 3 characters to get suggestions"></i>
                        </label>
                        <input type="text" class="form-control" id="drugName" name="drugName" required list="drugList"
                               ng-change="predictDrug()" ng-model="drugName" ng-init="drugName='{{ old('drugName') }}'">
                        <datalist id="drugList">
                            <option ng-repeat="drug in drugPredictions">[[drug.trade_name]]</option>
                        </datalist>
                        @if ($errors->has('drugName'))
                            <div class="invalid-feedback">
                                {{ $errors->first('drugName') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="ingredient" class="form-label">
                            Ingredient
                            <i class="fa fa-question-circle-o" data-bs-toggle="tooltip" data-bs-placement="bottom"
                               title="Scientific name or the generic name of the drug. Type at least 3 characters to get suggestions"></i>
                        </label>
                        <input type="text" class="form-control" id="ingredient" name="ingredient" required list="ingredientList"
                               ng-change="predictIngredient()" ng-model="ingredient" ng-init="ingredient='{{ old('ingredient') }}'">
                        <datalist id="ingredientList">
                            <option ng-repeat="drug in ingredientPredictions">[[drug.ingredient]]</option>
                        </datalist>
                        @if ($errors->has('ingredient'))
                            <div class="invalid-feedback">
                                {{ $errors->first('ingredient') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="quantityType" class="form-label">
                            Quantity Type
                            <i class="fa fa-question-circle-o" data-bs-toggle="tooltip" data-bs-placement="bottom"
                               title="The measurement used to measure the available quantity of a drug. ex: Number of 'Pills', number of 'Bottles', 'Litres'"></i>
                        </label>
                        <select id="quantityType" name="quantityType" class="form-select @error('quantityType') is-invalid @enderror">
                            {{-- Debugging output to check available quantity types --}}
                            @if(\App\Models\Clinic::getCurrentClinic()->quantityTypes()->count() > 0)
                                @foreach(\App\Models\Clinic::getCurrentClinic()->quantityTypes as $quantityType)
                                    <option value="{{ $quantityType->id }}" @if($quantityType->id == old('quantityType')) selected @endif>
                                        {{ $quantityType->drug_type }}
                                    </option>
                                @endforeach
                            @else
                                <option value="">No Quantity Types Available</option>
                            @endif
                        </select>
                        @if ($errors->has('quantityType'))
                            <div class="invalid-feedback">
                                {{ $errors->first('quantityType') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="manufacturer" class="form-label">Manufacturer</label>
                        <input type="text" class="form-control" id="manufacturer" name="manufacturer"
                               ng-init="manufacturer='{{ old('manufacturer') }}'"
                               required list="manufacturerList" ng-change="predictManufacturer()" ng-model="manufacturer">
                        <datalist id="manufacturerList">
                            <option ng-repeat="manufacturer in manufacturers">[[manufacturer.manufacturer]]</option>
                        </datalist>
                        @if ($errors->has('manufacturer'))
                            <div class="invalid-feedback">
                                {{ $errors->first('manufacturer') }}
                            </div>
                        @endif
                    </div>

                    {{-- Add Initial Stock Section --}}
                    @can('add','App\Models\Stock')
                        <div class="mb-4">
                            <h5 class="mb-3">Add Initial Stock (Optional)</h5>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" id="quantity" name="quantity" min="0" step="0.01" class="form-control"
                                       value="{{ old('quantity') }}">
                                @if ($errors->has('quantity'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('quantity') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                        <label for="manufacturedDate" class="form-label">Manufactured Date</label>
                        <input type="date" id="manufacturedDate" name="manufacturedDate"
                               class="form-control @error('manufacturedDate') is-invalid @enderror"
                               value="{{ old('manufacturedDate') }}">
                        @error('manufacturedDate')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="receivedDate" class="form-label">Purchased Date</label>
                        <input type="date" id="receivedDate" name="receivedDate"
                               class="form-control @error('receivedDate') is-invalid @enderror"
                               value="{{ old('receivedDate') }}">
                        @error('receivedDate')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="expiryDate" class="form-label">Expiry Date</label>
                        <input type="date" id="expiryDate" name="expiryDate"
                               class="form-control @error('expiryDate') is-invalid @enderror"
                               value="{{ old('expiryDate') }}">
                        @error('expiryDate')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea id="remarks" name="remarks" class="form-control" rows="2">{{ old('remarks') }}</textarea>
                                @if ($errors->has('remarks'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('remarks') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endcan

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('type') && session('type') === "drug" && $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('addDrugModal'));
            myModal.show();
        });
    </script>
@endif