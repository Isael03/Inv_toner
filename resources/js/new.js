document.addEventListener("DOMContentLoaded", function() {
  const table = tableListConsumables();

  document
    .querySelector("#container-tableListConsumable")
    .addEventListener("click", () => {
      showFormAndControl(table);
    });

  //Configuracion para cuando la tabla esta oculta en un tab
  $('a[data-toggle="tab"]').on("shown.bs.tab", function(e) {
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
  });
  //--------------------------

  printMarcaPrinter();

  /**Añadir las bodegas al select input */
  selectStorage(
    "selectUbicacion",
    "../api/bodega/bodegaGet.php?case=listStorage"
  );
  selectStorage(
    "selectStorage",
    "../api/bodega/bodegaGet.php?case=listStorage"
  );

  /**Resetear contador de cantidad de form de existente a 1 */
  document.getElementById("addMore").value = 1;

  document.querySelector("#inputMarca").addEventListener("input", () => {
    document.querySelector(
      "#modelo_imp"
    ).innerHTML = `<option value=''>Seleccione...</option>`;
    let marca = document.querySelector("#inputMarca");
    printModelPrinter(marca);
  });

  document.querySelector("#btnAddMore").addEventListener("click", () => {
    sendDataExists(table);
  });

  /* boton actualizar del modal */
  document.querySelector("#btnModalUpdate").addEventListener("click", () => {
    confirmUpdate(table);
  });

  /* Evento del input marca del modal actualizar */
  document.querySelector("#updMarca").addEventListener("input", () => {
    printModelPrinterIPD();
    document.querySelector(
      "#updImpresora"
    ).innerHTML = `<option value="">Seleccione...</option>`;
  });

  /**Evento del boton eliminar del modal */
  document.getElementById("btnDeleteAll").addEventListener("click", function() {
    confirmDeleteAll(table);
  });

  /**Desmarcar fila selecionada al pulsar el tab */
  document.getElementById("myTabAdd").addEventListener("click", () => {
    table.rows().deselect();
    table.ajax.reload();
    jQuery.noConflict();
    $("#form-and-buttons_exists").collapse("hide");
  });

  document.getElementById("btnNuevoToner").addEventListener("click", e => {
    e.preventDefault();
    e.stopPropagation();
    sendData();
  });

  document.getElementById("existUpdate").addEventListener("click", () => {
    getDataUpdate(table);
  });
  document.getElementById("existDelete").addEventListener("click", () => {
    showModalDelete(table);
  });

  document.getElementById("existDelete").addEventListener("click", () => {
    showModalDelete(table);
  });
});

/**@description Enviar datos a la bd */
async function sendData() {
  let input = document.getElementById("inputCantidad");
  let marca = document.getElementById("inputMarca").value.trim();
  let modelo = document.getElementById("modelo_con").value.trim();
  let tipo = document.getElementById("selectTipo").value.trim();
  let bodega = document.getElementById("selectUbicacion").value.trim();
  let impresora = document.getElementById("modelo_imp").value.trim();
  let minimo = document.getElementById("rangoMinimo").value.trim();
  let maximo = document.getElementById("rangoMaximo").value.trim();

  let cantidad = parseInt(input.value.trim());
  let selectores = [
    "#inputCantidad",
    "#inputMarca",
    "#modelo_con",
    "#selectTipo",
    "#selectUbicacion",
    "#modelo_imp",
    "#rangoMinimo",
    "#rangoMaximo"
  ];
  validClass(selectores);

  if (
    isNaN(cantidad) === false &&
    marca != "" &&
    modelo != "" &&
    tipo != "" &&
    bodega != "" &&
    impresora != "" &&
    minimo != "" &&
    maximo != ""
  ) {
    modelo = modelo.toUpperCase().replace(/ /g, "-");

    validarCantidades("#rangoMinimo", "#rangoMaximo");

    if (parseInt(minimo) < parseInt(maximo)) {
      const data = new FormData();

      data.append("cantidad", parseInt(cantidad));
      data.append("marca", marca.toUpperCase());
      data.append("modelo", modelo);
      data.append("tipo", tipo);
      data.append("bodega", parseInt(bodega));
      data.append("rangoMinimo", parseInt(minimo));
      data.append("rangoMaximo", parseInt(maximo));
      data.append("impresora", parseInt(impresora));
      data.append("case", "addPrinterConsumables");

      fetchURL("../api/consumible/insert_consumible.php", "POST", data)
        .then(res => {
          
          if (res.status === "ok") {
            alertSuccess();
            document.getElementById("formNuevo").reset();
            clean_Validations(selectores);
            //setTimeout(() => document.location.reload(), 1000);
          } else {
            alertError();
          }
        })
        .catch(err => console.log(err));
    } else {
      customAlertError(
        "El rango mínimo no puede ser mayor o igual que el rango máximo"
      );
    }
  } else if (isNaN(cantidad)) {
    input.classList.add("is-invalid");
    customAlertError("Ingrese una cantidad valida");
  } else if (cantidad <= 0) {
    input.classList.add("is-invalid");
    customAlertError(
      "La cantidad seleccionada no puede ser menor o igual a cero"
    );
  } else {
    alertErrorFormEmpty();
  }
}

