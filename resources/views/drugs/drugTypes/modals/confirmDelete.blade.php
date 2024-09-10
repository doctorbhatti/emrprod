<div class="modal fade" id="confirmDeleteDrugTypeModal" tabindex="-1" aria-labelledby="confirmDeleteDrugTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h5 class="modal-title" id="confirmDeleteDrugTypeModalLabel">Delete Quantity Type</h5>
            </div>

            <form class="form-horizontal" method="post" action="#">
                <div class="modal-body">
                    {{ csrf_field() }}

                    <p>Are you sure that you want to completely remove this quantity type from the system?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showConfirmDelete(drugTypeId, name) {
        var form = document.querySelector('#confirmDeleteDrugTypeModal form');
        form.action = `{{ url('drugs/deleteDrugType') }}/${drugTypeId}`;
        
        var modalTitle = document.querySelector('#confirmDeleteDrugTypeModalLabel');
        modalTitle.textContent = `Delete ${name}`;
        
        var myModal = new bootstrap.Modal(document.getElementById('confirmDeleteDrugTypeModal'));
        myModal.show();
    }
</script>
