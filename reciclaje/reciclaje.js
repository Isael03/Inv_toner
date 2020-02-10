//getDataUpdate("#tableToners tbody", table);
//getDataDelete("#tableToners tbody", table);
var getDataUpdate = function(tbody, table) {
  $(tbody).on("click", "#btnUpdate", function() {
    //$(this).addClass("selected");

    var data = table.row($(this).parents("tr")).data();
    //var data = table.row(this).data();
    //var data = table.row(".selected").data();
    //console.log(table.row(".selected").data());

    //abrir modal
    jQuery.noConflict();
    jQuery("#modalUpdate");
    $("#modalUpdate").modal("show");

    let marca = data.Marca;
    let modelo = data.Modelo;
    let tipo = data.Tipo;
    let codigo = data.Codigo;
    let impresora = data.Impresora;
    let lugar = parseInt(data.Id_bodega);

    //Pasar datos al modal
    setModalUpdate(marca, modelo, tipo, impresora);
    //boton confirmar del modal
    confirmUpdate(marca, modelo, tipo, codigo, lugar, impresora);
  });
};

//Acción eliminar
var getDataDelete = function(tbody, table) {
  $(tbody).on("click", "#btnDelete", function() {
    var data = table.row(this).data();

    jQuery.noConflict();
    jQuery("#modalUpdate");
    $("#modalDelete").modal("show");

    //Accion boton confirmar
    document
      .getElementById("btnModalDelete")
      .addEventListener("click", function() {
        console.log(data.Marca);
      });
  });
};

function list() {
  /**Configuración Datatable*/
  /** Variable Datatable_ES se encuentra en /scripts/main.js */
  var table = $("#tableToners").DataTable({
    destroy: true,
    responsive: true,
    select: true,
    dom: "Bfrtip",
    // buttons: ["copy", "excel", "pdf", "print"],
    // buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
    buttons: [
      {
        extend: "pdf",
        text: "PDF",
        exportOptions: {
          modifier: {
            page: "current"
          }
        }
      }
    ],
    buttons: [
      {
        extend: "pdfHtml5",
        orientation: "landscape",
        pageSize: "LEGAL"
      }
    ],
    /*dom: "Bfrtip",
     buttons: [
      {
        extend: "pdfHtml5",
        filename: "Inventario",
        text: "PDF",
        title: "Consumibles de impresoras existentes",
        className: 'copyButton',
        exportOptions: {
          modifier: {
            page: "current"
          }
        }
      },
      {
        extend: "print",
        text: "Imprimir",
        title: "Consumibles de impresoras existentes",
        header: "inventario"
      }
    ] ,*/
    //rowReorder: true,
    //scrollY: 250,
    //scrollX: true,
    //scrollable: "y",
    //bSort: false,
    language: Datatable_ES,
    ajax: {
      method: "GET",
      url: `./api/printer_supplies/get_printer_supplies.php`
    },
    columns: [
      { data: "Fecha" },
      { data: "Marca" },
      { data: "Modelo" },
      { data: "Tipo" },
      { data: "Codigo" },
      { data: "Lugar" },
      {
        data: "Impresora"
      } /* ,
        {
          defaultContent:
            "<div class='btn-group btn-group-sm' role='group' aria-label='Basic example'>" +
            "<button class='btn btn-info' id='btnWithdraw'><span class='fas fa-box-open'></span></button>" +
            "<button  id='btnUpdate' class='btn btn-warning mx-2'><span class='fas fa-wrench text-white'></span>" +
            "</button > <button class='btn btn-danger' id='btnDelete'>" +
            "<span class='fas fa-trash'></span></button></div>"
        } */
    ] /* ,
      buttons: [
        {
          text: '<span class="fa fa-files-o"></span>',
          titleAttr: "Añadir",
          action: function() {
            //table.rows().select();
  
            jQuery.noConflict();
            jQuery("#modalUpdate");
            $("#modalUpdate").modal("show");
            let data = table.row(".selected").data();
  
            let marca = data.Marca;
            let modelo = data.Modelo;
            let tipo = data.Tipo;
            let codigo = data.Codigo;
            let impresora = data.Impresora;
            let lugar = parseInt(data.Id_bodega);
  
            //Pasar datos al modal
            setModalUpdate(marca, modelo, tipo, codigo, lugar, impresora);
          }
        },
        {
          text: "Select none",
          titleAttr: "Retirar",
          action: function() {
            table.rows().deselect();
          }
        },
        {
          text: "Select none",
          titleAttr: "Modificar",
          action: function() {
            table.rows().deselect();
          }
        },
        {
          text: "Select none",
          titleAttr: "Eliminar",
          action: function() {
            //table.rows().deselect();
          }
        }
      ] */
  });
}

