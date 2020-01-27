<!-- Modal Update-->
<div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modificar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group ">
                                <label for="updMarca">Marca</label>
                                <input type="text" class="form-control" id="updMarca" placeholder="Marca" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group ">
                                <label for="updModelo">Modelo</label>
                                <input type="text" class="form-control" id="updModelo" placeholder="Modelo" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group ">
                                <label for="updTipo" class="form-label">Tipo</label>
                                <select class="custom-select" id="updTipo" name="tipo" required>
                                    <option value="" selected>Seleccione...</option>
                                    <option value="Fusor">Fusor</option>
                                    <option value="Tinta">Tinta</option>
                                    <option value="Tambor">Tambor</option>
                                    <option value="Toner">Tóner</option>
                                    <option value="Tambor de residuo">Tambor de residuo</option>
                                    <option value="Tambor de arrastre">Tambor de arrastre</option>
                                    <option value="Correa de arrastre">Correa de arrastre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="updImpresora">Impresora</label>
                                <input type="text" class="form-control" id="updImpresora" placeholder="Modelo de impresora" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnModalUpdate">Modificar</button>
            </div>
        </div>
    </div>
</div>