"use strict";

document.addEventListener("DOMContentLoaded", function() {
  showContent();
  autocompletar();
  printMarcaPrinter();

  /**Las tablas y eventos relacionados cargan 1 segundo despues de cargar las cartas y tabs, para evitar conflictos de variables desconocidas */
  setTimeout(() => {
    var tableStorages = listStorages();
    var tableAll = listALL();

    document
      .querySelector("#change-storage")
      .addEventListener("input", function() {
        changeStorage(tableStorages);
      });

    /*------------------------- Botones de modals ----------------------------*/

    /* boton eliminar del modal */
    document.querySelector("#btnModalDelete").addEventListener("click", () => {
      confirmDelete(tableStorages);
    });

    /* boton actualizar del modal */
    document.querySelector("#btnModalUpdate").addEventListener("click", () => {
      confirmUpdate(tableAll);
    });

    /* Boton retirar del modal */
    document
      .querySelector("#btnModalWithdraw")
      .addEventListener("click", () => {
        if (tableStorages.row(".selected").length > 0) {
          confirmWithdrawINF_MO(tableStorages);
        }
      });

    document
      .querySelector("#btnModalTransfer")
      .addEventListener("click", () => {
        confirmTransfer(tableStorages);
      });

    /* Desmarcar filas seleccionadas al cambiar la pestaña de la tabla */
    document.querySelector("#myTab").addEventListener("click", () => {
      tableStorages.rows().deselect();
      tableAll.rows().deselect();
      tableAll.ajax.reload();
      tableStorages.ajax.reload();
    });
  }, 1000);

  /* Evento del input marca del modal actualizar */
  document.querySelector("#updMarca").addEventListener("input", () => {
    printModelPrinter();
    document.querySelector(
      "#updImpresora"
    ).innerHTML = `<option value="">Seleccione...</option>`;
  });
});

//***************************************************************************************************************************** */

/* Acción modificar-abrir modal */
/**@param {object} table*/
function getDataUpdate(table) {
  var data = table.row(".selected").data();

  if (table.row(".selected").length > 0) {
    //abrir modal
    jQuery.noConflict();
    jQuery("#modalUpdate");
    $("#modalUpdate").modal("show");

    let marca = data.Marca;
    let modelo = data.Modelo;
    let tipo = data.Tipo;
    let impresora = data.Impresora;

    //Pasar datos al modal
    setModalUpdate(marca, modelo, tipo, impresora);
  } else {
    customAlertError("Seleccione un elemento");
  }
}

/* Acción boton eliminar-abrir modal */
/**@param {object} table*/
function getDataDelete(table) {
  if (table.row(".selected").length > 0) {
    jQuery.noConflict();
    jQuery("#modalDelete");
    $("#modalDelete").modal("show");
    document.getElementById("cantDelete").value = 1;
  } else {
    customAlertError("Seleccione un elemento");
  }
}

/* Enviar datos para eliminar */
/**@param {object} table*/
function confirmDelete(table) {
  const data = table.row(".selected").data();
  let cant_delete = document.getElementById("cantDelete").value;

  if (cant_delete <= parseInt(data.Cantidad) || cant_delete === 0) {
    const dataDelete = new FormData();
    dataDelete.append("cantidad", parseInt(cant_delete));
    dataDelete.append("modelo", data.Modelo.toUpperCase());
    dataDelete.append("marca", data.Marca.toUpperCase());
    dataDelete.append("tipo", data.Tipo);
    dataDelete.append("id_bodega", parseInt(data.Id_bodega));

    fetchURL("./api/consumible/delete_consumible.php", "POST", dataDelete)
      .then(res => {
        if (res.status === "ok") {
          customAlertSuccess("Elemento eliminado");
          changeStorage(table);
          //Ocultar modal
          $("#modalDelete").modal("hide");
          updateCountCard();
        } else {
          alertError();
        }
      })
      .catch(err => {
        console.log(err);
        alertError();
      });
  } else {
    customAlertError("La cantidad seleccionada sobrepasa la que existe");
  }
}

/* Pasar datos al modal de modificar */
/**@param {string} marca,  @param {string} modelo, @param {string} tipo, @param {string} impresora*/
async function setModalUpdate(marca, modelo, tipo, impresora) {
  document.getElementById("updMarca").value = marca;
  document.getElementById("updModelo").value = modelo;
  document.getElementById("updTipo").value = tipo;
  await printModelPrinter();
  //setTimeout(() => {
  document.getElementById("updImpresora").value = impresora;
  //}, 300);
}

