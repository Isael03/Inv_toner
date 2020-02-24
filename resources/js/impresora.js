document.addEventListener("DOMContentLoaded", () => {
  let table = tablePrinter();

  document.getElementById("PrinterDelete").addEventListener("click", () => {
    showDelete(table);
  });

  document.getElementById("PrinterUpdate").addEventListener("click", () => {
    showFormUpdate(table);
  });

  document.querySelector("#btnNuevaImpresora").addEventListener("click", e => {
    e.preventDefault();
    e.stopPropagation();
    insertNewPrinter(table);
  });

  document.querySelector("#contentTable").addEventListener("click", () => {
    showFormAndControl(table);
  });

  document.querySelector("#btnUpdatePrinter").addEventListener("click", e => {
    e.preventDefault();
    e.stopPropagation();
    updatePrinter(table);
  });

  document
    .querySelector("#btnModalDeletePrinter")
    .addEventListener("click", e => {
      e.preventDefault();
      e.stopPropagation();
      deletePrinter(table);
    });

  document
    .querySelector("#contentTable")
    .addEventListener("click", () => dataForm(table));
});

/**Datatable de impresoras */
function tablePrinter() {
  /**Configuración Datatable*/
  var table = $("#tablePrinters").DataTable({
    destroy: true,
    responsive: false,
    //select: true,
    columnDefs: [
      {
        orderable: false,
        className: "select-checkbox",
        targets: 0,
        data: null,
        defaultContent: ""
      }
    ],
    select: {
      style: "os",
      selector: "td"
    },
    language: Datatable_ES,
    ajax: {
      method: "GET",
      url: "../api/impresora/impresora.php",
      data: { case: "showPrinters" }
    },
    columns: [{}, { data: "Marca_impresora" }, { data: "Modelo_impresora" }]
  });

  return table;
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
function insertNewPrinter(table) {
  let marca = document.querySelector("#nuevaMarca").value.trim();
  let modelo = document.querySelector("#nuevoModelo").value.trim();

  validClass(["#nuevaMarca", "#nuevoModelo"]);

  if (marca != "" && modelo != "") {
    let data = new FormData();

    modelo = modelo.replace(" ", "-");
    data.append("marca", marca.toUpperCase());
    data.append("modelo", modelo.toUpperCase());
    data.append("case", "newPrinter");

    fetchURL("../api/impresora/impresora.php", "POST", data).then(res => {
      if (res.status === "ok") {
        alertSuccess();
        cleanFormPrinter();
        table.ajax.reload();
      } else {
        alertError();
      }
    });
  } else {
    alertErrorFormEmpty();
  }
}

/**@description Limpiar input de los formularios ingresar y actualizar */
function cleanFormPrinter() {
  document.querySelector("#nuevaMarca").value = "";
  document.querySelector("#nuevoModelo").value = "";
  document.querySelector("#updateMarcaPrinter").value = "";
  document.querySelector("#updateModeloPrinter").value = "";
  clean_Validations(["#nuevaMarca", "#nuevoModelo"]);
}

/**@description Modificar impresora */
function updatePrinter(table) {
  let marca = document.querySelector("#updateMarcaPrinter").value.trim();
  let modelo = document.querySelector("#updateModeloPrinter").value.trim();
  let selectores = ["#updateMarcaPrinter", "#updateModeloPrinter"];
  validClass(selectores);
  if (marca != "" && modelo != "") {
    let data = new FormData();

    const dataTable = table.row(".selected").data();
    modelo = modelo.replace(" ", "-");
    data.append("id", parseInt(dataTable.Id_impresora));
    data.append("marca", marca.toUpperCase());
    data.append("modelo", modelo.toUpperCase());
    data.append("case", "updatePrinter");

    fetchURL("../api/impresora/impresora.php", "POST", data).then(res => {
      if (res.status === "ok") {
        alertSuccess();
        cleanFormPrinter();
        table.ajax.reload();
        jQuery.noConflict();
        $("#update-impresora").modal("hide");
      } else {
        alertError();
      }
    });
  } else {
    alertErrorFormEmpty();
  }
}

/**@description Verificar que alguna fila de la tabla este seleccionada antes de mostrar el formulario para actualizar */
/**@param table object  */
function showFormUpdate(table) {
  if (table.row(".selected").length > 0) {
    jQuery.noConflict();
    jQuery("#update-impresora");
    $("#update-impresora").modal("show");

    let selectores = ["#updateMarcaPrinter", "#updateModeloPrinter"];
    $("#update-impresora").on("hidden.bs.modal", function() {
      clean_Validations(selectores);
    });
  } else {
    customAlertError("Seleccione un elemento");
  }
}

/**@description Pasar datos al formulario actualizar */
function printDataUpdate(data) {
  document.querySelector("#updateMarcaPrinter").value = data.Marca_impresora;
  document.querySelector("#updateModeloPrinter").value = data.Modelo_impresora;
}

/**@description Borrar impresora */
function deletePrinter(table) {
  const dataTable = table.row(".selected").data();

  let data = new FormData();

  data.append("id", parseInt(dataTable.Id_impresora));
  data.append("case", "deletePrinter");

  fetchURL("../api/impresora/impresora.php", "POST", data).then(res => {
    if (res.status === "ok") {
      alertSuccess();
      cleanFormPrinter();
      table.ajax.reload();
      jQuery.noConflict();
      $("#modalDeletePrinter").modal("hide");
    } else {
      alertError();
    }
  });
}

function dataForm(table) {
  if (table.row(".selected").length > 0) {
    const data = table.row(".selected").data();
    printDataUpdate(data);
  }
}

function showFormAndControl(table) {
  jQuery.noConflict();
  jQuery("#container-btn");
  if (table.row(".selected").length > 0) {
    $("#container-btn").collapse("show");
  } else {
    $("#container-btn").collapse("hide");
  }
}
