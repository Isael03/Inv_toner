<!-- Modal -->
<div class="modal fade" id="modalTransfer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transferir a <span id="titleTransfer"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="text-center">
                    <label for="amountTtoINF">Especifique la cantidad por favor:</label>
                    <div class="form-group ">
                        <input type="number" class="form-control w-25 mx-auto" id="amountTtoINF" placeholder="Cantidad" min="0" required>
                        <small>*Si escoge cero se transferirán todos los elementos</small>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnModalTransfer">Confirmar</button>
            </div>
        </div>
    </div>
</div>
</div>