"use strict";
document.addEventListener("DOMContentLoaded", () => {
  let table = tablePrinter();

  document.querySelector("#btnNuevaImpresora").addEventListener("click", e => {
    insertNewPrinter(table);
  });

  document.querySelector("#btnUpdatePrinter").addEventListener("click", e => {
    updatePrinter(table);
  });

  document
    .querySelector("#btnModalDeletePrinter")
    .addEventListener("click", e => {
      deletePrinter(table);
    });

  document.addEventListener("click", () => {
    if (table.row(".selected").length > 0) {
      const data = table.row(".selected").data();
      printDataUpdate(data);
    }
  });
});

function tablePrinter() {
  /**Configuración Datatable*/
  var table = $("#tablePrinters").DataTable({
    destroy: true,
    //responsive: true,
    select: true,
    dom: "Bfrtip",
    buttons: [
      {
        text: " <span class='fas fa-wrench text-white'></span>",
        titleAttr: "Actualizar",
        className: "btn btn-warning",
        action: function() {
          showFormUpdate(table);
        }
      },
      {
        text: " <span class='fas fa-trash'></span>",
        titleAttr: "Eliminar",
        className: "btn btn-danger",
        action: function() {
          showDelete(table);
        }
      }
    ],
    language: Datatable_ES,
    ajax: {
      method: "GET",
      url: "../api/impresora/impresora.php",
      data: { case: "showPrinters" }
    },
    columns: [{ data: "Marca_impresora" }, { data: "Modelo_impresora" }]
  });

  setInterval(function() {
    table.ajax.reload();
  }, 100000);

  return table;
}

function changeForm() {
  /*  let insertForm = document.querySelector("#insertPrinter");
  let updateForm = document.querySelector("#updatePrinter");*/

  let updateForm = document.querySelector("#updatePrinter");
  jQuery.noConflict();

  if (updateForm.classList.contains("show") === false) {
    $("#insertPrinter").collapse("hide");

    $("#insertPrinter").on("hidden.bs.collapse", () => {
      $("#updatePrinter").collapse("show");
    });
  }

  if (updateForm.classList.contains("show")) {
    $("#updatePrinter").collapse("hide");

    $("#updatePrinter").on("hidden.bs.collapse", () => {
      $("#insertPrinter").collapse("show");
    });
  }
}

/**@description Mostrar modal para eliminar */

function showDelete(table) {
  if (table.row(".selected").length > 0) {
    jQuery.noConflict();
    $("#modalDeletePrinter").modal("show");
  } else {
    customAlertError("Seleccione un elemento");
  }
}

/**@description Enviar datos del formulario  */
/**@param table object  */

async function insertNewPrinter(table) {
  let marca = document.querySelector("#nuevaMarca").value;
  let modelo = document.querySelector("#nuevoModelo").value;

  if (marca != "" && modelo != "") {
    let data = new FormData();

    data.append("marca", marca.toUpperCase());
    data.append("modelo", modelo.toUpperCase());
    data.append("case", "newPrinter");

    await fetch("../api/impresora/impresora.php", {
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
          alertSuccess();

          cleanFormPrinter();

          table.ajax.reload();
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

function cleanFormPrinter() {
  document.querySelector("#nuevaMarca").value = "";
  document.querySelector("#nuevoModelo").value = "";
  document.querySelector("#updateMarcaPrinter").value = "";
  document.querySelector("#updateModeloPrinter").value = "";
}

async function updatePrinter(table) {
  let marca = document.querySelector("#updateMarcaPrinter").value.trim();
  let modelo = document.querySelector("#updateModeloPrinter").value.trim();

  if (marca != "" && modelo != "") {
    let data = new FormData();

    const dataTable = table.row(".selected").data();

    data.append("id", parseInt(dataTable.Id_impresora));
    data.append("marca", marca.toUpperCase());
    data.append("modelo", modelo.toUpperCase());
    data.append("case", "updatePrinter");

    await fetch("../api/impresora/impresora.php", {
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
          alertSuccess();

          cleanFormPrinter();

          table.ajax.reload();
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

/**@description Verificar que alguna fila de la tabla este seleccionada antes de mostrar el formulario para actualizar */
/**@param table object  */

function showFormUpdate(table) {
  if (table.row(".selected").length > 0) {
    changeForm();
    const data = table.row(".selected").data();
    printDataUpdate(data);
  } else {
    customAlertError("Seleccione un elemento");
  }
}

function printDataUpdate(data) {
  document.querySelector("#updateMarcaPrinter").value = data.Marca_impresora;
  document.querySelector("#updateModeloPrinter").value = data.Modelo_impresora;
}

async function deletePrinter(table) {
  const dataTable = table.row(".selected").data();

  let data = new FormData();

  data.append("id", parseInt(dataTable.Id_impresora));
  data.append("case", "deletePrinter");

  await fetch("../api/impresora/impresora.php", {
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
        alertSuccess();

        cleanFormPrinter();

        table.ajax.reload();
      } else {
        alertError();
      }
    })
    .catch(err => {
      console.log(err);
      alertError();
    });
}