/* validacion de datos del modal modificar */
function validFields() {
  let marca = document.getElementById("updMarca").value;
  let modelo = document.getElementById("updModelo").value;
  let tipo = document.getElementById("updTipo").value;
  let modelo_impresora = document.getElementById("updImpresora").value;

  validClass(["#updMarca", "#updModelo", "#updTipo", "#updImpresora"]);

  if (marca != "" && modelo != "" && tipo != "" && modelo_impresora != "") {
    return true;
  } else {
    return false;
  }
}

/* Botón confirmación del modal modificar */
/**@param {object} table */
function confirmUpdate(table) {
  var datatable = table.row(".selected").data();

  if (validFields()) {
    let _marca = document.getElementById("updMarca").value.trim();
    let _modelo = document.getElementById("updModelo").value.trim();
    let _tipo = document.getElementById("updTipo").value.trim();
    let _impresora = document.getElementById("updImpresora").value.trim();

    _modelo = _modelo.toUpperCase().replace(/ /g, "-");

    //Pasar datos al objeto formatData
    let data = new FormData();
    data.append("marca_new", _marca.toUpperCase());
    data.append("modelo_new", _modelo.toUpperCase());
    data.append("tipo_new", _tipo);
    data.append("impresora_new", _impresora.toUpperCase());
    data.append("marca_old", datatable.Marca);
    data.append("modelo_old", datatable.Modelo);
    data.append("tipo_old", datatable.Tipo);
    data.append("impresora_old", datatable.Impresora);

    fetchURL("./api/consumible/update_consumible.php", "POST", data)
      .then(res => {
        if (res.status === "ok") {
          customAlertSuccess("Elemento actualizado");

          $("#modalUpdate").modal("hide");
          table.ajax.reload();
        } else {
          alertError();
        }
      })
      .catch(err => console.log(err));
  } else {
    alertErrorFormEmpty();
  }
}

/* Abrir modal para retirar */
/**@param {object} table*/
function getDataWithdraw(table) {
  if (table.row(".selected").length > 0) {
    jQuery.noConflict();
    jQuery("#modalWithdraw");
    $("#modalWithdraw").modal("show");

    setModalWithdraw(table);
  } else {
    customAlertError("Seleccione un elemento");
  }
}

/* Pasar datos a los input del modal */
/**@param {object} table*/
function setModalWithdraw(table) {
  var data = table.row(".selected").data();
  //document.querySelector("#submitter").value = "Falta";
  document.querySelector("#receivedBy").value = "";
  document.querySelector("#mMarca").value = data.Marca;
  document.querySelector("#mModelo").value = data.Modelo;
  document.querySelector("#mTipo").value = data.Tipo;
  document.querySelector("#mCantidad").value = 1;
}

/**  */
/**@deprecated */
/**@description Confirmar retiro @param {object} table*/
function confirmWithdraw(table) {
  var data = table.row(".selected").data();
  let receivedBy = document.querySelector("#receivedBy");

  let cantidad = document.querySelector("#mCantidad").value.trim();

  if (receivedBy.value.trim() === "" || parseInt(data.Cantidad) < cantidad) {
    customAlertError(
      "La cantidad sobrepasa a la existente o hay algún campo vacío"
    );
  } else {
    //let usuarioRetira = document.querySelector("#submitter").value.trim();

    let form = new FormData();
    //form.append("usuarioRetira", usuarioRetira);
    form.append("usuarioRecibe", receivedBy.value.trim());
    form.append("marca", data.Marca);
    form.append("modelo", data.Modelo);
    form.append("tipo", data.Tipo);
    form.append("impresora", data.Impresora);
    form.append("cantidad", parseInt(cantidad));

    fetchURL("./api/retiro/insert_retiro.php", "POST", form)
      .then(res => {
        if (res.status === "bad") {
          alertError();
        } else {
          jQuery.noConflict();
          jQuery("#modalWithdraw");
          $("#modalWithdraw").modal("hide");
          updateCountCard();
          alertSuccess();
          table.ajax.reload();
        }
      })
      .catch(err => console.log(err));
  }
}

