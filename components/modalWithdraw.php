 <!-- ModalWithdraw -->
 <div class="modal fade pl-0" id="modalWithdraw" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-scrollable" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Retirar</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form>
                     <div class="row">
                         <div class="col-md-6 col-sm-12">
                             <div class="form-group">
                                 <label for="submitter" class="col-form-label">Entregado por:</label>
                                 <input type="text" class="form-control" readonly id="submitter" placeholder="Quién entrega">
                             </div>
                         </div>
                         <div class="col-md-6 col-sm-12">
                             <div class="form-group">
                                 <label for="receivedBy" class="col-form-label">Recibido por:</label>
                                 <input type="text" class="form-control is-invalid" id="receivedBy" placeholder="Quién recibe" required>
                             </div>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-6 col-sm-12">
                             <div class="form-group">
                                 <label for="mMarca" class="col-form-label">Marca</label>
                                 <input type="text" class="form-control" id="mMarca" readonly placeholder="Marca">
                             </div>

                         </div>
                         <div class="col-md-6 col-sm-12">
                             <div class="form-group">
                                 <label for="mModelo" class="col-form-label">Modelo</label>
                                 <input type="text" class="form-control" id="mModelo" readonly placeholder="Modelo">
                             </div>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-6 col-sm-12">
                             <div class="form-group">
                                 <label for="mTipo" class="col-form-label">Tipo</label>
                                 <input type="text" class="form-control" id="mTipo" readonly placeholder="Tipo">
                             </div>

                         </div>
                         <div class="col-md-6 col-sm-12">
                             <div class="form-group">
                                 <label for="mCodigo" class="col-form-label">Código</label>
                                 <input type="text" class="form-control" id="mCodigo" readonly placeholder="Código de barra">
                             </div>
                         </div>
                     </div>

                     <div class="form-group">
                         <label for="mBodega" class="col-form-label">Bodega</label>
                         <input type="text" class="form-control" id="mBodega" readonly>

                         <!--  <option value="" selected>Seleccione...</option>
                             <option value="1">Bodega 1</option>
                             <option value="2">Bodega 2</option>
                             <option value="3">Informática</option> -->

                     </div>
                 </form>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                 <button type="button" class="btn btn-primary" id="btnModalWithdraw">Confirmar</button>
             </div>
         </div>
     </div>
 </div>