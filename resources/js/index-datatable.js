"use strict";
document.addEventListener("DOMContentLoaded", function() {
  const tableINF = listINF();
  const tableMO = listMO();
  const tableAll = listALL();
  amountHeld();
  autocompletar();

  /*------------------------- Botones de modals ----------------------------*/

  /* boton eliminar del modal */
  document.querySelector("#btnModalDelete").addEventListener("click", () => {
    confirmDelete(tableAll);
  });

  /* boton actualizar del modal */
  document.querySelector("#btnModalUpdate").addEventListener("click", () => {
    confirmUpdate(tableAll);
  });

  /* Boton retirar del modal */
  document.querySelector("#btnModalWithdraw").addEventListener("click", () => {
    if (tableAll.row(".selected").length > 0) {
      confirmWithdraw(tableAll);
    }
    if (tableMO.row(".selected").length > 0) {
      confirmWithdrawINF_MO(tableMO);
    }
    if (tableINF.row(".selected").length > 0) {
      confirmWithdrawINF_MO(tableINF);
    }
  });

  document.querySelector("#btnModalTransfer").addEventListener("click", () => {
    if (tableMO.row(".selected").length > 0) {
      confirmTransfer(tableMO, tableINF);
    }
    if (tableINF.row(".selected").length > 0) {
      confirmTransfer(tableINF, tableMO);
    }
  });

  /* Desmarcar filas seleccionadas al cambiar la pestaña de la tabla */
  document.querySelector("#tabAll").addEventListener("click", () => {
    tableMO.rows().deselect();
    tableINF.rows().deselect();
  });
  document.querySelector("#tabINF").addEventListener("click", () => {
    tableMO.rows().deselect();
    tableAll.rows().deselect();
  });
  document.querySelector("#tabMO").addEventListener("click", () => {
    tableAll.rows().deselect();
    tableINF.rows().deselect();
  });
});

/* Acción modificar */
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

/* Acción boton eliminar */
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

