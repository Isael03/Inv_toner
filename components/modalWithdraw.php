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
             <form>
                 <div class="modal-body">

                     <div class="row">
                         <div class="col-md-12 col-sm-12">
                             <div class="form-group">
                                 <label for="receivedBy" class="col-form-label">Recibido por:</label>
                                 <div class="autocomplete">
                                     <input type="text" class="form-control" id="receivedBy" name="receivedBy" placeholder="Quién recibe" required>
                                 </div>

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
                                 <label for="mCantidad" class="col-form-label">Cantidad</label>
                                 <input type="number" class="form-control" id="mCantidad" placeholder="Cantidad a retirar" min="1">
                                 <small class="select-max" id="max-withdraw">Seleccionar máxima cantidad</small>
                             </div>
                         </div>
                     </div>

                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                     <input type="submit" class="btn btn-primary" id="btnModalWithdraw" value="Confirmar">
                 </div>
             </form>
         </div>
     </div>
 </div>