/**@description abrir modal para transferir, @param {object} table*/
function transfer(table) {
  if (table.row(".selected").length > 0) {
    /**obtener bodega del select */
    let changeStorage = document.querySelector("#change-storage");
    var textSelect = changeStorage.options[changeStorage.selectedIndex].text;

    /**pasar bodega al input desabilitado */
    document.querySelector("#current-storage").value = textSelect;

    /**resetear input select */
    document.getElementById(
      "transfer-select"
    ).innerHTML = `<option value="">Seleccione...</option>`;

    fetchURL("./api/bodega/bodegaGet.php?case=listStorage")
      .then(res => {
        let selectBodegas = res.data.filter(function(bodega) {
          return bodega.Lugar !== textSelect;
        });

        selectBodegas.forEach(bodega => {
          document.getElementById(
            "transfer-select"
          ).innerHTML += `<option value=${bodega.Id_bodega}>${bodega.Lugar}</option>`;
        });
      })
      .catch(err => {
        console.log(err);
      });

    document.querySelector("#current-storage").value = textSelect;

    jQuery.noConflict();
    jQuery("#modalTransfer");
    $("#modalTransfer").modal("show");

    document.querySelector("#amountTtoINF").value = 1;
  } else {
    customAlertError("Seleccione un elemento");
  }
}

/* Boton confirmar del  modal transferir del modal */
/**@param {object} table, @param {object} table */
function confirmTransfer(table) {
  let cantidad = document.querySelector("#amountTtoINF").value;
  let destino = document.querySelector("#transfer-select").value;

  if (cantidad != "" && destino != "") {
    var data = table.row(".selected").data();
    cantidad = parseInt(cantidad);

    if (cantidad <= parseInt(data.Cantidad)) {
      let formData = new FormData();

      formData.append("cantidad", cantidad);
      formData.append("marca", data.Marca);
      formData.append("modelo", data.Modelo);
      formData.append("tipo", data.Tipo);
      formData.append("origen", parseInt(data.Id_bodega));
      formData.append("destino", parseInt(destino));

      let config = {
        method: "POST",
        headers: {
          Accept: "application/json"
        },
        body: formData
      };

      fetch("./api/consumible/transfer_consumible.php", config)
        .then(response => {
          if (response.ok) {
            return response.json();
          } else {
            alertError();
          }
        })
        .then(json => {
          if (json.status === "ok") {
            updateCountCard();
            jQuery.noConflict();
            jQuery("#modalTransfer");
            $("#modalTransfer").modal("hide");
            changeStorage(table);
            alertSuccess();
          } else {
            alertError();
          }
        })
        .catch(err => {
          alertError();
          console.log(err);
        });
    } else {
      customAlertError("La cantidad especificada supera a la existente");
    }
  } else {
    alertErrorFormEmpty();
  }
}

/* -------------------------------------------------------------------------------------------------- */
/* Confirmar retiro */
/**@param {object} table */
function confirmWithdrawINF_MO(table) {
  var data = table.row(".selected").data();
  let receivedBy = document.querySelector("#receivedBy");
  let cantidad = document.querySelector("#mCantidad").value.trim();

  let selectores = ["#receivedBy"];
  validClass(selectores);

  if (receivedBy.value.trim() === "" || parseInt(data.Cantidad) < cantidad) {
    customAlertError(
      "La cantidad sobrepasa a la existente o hay algún campo vacío"
    );
  } else {
    //let usuarioRetira = document.querySelector("#submitter").value.trim();

    let form = new FormData();
    // form.append("usuarioRetira", usuarioRetira);
    form.append("usuarioRecibe", receivedBy.value.trim());
    form.append("marca", data.Marca);
    form.append("modelo", data.Modelo);
    form.append("tipo", data.Tipo);
    form.append("impresora", data.Impresora);
    form.append("cantidad", parseInt(cantidad));
    form.append("bodega", parseInt(data.Id_bodega));
    form.append("Nombrebodega", data.Lugar);

    let config = {
      method: "POST",
      headers: {
        Accept: "application/json"
      },
      body: form
    };

    fetch("./api/retiro/insert_retiroINF_MO.php", config)
      .then(response => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("Error en la llamada");
          alertError();
        }
      })
      .then(json => {
        if (json.status === "ok") {
          jQuery.noConflict();
          jQuery("#modalWithdraw");
          $("#modalWithdraw").modal("hide");
          //table.ajax.reload();
          alertSuccess();
          updateCountCard();
          changeStorage(table);
        } else {
          alertError();
        }
      })
      .catch(err => {
        console.log(err);
        alertError();
      });
  }
}

