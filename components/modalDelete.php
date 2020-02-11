<!-- Modal Delete-->

<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Esta seguro de que desea borrarlo?
                <div class="form-group mx-auto mt-2 text-center w-50">
                    <label for="recipient-name" class="col-form-label">Cantidad a eliminar</label>
                    <input type="number" class="form-control w-100 mx-auto" id="cantDelete" placeholder="Cantidad a eliminar" value="1" min="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger" id="btnModalDelete">Borrar</button>
            </div>
        </div>
    </div>
</div>