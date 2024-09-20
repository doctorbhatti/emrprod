<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockModalLabel">Add Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="post" action="{{ route('addStock', ['drugId' => $drug->id]) }}">
                @csrf

                <div class="modal-body">
                    {{-- General error message --}}
                    @if ($errors->has('general'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Oops!</strong> {{ $errors->first('general') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity (in {{ $drug->getQuantityType() }})</label>
                        <input type="number" id="quantity" name="quantity"
                               class="form-control @error('quantity') is-invalid @enderror"
                               min="0" step="0.01" value="{{ old('quantity') }}">
                        @error('quantity')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
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
                        <textarea id="remarks" name="remarks" class="form-control @error('remarks') is-invalid @enderror"
                                  rows="2">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script to show the modal if there are errors --}}
@if(session('type') && session('type') === 'stock' && $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Showing Add Stock Modal due to errors:', @json($errors->all()));
            var addStockModal = new bootstrap.Modal(document.getElementById('addStockModal'));
            addStockModal.show();
        });
    </script>
@endif