/* Datatable Todos */
function listALL() {
  /**Configuración Datatable*/
  var table = $("#tableALL").DataTable({
    destroy: true,
    //responsive: true,
    order: [[0, "desc"]],
    select: true,
    dom: "Bfrtip",
    buttons: [
      {
        extend: "pdf",
        text: "<span class='fas fa-file-pdf'></span>",
        exportOptions: {
          columns: [0, 1, 2, 3, 4]
          //columns: ":not(.no-exportar)"
        },
        titleAttr: "Generar reporte PDF",
        className: "btn btn-success",
        title: "Total en existencia",
        customize: function(doc) {
          doc.content[1].margin = [100, 10, 100, 0];
          doc.defaultStyle.alignment = "center";
          doc.footer = function(currentPage, pageCount) {
            return currentPage.toString() + " de " + pageCount;
          };
          doc.header = function() {
            return {
              text:
                "Emitido el " +
                new Date().getDate() +
                "/" +
                new Date().getMonth() +
                1 +
                "/" +
                new Date().getFullYear(),
              alignment: "left"
            };
          };
        }
      },
      {
        text: " <span class='fas fa-wrench text-white'></span>",
        titleAttr: "Actualizar",
        className: "btn btn-warning",
        action: function() {
          getDataUpdate(table);
        }
      }
    ],
    language: Datatable_ES,
    ajax: {
      method: "GET",
      url: "./api/consumible/get_consumibleALL.php"
    },

    columns: [
      { data: "Marca" },
      { data: "Modelo" },
      { data: "Tipo" },
      { data: "Cantidad" },
      {
        data: "Impresora"
      } /* ,
      {
        defaultContent:
          "<div class='btn-group btn-group-sm' role='group' aria-label='Basic example'><button class='btn btn-info' id='btnWithdraw'><span class='fas fa-box-open'></span></button><button  id='btnUpdate' class='btn btn-warning mx-2' ><span class='fas fa-wrench text-white'></span></button > <button class='btn btn-danger' id='btnDelete'><span class='fas fa-trash'></span></button></div>"
      } */
    ]
  });
  //getUpdate("#tableALL tbody", table);

  setInterval(function() {
    table.ajax.reload();
  }, 100000);

  return table;
}

/**@deprecated */
/* var getUpdate = function(tbody, table) {
  $(tbody).on("click", "#btnUpdate", function() {

    var data = table.row($(this).parents("tr")).data();
    jQuery.noConflict();
    jQuery("#modalUpdate");
    $("#modalUpdate").modal("show");

    let marca = data.Marca;
    let modelo = data.Modelo;
    let tipo = data.Tipo;
    let impresora = data.Impresora;
    setModalUpdate(marca, modelo, tipo, impresora);
  });
}; */

/**@description agregar modelos de impresoras en la lista del modal actualizar */
async function printModelPrinter() {
  //case: namePrinter
  /* resetear select */
  document.querySelector("#updImpresora").innerHTML =
    "<option value='' selected>Seleccione...</option>";
  let marca = document.querySelector("#updMarca");
  /*Consulta y llenar select con resultados */
  await fetch(
    `./api/impresora/impresora.php?case=namePrinter&&marca=${marca.value.toUpperCase()}`
  )
    .then(response => {
      return response.json();
    })
    .then(json => {
      json.forEach(impresora => {
        document.querySelector(
          "#updImpresora"
        ).innerHTML += `<option >${impresora.Impresora}</option>`;
      });
    })
    .catch(err => {
      console.log(err);
      alertError();
    });
}

/**@description agregar marcas de impresoras en la lista del modal actualizar */
async function printMarcaPrinter() {
  //case:printersBrand
  await fetch("./api/impresora/impresora.php?case=printersBrand")
    .then(response => {
      if (response.ok) {
        return response.json();
      }
    })
    .then(json => {
      if (json.marca[0].Marca_impresora != null) {
        for (const impresora of json.marca) {
          document.querySelector(
            "#updMarca"
          ).innerHTML += `<option>${impresora.Marca_impresora}</option>`;
        }
      } else {
        document.querySelector(
          "#updMarca"
        ).innerHTML += `<option value="">No hay impresoras en el sistema</option>`;
      }
    })
    .catch(err => {
      // alertError();
      console.log(err);
    });
}

function showContent() {
  /**Iniciar el select de bodegas */
  selectStorage(
    "change-storage",
    "./api/bodega/bodegaGet.php?case=listStorage"
  );

  document.getElementById("rowCards").innerHTML = "";

  fetchURL("./api/bodega/bodegaGet.php?case=listStorage")
    .then(function(res) {
      let i = 0;

      res.data.forEach(function(bodega) {
        if (i === 10) {
          i = 0;
        }
        showCards(bodega.Cantidad, bodega.Lugar, i);

        i++;
      });
    })
    .catch(function(err) {
      console.log(err);
    });
}

/**Actualizar el contador de las cartas */
function updateCountCard() {
  fetchURL("./api/bodega/bodegaGet.php?case=listStorage")
    .then(function(res) {
      let i = 0;
      let card = document.querySelectorAll(".idstg");
      res.data.forEach(function(bodega) {
        card[i++].innerHTML = bodega.Cantidad;
      });
    })
    .catch(function(err) {
      console.log(err);
    });
}