/* contar columnas seleccionadas */
console.log(table.rows({ selected: true }).indexes());

setInterval(function() {
  table.ajax.reload();
}, 30000);

setInterval(function() {
  table.ajax.reload(null, false); // user paging is not reset on reload
}, 30000);

setInterval(function() {
  yourTable.draw();
}, 3000);
var table = $("#example").DataTable();
table.ajax.reload();
$("#example")
  .DataTable()
  .ajax.reload();

var getDataWithdraw = function(tbody, table) {
  $(tbody).on("click", "#btnWithdraw", function() {
    var data = table.row(this).data();
    console.log(data);
    jQuery.noConflict();
    jQuery("#modalUpdate");
    $("#modalWithdraw").modal("show");
  });
};

/* --------------------Botones de tabla de all--------------------------- */

/* boton modificar */
/* document.querySelector("#btnUpdate").addEventListener("click", () => {
    getDataUpdate(tableINF);
  }); */

/*  boton eliminar */
/*   document.getElementById("btnDelete").addEventListener("click", () => {
    getDataDelete(tableAll);
  }); */

/* boton retirar */
/* document.getElementById("btnWithdraw").addEventListener("click", () => {
    getDataWithdraw(tableINF);
  }); */

switch (data.Id_bodega) {
  case "1":
    bodega.value = "Bodega 1";
    break;
  case "2":
    bodega.value = "Bodega 2";
    break;
  case "3":
    bodega.value = "Informatica";
    break;

  default:
    bodega.value = "No hay datos";
    break;
}
table
  .row(".selected")
  .remove()
  .draw(false);

function validClass(selector) {
  let input = document.querySelector(selector);

  if (input.value === "") {
    if (input.classList.contains("is-valid")) {
      input.classList.remove("is-valid");
      input.classList.add("is-invalid");
    }
    input.classList.add("is-invalid");

    // return false;
  } else {
    if (input.classList.contains("is-invalid")) {
      input.classList.remove("is-invalid");
      input.classList.add("is-valid");
    }
    input.classList.add("is-valid");
    //return true;
  }
}

/* Insertar impresora */
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

/* Actualizar impresora */

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

/* Borrar impresora */

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
      jQuery.noConflict();
      $("#modalDeletePrinter").modal("hide");
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

/* Borrar consumible */

/*  fetch("./api/consumible/delete_consumible.php", {
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
      }); */

/* Actualizar consumible */

/* await fetch("./api/consumible/update_consumible.php", {
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
      }); */

/* Retirar consumible */
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

/* Ingresar nuevo consumible */
await fetch("../api/consumible/insert_consumible.php", {
  method: "POST",
  headers: { Accept: "application/json" },
  body: data
})
  .then(response => {
    if (response.ok) {
      return response.json();
    } else {
      alertError();
      //throw "Error en la llamada fetch";
    }
  })
  .then(json => {
    console.log(json);

    if (json.status === "ok") {
      alertSuccess();
      document.getElementById("formNuevo").reset();
      setTimeout(() => document.location.reload(), 1000);
    } else {
      alertError();
    }
  })
  .catch(err => {
    alertError();
    console.log(err);
  });

/* Mostrar marcas de impresoras en el insert */

await fetch("../api/impresora/impresora.php?case=printersBrand")
  .then(response => {
    if (response.ok) {
      return response.json();
    } else {
      alertError();
      //throw "Error en la llamada fetch";
    }
  })
  .then(json => {
    if (json.marca[0].Marca_impresora != "") {
      for (const impresora of json.marca) {
        document.querySelector(
          "#inputMarca"
        ).innerHTML += `<option value=${impresora.Marca_impresora}>${impresora.Marca_impresora}</option>`;
      }

      if (json.modelo_con !== undefined) {
        for (const modelo of json.modelo_con) {
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
  .catch(err => {
    alertError();
    console.log(err);
  });

/**For Card  */

for (let i = 0; i < res.data.length; i++) {
  if (i >= 3) break;
  if (i === 10) {
    i = 0;
  }

  document.getElementById(
    "rowCards"
  ).innerHTML += `  <div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-${color[i]} o-hidden h-100">
      <div class="card-body">
        <div class="card-body-icon">
          <i class="fas fa-boxes"></i>
        </div>
        <div class="text-center font-weight-bold idstg">${res.data[i].Cantidad}</div>
      </div>
      <a class="card-footer text-white clearfix small z-1" href="#">
        <span class="float-left font-weight-bold">${res.data[i].Lugar}</span>
      </a>
    </div>
  </div>`;
  //changeTabName(res.data[i].Lugar, i);
}
