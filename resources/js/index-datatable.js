"use strict";

document.addEventListener("DOMContentLoaded", function() {
  showContent();
  autocompletar();
  /**Las tablas y eventos relacionados cargan 1 segundo despues de cargar las cartas, para evitar conflictos de variables desconocidas */
  setTimeout(() => {
    //Configuracion para cuando la tabla esta oculta en un tab
    $('a[data-toggle="tab"]').on("shown.bs.tab", function(e) {
      $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
    //--------------------------

    //Inicializar tablas
    var tableStorages = listStorages();
    var tableAll = listALL();
    //----------------------------------

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

    /* Boton retirar del modal */
    document.querySelector("#btnModalWithdraw").addEventListener("click", e => {
      e.preventDefault();
      e.stopPropagation();
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

    document.getElementById("max-transfer").addEventListener("click", () => {
      var data = tableStorages.row(".selected").data();
      document.getElementById("amountTtoINF").value = data.Cantidad;
    });

    document.getElementById("max-delete").addEventListener("click", () => {
      var data = tableStorages.row(".selected").data();
      document.getElementById("cantDelete").value = data.Cantidad;
    });

    document.getElementById("max-withdraw").addEventListener("click", () => {
      var data = tableStorages.row(".selected").data();
      document.getElementById("mCantidad").value = data.Cantidad;
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
  let input = document.getElementById("cantDelete");
  let cant_delete = parseInt(input.value);

  let selectores = ["#cantDelete"];
  validClass(selectores);

  cant_delete = parseInt(cant_delete);
  if (cant_delete <= parseInt(data.Cantidad) && cant_delete > 0) {
    const dataDelete = new FormData();
    dataDelete.append("cantidad", parseInt(cant_delete));
    dataDelete.append("id_bodega", parseInt(data.Id_bodega));
    dataDelete.append("Id_consumible", parseInt(data.Id_consumible));
    dataDelete.append("case", "deleteCon");

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
  } else if (cant_delete > parseInt(data.Cantidad)) {
    customAlertError("La cantidad seleccionada sobrepasa la que existe");
    input.classList.add("is-invalid");
  } else if (cant_delete <= 0) {
    customAlertError(
      "La cantidad seleccionada no puede ser menor o igual a cero"
    );
    input.classList.add("is-invalid");
  } else {
    alertErrorFormEmpty();
    input.classList.add("is-invalid");
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
    /**Al cerrar el modal las validaciones desaparecen */
    $("#modalWithdraw").on("hidden.bs.modal", function() {
      let selectores = ["#receivedBy", "#mCantidad"];
      clean_Validations(selectores);
    });
  } else {
    customAlertError("Seleccione un elemento");
  }
}

/* Pasar datos a los input del modal */
/**@param {object} table*/
function setModalWithdraw(table) {
  var data = table.row(".selected").data();

  document.querySelector("#receivedBy").value = "";
  document.querySelector("#mMarca").value = data.Marca;
  document.querySelector("#mModelo").value = data.Modelo;
  document.querySelector("#mTipo").value = data.Tipo;
  document.querySelector("#mCantidad").value = 1;
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

    /**Al cerrar el modal las validaciones desaparecen */
    $("#modalTransfer").on("hidden.bs.modal", function() {
      let selectores = ["#amountTtoINF", "#transfer-select"];
      clean_Validations(selectores);
    });
  } else {
    customAlertError("Seleccione un elemento");
  }
}

/* Boton confirmar del  modal transferir del modal */
/**@param {object} table, @param {object} table */
function confirmTransfer(table) {
  let input = document.querySelector("#amountTtoINF");
  let destino = document.querySelector("#transfer-select").value;

  let cantidad = parseInt(input.value);

  let selectores = ["#amountTtoINF", "#transfer-select"];
  validClass(selectores);

  if (cantidad != "" && destino != "" && cantidad > 0) {
    var data = table.row(".selected").data();

    if (cantidad <= parseInt(data.Cantidad)) {
      let formData = new FormData();

      formData.append("cantidad", cantidad);
      formData.append("origen", parseInt(data.Id_bodega));
      formData.append("destino", parseInt(destino));
      formData.append("Id_consumible", parseInt(data.Id_consumible));

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
            clean_Validations(selectores);
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
      input.classList.add("is-invalid");
    }
  } else if (cantidad <= 0) {
    customAlertError(
      "La cantidad seleccionada no puede ser menor o igual a cero"
    );
    input.classList.add("is-invalid");
  } else if (isNaN(cantidad) || destino === "") {
    alertErrorFormEmpty();
  }
}

/* -------------------------------------------------------------------------------------------------- */
/* Confirmar retiro */
/**@param {object} table */
function confirmWithdrawINF_MO(table) {
  var data = table.row(".selected").data();
  let receivedBy = document.querySelector("#receivedBy").value.trim();
  let cantidad = document.querySelector("#mCantidad").value.trim();

  let input = document.querySelector("#mCantidad");

  cantidad = parseInt(cantidad);
  let selectores = ["#receivedBy", "#mCantidad"];
  validClass(selectores);

  if (receivedBy === "" || isNaN(cantidad)) {
    alertErrorFormEmpty();
  } else if (parseInt(data.Cantidad) < cantidad) {
    //Comprobar que la cantidad sea mayor a 0 y menor o igual a la que existe
    customAlertError("La cantidad sobrepasa a la existente");
    input.classList.add("is-invalid");
  } else if (cantidad <= 0) {
    customAlertError(
      "La cantidad seleccionada no puede ser menor o igual a cero"
    );
    input.classList.add("is-invalid");
  } else {
    //Proceder a efectuar el retiro
    let form = new FormData();
    form.append("usuarioRecibe", receivedBy);
    form.append("marca", data.Marca);
    form.append("modelo", data.Modelo);
    form.append("tipo", data.Tipo);
    form.append("impresora", data.Impresora);
    form.append("id_impresora", data.Id_impresora);
    form.append("cantidad", parseInt(cantidad));
    form.append("bodega", parseInt(data.Id_bodega));
    form.append("nombreBodega", data.Lugar);
    form.append("Id_consumible", data.Id_consumible);

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
          table.ajax.reload();
          alertSuccess();
          updateCountCard();
          changeStorage(table);
          clean_Validations(selectores);
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
      }
    ],
    language: Datatable_ES,
    ajax: {
      method: "GET",
      url: "./api/consumible/get_consumibleALL.php",
      data: { case: "allConsumables" }
    },
    /**Colores en las celdas de cantidad */
    createdRow: function(row, data, dataIndex) {
      let cantidad = parseInt(data.Cantidad);
      let minimo = parseInt(data.Minimo);
      let maximo = parseInt(data.Maximo);

      if (cantidad < minimo) {
        $($(row).find("td")[5]).addClass("bg-danger text-white");
      } else if (cantidad > maximo) {
        $($(row).find("td")[5]).addClass("bg-success text-white");
      } else {
        $($(row).find("td")[5]).addClass("bg-warning text-white");
      }
    },
    columns: [
      { data: "Marca" },
      { data: "Modelo" },
      { data: "Tipo" },
      { data: "Cantidad" },
      {
        data: "Impresora"
      },
      {
        data: "Estado"
      }
    ]
  });
  //getUpdate("#tableALL tbody", table);

  setInterval(function() {
    table.ajax.reload();
  }, 100000);

  return table;
}

/**Mostrar cartas con nombres y cantidad */
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
  ).innerHTML += `  <div class="col-xl-3 col-md-3 col-sm-12" style="margin-bottom:.5rem; margin-top:1rem">
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