/* Enviar id para eliminar */
function confirmDelete(table) {
  const data = table.row(".selected").data();
  let cant_delete = document.getElementById("cantDelete").value;

  if (cant_delete <= parseInt(data.Cantidad) || cant_delete === 0) {
    const dataDelete = new FormData();
    dataDelete.append("cantidad", parseInt(cant_delete));
    dataDelete.append("modelo", data.Modelo.toUpperCase());
    dataDelete.append("marca", data.Marca.toUpperCase());
    dataDelete.append("tipo", data.Tipo);

    fetch("./api/consumible/delete_consumible.php", {
      method: "POST",
      headers: { Accept: "application/json" },
      body: dataDelete
    })
      .then(response => {
        if (response.ok) {
          return response.json();
        } else {
          alertError();
          throw "Error en la llamada";
        }
      })
      .then(json => {
        if (json.status === "ok") {
          customAlertSuccess("Elemento eliminado");
          table.ajax.reload();
          //Ocultar modal
          $("#modalDelete").modal("hide");
          amountHeld();
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
function setModalUpdate(marca, modelo, tipo, impresora) {
  document.getElementById("updMarca").value = marca;
  document.getElementById("updModelo").value = modelo;
  document.getElementById("updTipo").value = tipo;
  document.getElementById("updImpresora").value = impresora;
}

/* validacion de datos del modal modificar */
function validFields() {
  let marca = document.getElementById("updMarca").value;
  let modelo = document.getElementById("updModelo").value;
  let tipo = document.getElementById("updTipo").value;
  let modelo_impresora = document.getElementById("updImpresora").value;

  if (marca != "" && modelo != "" && tipo != "" && modelo_impresora != "") {
    return true;
  } else {
    return false;
  }
}

/* Botón confirmación del modal modificar */
async function confirmUpdate(table) {
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

    await fetch("./api/consumible/update_consumible.php", {
      method: "POST",
      headers: {
        Accept: "application/json"
      },
      body: data
    })
      .then(response => {
        if (response.ok) {
          return response.json();
        } else {
          alertError();
          console.log("Error en la llamada");
        }
      })
      .then(json => {
        if (json.status === "ok") {
          customAlertSuccess("Elemento actualizado");

          $("#modalUpdate").modal("hide");
          table.ajax.reload();
          // setTimeout(() => document.location.reload(), 1000);
        } else {
          alertError();
        }
      })
      .catch(err => {
        console.log(err);
        alertError();
      });
  } else {
    alertErrorFormEmpty();
  }
}

/* Abrir modal para retirar */
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
function setModalWithdraw(table) {
  var data = table.row(".selected").data();

  document.querySelector("#submitter").value = "Falta";
  document.querySelector("#receivedBy").value = "";
  document.querySelector("#mMarca").value = data.Marca;
  document.querySelector("#mModelo").value = data.Modelo;
  document.querySelector("#mTipo").value = data.Tipo;
  document.querySelector("#mCantidad").value = 1;
}

/* Confirmar retiro */
function confirmWithdraw(table) {
  var data = table.row(".selected").data();
  let receivedBy = document.querySelector("#receivedBy");

  let cantidad = document.querySelector("#mCantidad").value.trim();

  if (receivedBy.value.trim() === "" || parseInt(data.Cantidad) < cantidad) {
    customAlertError(
      "La cantidad sobrepasa a la existente o hay algún campo vacío"
    );
  } else {
    let usuarioRetira = document.querySelector("#submitter").value.trim();

    let form = new FormData();
    form.append("usuarioRetira", usuarioRetira);
    form.append("usuarioRecibe", receivedBy.value.trim());
    form.append("marca", data.Marca);
    form.append("modelo", data.Modelo);
    form.append("tipo", data.Tipo);
    form.append("impresora", data.Impresora);
    form.append("cantidad", parseInt(cantidad));

    let config = {
      method: "POST",
      headers: {
        Accept: "application/json"
      },
      body: form
    };

    fetch("./api/retiro/insert_retiro.php", config)
      .then(response => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("Error en la llamada");
          alertError();
        }
      })
      .then(json => {
        if (json.status === "bad") {
          alertError();
        } else {
          jQuery.noConflict();
          jQuery("#modalWithdraw");
          $("#modalWithdraw").modal("hide");

          alertSuccess();
          table.ajax.reload();
        }
      })
      .catch(err => {
        alertError();
        console.log(err);
      });
  }
}

async function amountHeld() {
  let config = {
    method: "GET",
    headers: {
      Accept: "application/json"
    }
  };
  await fetch("./api/bodega/stock_quantity.php", config)
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        console.log("Error en la llamada");
      }
    })
    .then(json => {
      document.querySelector("#amount-inf").innerHTML = json.INF.Cantidad_INF;
      document.querySelector("#amount-mo").innerHTML = json.MO.Cantidad_MO;
    })
    .catch(err => console.log(err));
}

function transfer(table) {
  if (table.row(".selected").length > 0) {
    var data = table.row(".selected").data();

    /* Titulo del modal transferir a .... */
    data.Lugar === "Manuel Orella"
      ? (document.querySelector("#titleTransfer").innerHTML = "Informática")
      : (document.querySelector("#titleTransfer").innerHTML = "Manuel Orella");

    jQuery.noConflict();
    jQuery("#modalTransfer");
    $("#modalTransfer").modal("show");

    document.querySelector("#amountTtoINF").value = 1;
  } else {
    customAlertError("Seleccione un elemento");
  }
}

/* Boton confirmar del  modal transferir del modal */
function confirmTransfer(table, table2) {
  let cantidad = document.querySelector("#amountTtoINF").value;
  if (cantidad != "") {
    var data = table.row(".selected").data();
    cantidad = parseInt(cantidad);
    console.log(data);

    if (cantidad <= parseInt(data.Cantidad)) {
      let formData = new FormData();

      let destino;
      if (data.Lugar === "Manuel Orella") {
        destino = "Informatica";
      }
      if (data.Lugar === "Informatica") {
        destino = "Manuel Orella";
      }

      formData.append("cantidad", cantidad);
      formData.append("marca", data.Marca);
      formData.append("modelo", data.Modelo);
      formData.append("tipo", data.Tipo);
      formData.append("origen", data.Lugar);
      formData.append("destino", destino);

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
            jQuery.noConflict();
            jQuery("#modalTransfer");
            $("#modalTransfer").modal("hide");
            table.ajax.reload();
            table2.ajax.reload();

            alertSuccess();
          } else {
            alertError();
          }
        })
        .catch(err => console.log(err));
    } else {
      customAlertError("La cantidad especificada supera a la existente");
    }
  } else {
    alertErrorFormEmpty();
  }
}

