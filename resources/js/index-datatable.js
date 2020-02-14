"use strict";

document.addEventListener("DOMContentLoaded", function() {
  showContent();
  autocompletar();

  /**Las tablas y eventos relacionados cargan 1 segundo despues de cargar las cartas, para evitar conflictos de variables desconocidas */
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

/**@deprecated */
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
  } else {
    customAlertError("La cantidad seleccionada sobrepasa la que existe");
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

      formData.append("cantidad", parseInt(cantidad));
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
    let form = new FormData();
    form.append("usuarioRecibe", receivedBy.value.trim());
    form.append("marca", data.Marca);
    form.append("modelo", data.Modelo);
    form.append("tipo", data.Tipo);
    form.append("impresora", data.Impresora);
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
    select: false,
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
      if (data.Cantidad < data.Minimo) {
        $($(row).find("td")[3]).addClass("bg-danger text-white");
      }
      if (data.Cantidad >= data.Minimo && data.Cantidad <= data.Maximo) {
        $($(row).find("td")[3]).addClass("bg-warning text-white");
      }
      if (data.Cantidad > data.Maximo) {
        $($(row).find("td")[3]).addClass("bg-success text-white");
      }
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
      <div class="text-center font-weight-bold">${cantidad}</div>
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