/**@description Listar marcas de impresoras en el input marca*/
async function printMarcaPrinter() {
  //case:printersBrand

  fetchURL("../api/impresora/impresora.php?case=printersBrand")
    .then(res => {
      if (res.marca[0].Marca_impresora != "") {
        for (const impresora of res.marca) {
          document.querySelector(
            "#inputMarca"
          ).innerHTML += `<option value=${impresora.Marca_impresora}>${impresora.Marca_impresora}</option>`;

          document.querySelector(
            "#updMarca"
          ).innerHTML += `<option>${impresora.Marca_impresora}</option>`;
        }
      } else {
        document.querySelector(
          "#inputMarca"
        ).innerHTML += `<option value="">No hay impresoras en el sistema</option>`;
      }
    })
    .catch(err => console.log(err));
}

/**@description Listar modelos de impresoras en el input impresora */
async function printModelPrinter(marca) {
  //case: showModelPrinter

  await fetch(
    `../api/impresora/impresora.php?case=showModelPrinter&&marca=${marca.value.toUpperCase()}`
  )
    .then(response => {
      return response.json();
    })
    .then(json => {
      for (const modelo of json) {
        document.querySelector(
          "#modelo_imp"
        ).innerHTML += `<option value=${modelo.Id_impresora}>${modelo.Modelo_impresora}</option>`;
      }
    })
    .catch(err => {
      console.log(err);
    });
}
//tabla de los consumibles existentes
function tableListConsumables() {
  var table = $("#tableListConsumable").DataTable({
    destroy: true,
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
      url: "../api/consumible/get_consumibleALL.php",
      data: { case: "allConsumablesList" }
    },
    columns: [
      {},
      { data: "Marca" },
      { data: "Modelo" },
      { data: "Tipo" },
      { data: "Impresora" }
    ]
  });

  return table;
}

