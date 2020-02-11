<!-- Modal -->
<div class="modal fade" id="modalTransfer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transferir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="text-center">
                    <div class="row align-items-center mb-3">
                        <input class="form-control col-lg-5 col-xs-5 mx-2" type="text" placeholder="Bodega actual" id="current-storage" disabled title="Bodega actual">
                        <span class="fas fa-long-arrow-alt-right fa-2x mx-auto col-1"></span>
                        <select class="custom-select col-lg-5 col-xs-8 mx-2" id="transfer-select" name="tipo" required>
                            <option value="">Seleccione...</option>
                        </select>
                    </div>

                    <label for="amountTtoINF">Especifique la cantidad por favor:</label>
                    <div class="form-group ">
                        <input type="number" class="form-control w-25 mx-auto" id="amountTtoINF" placeholder="Cantidad" min="1" required>
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