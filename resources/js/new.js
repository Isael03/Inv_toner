document.addEventListener("DOMContentLoaded", function() {
  const table = tableListConsumables();

  var forms = document.getElementsByClassName("needs-validation");
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

  /**Animacion de validacion de formulario de nuevo consumible */
  var validation = Array.prototype.filter.call(forms, function(form) {
    form.addEventListener(
      "submit",
      function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
          alertError();
        }
        if (form.checkValidity() === true) {
          event.preventDefault();
          event.stopPropagation();

          //Enviar datos
          sendData();
        }
        form.classList.add("was-validated");
      },
      false
    );
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
  });
});

/**@description Enviar datos a la bd */
async function sendData() {
  let cantidad = document.getElementById("inputCantidad").value.trim();
  let marca = document.getElementById("inputMarca").value.trim();
  let modelo = document.getElementById("modelo_con").value.trim();
  let tipo = document.getElementById("selectTipo").value.trim();
  let bodega = document.getElementById("selectUbicacion").value.trim();
  let impresora = document.getElementById("modelo_imp").value.trim();
  let minimo = document.getElementById("rangoMinimo").value.trim();
  let maximo = document.getElementById("rangoMaximo").value.trim();

  modelo = modelo.toUpperCase().replace(/ /g, "-");

  const data = new FormData();

  data.append("cantidad", parseInt(cantidad));
  data.append("marca", marca.toUpperCase());
  data.append("modelo", modelo);
  data.append("tipo", tipo);
  data.append("bodega", parseInt(bodega));
  data.append("rangoMinimo", parseInt(minimo));
  data.append("rangoMaximo", parseInt(maximo));
  // data.append("impresora", impresora.toUpperCase());
  data.append("impresora", parseInt(impresora));
  data.append("case", "addPrinterConsumables");

  fetchURL("../api/consumible/insert_consumible.php", "POST", data)
    .then(res => {
      if (res.status === "ok") {
        alertSuccess();
        document.getElementById("formNuevo").reset();
        setTimeout(() => document.location.reload(), 1000);
      } else {
        alertError();
      }
    })
    .catch(err => console.log(err));
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

        if (res.modelo_con !== undefined) {
          for (const modelo of res.modelo_con) {
            document.querySelector(
              "#inputModelo"
            ).innerHTML += `<option value=${modelo.Modelo}>`;
          }
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
  //let marca = document.querySelector("#inputMarca");

  //resetear select
  /*  document.querySelector("#updImpresora").innerHTML =
    "<option value='' selected>Seleccione...</option>"; */
  //let updmarca = document.querySelector("#updMarca");

  await fetch(
    `../api/impresora/impresora.php?case=showModelPrinter&&marca=${marca.value.toUpperCase()}`
  )
    .then(response => {
      return response.json();
    })
    .then(json => {
      console.log(json);

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

function tableListConsumables() {
  var table = $("#tableListConsumable").DataTable({
    destroy: true,
    select: true,
    dom: "Bfrtip",
    buttons: [
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
          showModalDelete(table);
        }
      }
    ],
    language: Datatable_ES,
    ajax: {
      method: "GET",
      url: "../api/consumible/get_consumibleALL.php",
      data: { case: "allConsumablesList" }
    },
    columns: [
      { data: "Marca" },
      { data: "Modelo" },
      { data: "Tipo" },
      { data: "Impresora" }
    ]
  });

  setInterval(function() {
    table.ajax.reload();
  }, 100000);

  return table;
}

function sendDataExists(table) {
  if (table.row(".selected").length > 0) {
    let cantidad = document.getElementById("addMore").value;
    let bodega = document.getElementById("selectStorage").value;

    if (cantidad != 0 && bodega != "") {
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
          } else {
            alertError();
          }
        })
        .catch(err => {
          alertError();
          console.log(err);
        });
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

    _modelo = _modelo.toUpperCase().replace(/ /g, "-");

    //Pasar datos al objeto formatData
    let data = new FormData();
    data.append("marca_new", _marca.toUpperCase());
    data.append("modelo_new", _modelo.toUpperCase());
    data.append("tipo_new", _tipo);
    data.append("Id_consumible", parseInt(datatable.Id_consumible));
    data.append("impresora_new", _impresora.toUpperCase());
    data.append("minimo", parseInt(minima));
    data.append("maximo", parseInt(maxima));

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
    alertErrorFormEmpty();
  }
}

/**@description agregar marcas de impresoras en la lista del modal actualizar */
/* async function printMarcaPrinter() {
  //case:printersBrand
  await fetch("../api/impresora/impresora.php?case=printersBrand")
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
} */

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
    //jQuery("#modalDeleteConsumable");
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
