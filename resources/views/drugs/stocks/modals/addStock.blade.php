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
                        <input type="number" id="quantity" name="quantity" class="form-control" min="0" step="0.01"
                            value="{{ old('quantity') }}">
                        @if ($errors->has('quantity'))
                            <div class="invalid-feedback">
                                {{ $errors->first('quantity') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="manufacturedDate" class="form-label">Manufactured Date</label>
                        <input type="date" id="manufacturedDate" name="manufacturedDate" class="form-control"
                            value="{{ old('manufacturedDate') }}">
                        @if ($errors->has('manufacturedDate'))
                            <div class="invalid-feedback">
                                {{ $errors->first('manufacturedDate') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="receivedDate" class="form-label">Purchased Date</label>
                        <input type="date" id="receivedDate" name="receivedDate" class="form-control"
                            value="{{ old('receivedDate') }}">
                        @if ($errors->has('receivedDate'))
                            <div class="invalid-feedback">
                                {{ $errors->first('receivedDate') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="expiryDate" class="form-label">Expiry Date</label>
                        <input type="date" id="expiryDate" name="expiryDate" class="form-control"
                            value="{{ old('expiryDate') }}">
                        @if ($errors->has('expiryDate'))
                            <div class="invalid-feedback">
                                {{ $errors->first('expiryDate') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea id="remarks" name="remarks" class="form-control"
                            rows="2">{{ old('remarks') }}</textarea>
                        @if ($errors->has('remarks'))
                            <div class="invalid-feedback">
                                {{ $errors->first('remarks') }}
                            </div>
                        @endif
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

@if(session('type') && session('type') === 'stock' && $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var addStockModal = new bootstrap.Modal(document.getElementById('addStockModal'));
            addStockModal.show();
        });
    </script>
@endif