//Ingresar una nueva cantidad de consumibles a la bodega seleccionada
function sendDataExists(table) {
  if (table.row(".selected").length > 0) {
    let input = document.getElementById("addMore");
    let bodega = document.getElementById("selectStorage").value;

    let cantidad = parseInt(input.value.trim());
    let selectores = ["#addMore", "#selectStorage"];
    validClass(selectores);

    if (isNaN(cantidad) === false && cantidad > 0 && bodega != "") {
      var data = table.row(".selected").data();

      let formaData = new FormData();

      formaData.append("Id_consumible", parseInt(data.Id_consumible));
      formaData.append("Id_bodega", bodega);
      formaData.append("cantidad", parseInt(cantidad));
      formaData.append("case", "addConsumablesExists");

      fetchURL("../api/consumible/insert_consumible.php", "POST", formaData)
        .then(res => {
          if (res.status === "ok") {
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
    } else if (isNaN(cantidad)) {
      input.classList.add("is-invalid");
      customAlertError("Ingrese una cantidad valida");
    } else if (cantidad <= 0) {
      input.classList.add("is-invalid");
      customAlertError(
        "La cantidad seleccionada no puede ser menor o igual a cero"
      );
    } else {
      customAlertError(
        "Complete todos los campos y asegúrese que la cantidad no sea cero"
      );
    }
  } else {
    customAlertError("Seleccione un elemento");
  }
}

/* Acción modificar-abrir modal */
/**@param {object} table*/
function getDataUpdate(table) {
  var data = table.row(".selected").data();

  if (table.row(".selected").length > 0) {
    //abrir modal
    jQuery.noConflict();
    jQuery("#modalUpdate");
    $("#modalUpdate").modal("show");

    $("#modalUpdate").on("hidden.bs.modal", function() {
      let selectores = [
        "#updMarca",
        "#updModelo",
        "#updTipo",
        "#cantMinima",
        "#cantMaxima",
        "#updImpresora"
      ];
      clean_Validations(selectores);
    });

    let marca = data.Marca;
    let modelo = data.Modelo;
    let tipo = data.Tipo;
    let impresora = data.Impresora;
    let maximo = data.Maximo;
    let minimo = data.Minimo;

    //Pasar datos al modal
    setModalUpdate(marca, modelo, tipo, impresora, maximo, minimo);
  } else {
    customAlertError("Seleccione un elemento");
  }
}

/* Pasar datos al modal de modificar */
/**@param {string} marca,  @param {string} modelo, @param {string} tipo, @param {string} impresora, @param {string} maximo, @param {string} minimo*/
async function setModalUpdate(marca, modelo, tipo, impresora, maximo, minimo) {
  document.getElementById("updMarca").value = marca;
  document.getElementById("updModelo").value = modelo;
  document.getElementById("updTipo").value = tipo;
  document.getElementById("cantMinima").value = minimo;
  document.getElementById("cantMaxima").value = maximo;
  await printModelPrinterIPD();
  document.getElementById("updImpresora").value = impresora;
}

/* validacion de datos del modal modificar */
function validFields() {
  let marca = document.getElementById("updMarca").value;
  let modelo = document.getElementById("updModelo").value;
  let tipo = document.getElementById("updTipo").value;
  let modelo_impresora = document.getElementById("updImpresora").value;
  let minima = document.getElementById("cantMinima").value;
  let maxima = document.getElementById("cantMaxima").value;

  validClass([
    "#updMarca",
    "#updModelo",
    "#updTipo",
    "#updImpresora",
    "#cantMinima",
    "#cantMaxima"
  ]);

  if (
    marca != "" &&
    modelo != "" &&
    tipo != "" &&
    modelo_impresora != "" &&
    isNaN(parseInt(minima)) === false &&
    isNaN(parseInt(maxima)) === false
  ) {
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
    let minima = document.getElementById("cantMinima").value.trim();
    let maxima = document.getElementById("cantMaxima").value.trim();

    minima = parseInt(minima);
    maxima = parseInt(maxima);

    validarCantidades("#cantMinima", "#cantMaxima");

    if (parseInt(minima) < parseInt(maxima)) {
      _modelo = _modelo.toUpperCase().replace(/ /g, "-");

      //Pasar datos al objeto formatData
      let data = new FormData();
      data.append("marca_new", _marca.toUpperCase());
      data.append("modelo_new", _modelo.toUpperCase());
      data.append("tipo_new", _tipo);
      data.append("Id_consumible", parseInt(datatable.Id_consumible));
      data.append("impresora_new", _impresora.toUpperCase());
      data.append("minimo", minima);
      data.append("maximo", maxima);

      fetchURL("../api/consumible/update_consumible.php", "POST", data)
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
      validarCantidades("#cantMinima", "#cantMaxima");
      customAlertError(
        "El rango mínimo no puede ser mayor o igual que el rango máximo"
      );
    }
  } else {
    alertErrorFormEmpty();
  }
}

/**@description agregar modelos de impresoras en la lista del modal actualizar */
async function printModelPrinterIPD() {
  //case: namePrinter

  //resetear select
  document.querySelector("#updImpresora").innerHTML =
    "<option value='' selected>Seleccione...</option>";
  let marca = document.querySelector("#updMarca");
  //Consulta y llenar select con resultados
  await fetch(
    `../api/impresora/impresora.php?case=namePrinter&&marca=${marca.value.toUpperCase()}`
  )
    .then(response => {
      return response.json();
    })
    .then(json => {
      json.forEach(impresora => {
        document.querySelector(
          "#updImpresora"
        ).innerHTML += `<option>${impresora.Impresora}</option>`;
      });
    })
    .catch(err => {
      console.log(err);
      alertError();
    });
}

/**Mostrar modal de confirmacion para la eliminacion */
function showModalDelete(table) {
  if (table.row(".selected").length > 0) {
    //abrir modal
    jQuery.noConflict();
    jQuery("#modalDeleteConsumable");
    $("#modalDeleteConsumable").modal("show");
  } else {
    customAlertError("Seleccione un elemento");
  }
}

/**Realizar eliminacion del elemento */
function confirmDeleteAll(table) {
  var datatable = table.row(".selected").data();

  let data = new FormData();
  data.append("Id_consumible", parseInt(datatable.Id_consumible));
  data.append("case", "deleteAll");

  fetchURL("../api/consumible/delete_consumible.php", "POST", data)
    .then(res => {
      if ((res.status = "ok")) {
        alertSuccess();
        table.ajax.reload();
        $("#modalDeleteConsumable").modal("hide");
      } else {
        alertError();
      }
    })
    .catch(err => {
      console.log(err);
      alertError();
    });
}

function showFormAndControl(table) {
  jQuery.noConflict();
  jQuery("#form-and-buttons_exists");
  if (table.row(".selected").length > 0) {
    $("#form-and-buttons_exists").collapse("show");
  } else {
    $("#form-and-buttons_exists").collapse("hide");
  }
}