//Imprimir las pestañas de las tablas en pantalla
/**@deprecated */
/* function changeTabName(nombre, index, id) {
  let tab = document.getElementById("myTab");
  let tabContent = document.getElementById("myTabContent");

  tab.innerHTML += `<li class="nav-item">
  <a class="nav-link id-tab" data-toggle="tab" href="#tab-${id}" role="tab" aria-controls="tab-${id}" aria-selected="false">${nombre}</a>
</li>`;

     tabContent.innerHTML += `<div class="tab-pane fade" id="tab-${id}" role="tabpanel" aria-labelledby="tab-${id}">
<!-- Datatable de informatica -->
<div class="table-responsive">
  <table class="table table-bordered display nowrap text-center id-table" id="table${id}" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Tipo</th>
        <th>Cantidad</th>
        <th>Impresora</th>
      </tr>
    </thead>
    <tbody>

    </tbody>
  </table>
</div>
</div>`; 
} */

/**Imprimir las cartas de las bodegas en pantalla */
function showCards(cantidad, lugar, i) {
  const color = [
    "primary",
    "warning",
    "success",
    "danger",
    "info",
    "dark",
    "secondary",
    "light",
    "white"
  ];
  document.getElementById(
    "rowCards"
  ).innerHTML += `  <div class="col-xl-3 col-md-3 col-sm-6" style="margin-bottom:.5rem; margin-top:1rem">
  <div class="card text-white bg-${color[i]} o-hidden h-100">
    <div class="card-body">
      <div class="card-body-icon">
        <i class="fas fa-boxes"></i>
      </div>
      <div class="text-center font-weight-bold idstg">${cantidad}</div>
    </div>
    <a class="card-footer text-white clearfix small z-1">
      <span class="float-left font-weight-bold text-capitalize">${lugar}</span>
    </a>
  </div>
</div>`;
}

/* DataTabla de bodegas */
function listStorages() {
  var table = $("#tableBodegas").DataTable({
    destroy: true,
    //responsive: true,
    order: [[0, "desc"]],
    select: true,
    language: Datatable_ES,
    ajax: {
      method: "GET",
      url: "./api/consumible/get_consumibles.php",
      data: {
        bodega: parseInt(document.querySelector("#change-storage").value)
      }
    },
    dom: "Bfrtip",
    buttons: [
      /*  {
        extend: "pdf",
        text: "<span class='fas fa-file-pdf'></span>",
        titleAttr: "PDF",
        className: "btn btn-success",
        title: `Consumibles en ${
          document.querySelector("#transfer-select").value
        }`,
        customize: function(doc) {
          doc.content[1].margin = [100, 10, 100, 0];
          doc.defaultStyle.alignment = "center";
          doc.footer = function(currentPage, pageCount) {
            return currentPage.toString() + " de " + pageCount;
          };
          doc.header = function() {
            return {
              text:
                "Emitido el " +
                new Date().getDate() +
                "/" +
                new Date().getMonth() +
                1 +
                "/" +
                new Date().getFullYear(),
              alignment: "left"
            };
          };
        }
      }, */
      {
        text: " <span class='fas fa-box-open text-white'></span>",
        titleAttr: "Retirar",
        className: "btn btn-info",
        action: function() {
          getDataWithdraw(table);
        }
      },
      {
        text: "<span class='fas fa-exchange-alt text-white'></span>",
        titleAttr: "Trasladar",
        className: "btn btn-dark",
        action: function() {
          transfer(table);
        }
      },
      {
        text: " <span class='fas fa-trash'></span>",
        titleAttr: "Eliminar",
        className: "btn btn-danger",
        action: function() {
          getDataDelete(table);
        }
      }
    ],
    columns: [
      { data: "Marca" },
      { data: "Modelo" },
      { data: "Tipo" },
      { data: "Cantidad" },
      { data: "Impresora" }
    ]
  });

  setInterval(function() {
    table.ajax.reload();
  }, 100000);

  return table;
}

/**Cambiar la informacion de la datatable bodega */
function changeStorage(table) {
  let value = document.querySelector("#change-storage").value;
  fetchURL(`./api/consumible/get_consumibles.php?bodega=${value}`, "GET")
    .then(function(res) {
      if (res.data[0].Marca != "") {
        table.rows().remove();
        table.rows.add(res.data).draw();
        //alertSuccess();
      } else {
        table.rows().remove();
        table.rows.add(res.data).draw();
        alertWarning("Nada encontrado");
      }
    })
    .catch(function(err) {
      console.log(err);
    });
}
