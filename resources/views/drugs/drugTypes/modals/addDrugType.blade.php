<div class="modal fade" id="addDrugTypeModal" tabindex="-1" aria-labelledby="addDrugTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDrugTypeModalLabel">Add Quantity Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="form-horizontal" method="post" action="{{ route('addDrugType') }}">
                <div class="modal-body">
                    {{-- General error message --}}
                    @if ($errors->has('general'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-ban"></i> {{ $errors->first('general') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{ csrf_field() }}

                    <div class="alert alert-warning">
                        These quantity types will be used when keeping track of drug stocks. Therefore, use meaningful type names which can be used to describe quantities.
                        <br>
                        <strong>Ex: Pills, Tablets, Bottles, Litres, Millilitres, etc...</strong>
                    </div>

                    <div class="mb-3">
                        <label for="drugType" class="form-label">Quantity Type</label>
                        <input type="text" class="form-control @error('drugType') is-invalid @enderror" id="drugType" name="drugType" value="{{ old('drugType') }}" required placeholder="Ex: Pills/Tablets/Bottles">
                        @error('drugType')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('addDrugTypeModal'));
            myModal.show();
        @endif
    });
</script>
