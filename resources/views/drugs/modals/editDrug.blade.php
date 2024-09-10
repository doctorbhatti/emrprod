<div class="modal fade" id="editDrugModal" tabindex="-1" aria-labelledby="editDrugModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDrugModalLabel">Edit Drug</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="form-horizontal" method="post" action="{{route('editDrug', ['id' => $drug->id])}}">
                <div class="modal-body">

                    {{-- Warning when there's no quantity type pre entered --}}
                    @if($drug->clinic->quantityTypes()->count() == 0)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <h5><i class="icon fa fa-warning"></i> No Quantity Types Available!</h5>
                            In order to add drugs, quantity types are required. Quantity Types are used to manage
                            stocks. Go to <a href="{{route('drugTypes')}}"><strong> Quantity Types</strong> </a> to add
                            quantity types.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- General error message --}}
                    @if ($errors->has('general'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5><i class="icon fa fa-ban"></i> Oops!</h5>
                            {{ $errors->first('general') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{csrf_field()}}

                    <div class="mb-3">
                        <label for="drugName" class="form-label">Drug Name</label>
                        <input type="text" class="form-control" name="drugName" id="drugName"
                            value="{{ old('drugName') ?: $drug->name }}" required>
                        @if ($errors->has('drugName'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('drugName') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="ingredient" class="form-label">Ingredient</label>
                        <input type="text" class="form-control" name="ingredient" id="ingredient"
                            value="{{ old('ingredient') ?: $drug->ingredient }}" required>
                        @if ($errors->has('ingredient'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('ingredient') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="quantityType" class="form-label">Quantity Type</label>
                        <select name="quantityType" id="quantityType" class="form-select">
                            @foreach($drug->clinic->quantityTypes as $quantityType)
                                <option value="{{$quantityType->id}}" @if($quantityType->id === $drug->quantityType->id)
                                selected @endif>
                                    {{$quantityType->drug_type}}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('quantityType'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('quantityType') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="manufacturer" class="form-label">Manufacturer</label>
                        <input type="text" class="form-control" name="manufacturer" id="manufacturer"
                            value="{{ old('manufacturer') ?: $drug->manufacturer }}" required>
                        @if ($errors->has('manufacturer'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('manufacturer') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('type') && session('type') === "drug" && $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editDrugModal = new bootstrap.Modal(document.getElementById('editDrugModal'));
            editDrugModal.show();
        });
    </script>
@endif
