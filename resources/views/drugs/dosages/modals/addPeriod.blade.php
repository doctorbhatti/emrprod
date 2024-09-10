<div class="modal fade" id="addPeriodModal" tabindex="-1" aria-labelledby="addPeriodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPeriodModalLabel">Add Dosage Period</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="post" action="{{ route('addPeriod') }}">
                @csrf

                <div class="modal-body">
                    {{-- General error message --}}
                    @if ($errors->has('general'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-ban"></i> {{ $errors->first('general') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="description" class="form-label">Period Description</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror"
                               id="description" name="description" value="{{ old('description') }}" required>
                        @error('description')
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

@if(session('type') && session('type') === "period" && $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var addPeriodModal = new bootstrap.Modal(document.getElementById('addPeriodModal'));
            addPeriodModal.show();
        });
    </script>
@endif