/* -------------------------------------------------------------------------------------------------- */
/* Confirmar retiro */
function confirmWithdrawINF_MO(table) {
  var data = table.row(".selected").data();
  let receivedBy = document.querySelector("#receivedBy");
  let cantidad = document.querySelector("#mCantidad").value.trim();

  if (receivedBy.value.trim() === "" || parseInt(data.Cantidad) < cantidad) {
    customAlertError(
      "La cantidad sobrepasa a la existente o hay algún campo vacío"
    );
  } else {
    let usuarioRetira = document.querySelector("#submitter").value.trim();

    let form = new FormData();
    form.append("usuarioRetira", usuarioRetira);
    form.append("usuarioRecibe", receivedBy.value.trim());
    form.append("marca", data.Marca);
    form.append("modelo", data.Modelo);
    form.append("tipo", data.Tipo);
    form.append("impresora", data.Impresora);
    form.append("cantidad", parseInt(cantidad));
    form.append("bodega", data.Lugar);

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

          alertSuccess();
          table.ajax.reload();
        } else {
          alertError();
        }
      })
      .catch(err => console.log(err));
  }
}

/* Datatable Todos */
function listALL() {
  /**Configuración Datatable*/
  /** Variable Datatable_ES se encuentra en /scripts/main.js */
  var table = $("#tableALL").DataTable({
    destroy: true,
    //responsive: true,
    //scrollX: true,
    order: [[0, "desc"]],
    select: true,
    dom: "Bfrtip",
    buttons: [
      {
        extend: "pdf",
        text: "<span class='fas fa-file-pdf'></span>",
        titleAttr: "PDF",
        className: "btn btn-success",
        title:
          "En existencia " +
          new Date().getDate() +
          "/" +
          new Date().getMonth() +
          1 +
          "/" +
          new Date().getFullYear()
      },
      {
        text: " <span class='fas fa-box-open text-white'></span>",
        titleAttr: "Retirar",
        className: "btn btn-info",
        action: function() {
          getDataWithdraw(table);
        }
      },
      {
        text: " <span class='fas fa-wrench text-white'></span>",
        titleAttr: "Actualizar",
        className: "btn btn-warning",
        action: function() {
          getDataUpdate(table);
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
      { data: "Impresora" }
    ]
  });

  setInterval(function() {
    table.ajax.reload();
  }, 100000);

  return table;
}

/* Datatable Manuel Orella */
function listMO() {
  /**Configuración Datatable*/
  /** Variable Datatable_ES se encuentra en /scripts/main.js */
  var table = $("#tableMO").DataTable({
    destroy: true,
    //responsive: true,
    //scrollX: true,
    order: [[0, "desc"]],
    select: true,
    language: Datatable_ES,
    ajax: {
      method: "GET",
      url: "./api/consumible/get_consumibleMO.php"
    },
    columns: [
      { data: "Marca" },
      { data: "Modelo" },
      { data: "Tipo" },
      { data: "Cantidad" },
      {
        data: "Impresora"
      }
    ],
    dom: "Bfrtip",
    buttons: [
      {
        extend: "pdf",
        text: "<span class='fas fa-file-pdf'></span>",
        titleAttr: "PDF",
        className: "btn btn-success",
        title:
          "En existencia " +
          new Date().getDate() +
          "/" +
          new Date().getMonth() +
          1 +
          "/" +
          new Date().getFullYear()
      },
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
        titleAttr: "trasladar",
        className: "btn btn-dark",
        action: function() {
          transfer(table);
        }
      }
    ]
  });

  setInterval(function() {
    table.ajax.reload();
  }, 100000);

  return table;
}

function listINF() {
  /**Configuración Datatable*/
  /** Variable Datatable_ES se encuentra en /scripts/main.js */
  var table = $("#tableINF").DataTable({
    destroy: true,
    //responsive: true,
    //scrollX: true,
    order: [[0, "desc"]],
    select: true,
    language: Datatable_ES,
    ajax: {
      method: "GET",
      url: "./api/consumible/get_consumibleINF.php"
    },
    dom: "Bfrtip",
    buttons: [
      {
        extend: "pdf",
        text: "<span class='fas fa-file-pdf'></span>",
        titleAttr: "PDF",
        className: "btn btn-success",
        title:
          "En existencia " +
          new Date().getDate() +
          "/" +
          new Date().getMonth() +
          1 +
          "/" +
          new Date().getFullYear()
      },
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
