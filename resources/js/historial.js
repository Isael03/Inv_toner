document.addEventListener("DOMContentLoaded", function() {
  const table = list();

  document.querySelector("#btnBuscarRango").addEventListener("click", () => {
    DateRange(table);
  });

  namepdf();

  document.querySelector("#btnCancel").addEventListener("click", () => {
    showModalConfirmWithdraw();
  });
  document
    .querySelector("#btn-confirm-cancelWithdraw")
    .addEventListener("click", () => {
      cancelWithdraw(table);
    });
  document.querySelector("#cancel-operation").addEventListener("click", () => {
    $("#dataHistorial").removeClass("d-none");
  });
});

function list() {
  const table = $("#tableHist").DataTable({
    destroy: true,
    select: true,
    order: [[0, "desc"]],
    /*  pageLength: 5,
    lengthMenu: [
      [5, 10, 20, -1],
      [5, 10, 20, 100]
    ], */
    dom: "Bfrtip",
    buttons: [
      {
        extend: "pdf",
        text: "<span class='fas fa-file-pdf'></span>",
        titleAttr: "PDF",
        className: "btn btn-success",
        orientation: "landscape",
        //title: `Historial de consumibles`,
        /*   messageTop:
          "Emitido el " +
          new Date().getDate() +
          "/" +
          new Date().getMonth() +
          1 +
          "/" +
          new Date().getFullYear(), */
        customize: function(doc) {
          doc.content[1].margin = [50, 0, 50, 0];
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
      url: `../api/retiro/get_retiro.php`
    },
    columns: [
      { data: "Fecha" },
      { data: "Retira" },
      { data: "Recibe" },
      { data: "Departamento" },
      { data: "Marca" },
      { data: "Modelo" },
      { data: "Tipo" },
      { data: "Cantidad" },
      { data: "Impresora" },
      { data: "Bodega" }
    ]
  });
  /*  setInterval(function() {
    table.ajax.reload();
  }, 100000); */

  showModal("#tableHist tbody", table);

  return table;
}

/**Mostral modal con informacion de la fila */
var showModal = function(tbody, table) {
  $(tbody).on("click", "tr", function() {
    //var data = table.row($(this).parents("tr")).data();
    var data = table.row(this).data();
    jQuery.noConflict();
    jQuery("#dataHistorial");
    $("#dataHistorial").modal("show");

    document.querySelector(
      "#mMistorialHeader"
    ).innerHTML = `Recibe: ${data.Recibe}`;

    let size_font = ".9em";

    let dataModal = [
      "Fecha: ",
      "Departamento que recibe: ",
      "Quién entrega: ",
      "Marca: ",
      "Modelo: ",
      "Tipo: ",
      "Cantidad: ",
      "Impresora: ",
      "Bodega: "
    ];

    let infoModal = [
      data.Fecha,
      data.Departamento,
      data.Retira,
      data.Marca,
      data.Modelo,
      data.Tipo,
      data.Cantidad,
      data.Impresora,
      data.Bodega
    ];

    document.querySelector("#id_historial").innerHTML = data.Id_retiro;

    document.querySelector("#body-history").innerHTML = "";

    for (let index = 0; index < dataModal.length; index++) {
      document.querySelector(
        "#body-history"
      ).innerHTML += `<div class='row'><div class='col-6'> <p class='m-0' style="font-size:${size_font}">${dataModal[index]}</p></div><div class='col-6 font-weight-bold'  style="font-size:${size_font}">${infoModal[index]}</div></div> `;
    }
  });
};

/** Buscar datos por rango de fechas*/
function DateRange(table) {
  let dateFrom = document.querySelector("#dateFrom").value;
  let dateTo = document.querySelector("#dateTo").value;

  let selectores = ["#dateFrom", "#dateTo"];
  validClass(selectores);
  if (dateFrom !== "" && dateTo !== "") {
    let data = new FormData();

    data.append("inicio", dateFrom + " 00:00:00");
    data.append("termino", dateTo + " 23:59:59");
    data.append("case", 2);

    let config = {
      method: "POST",
      headers: {
        Accept: "application/json"
      },
      body: data
    };

    fetch("../api/retiro/get_retiro.php", config)
      .then(function(response) {
        if (response.ok) {
          return response.json();
        } else {
          customAlertError("Error en la búsqueda");
          throw "Error en la llamada fetch";
        }
      })
      .then(json => {
        if (json.data[0] != undefined) {
          table.rows().remove();
          table.rows.add(json.data).draw();
          alertSuccess();
          clean_Validations(selectores);
        } else {
          table.rows().remove();
          table.rows.add(json.data).draw();
          alertWarning("Nada encontrado");
        }
      })
      .catch(err => {
        console.log(err);
        customAlertError("Error en la búsqueda");
      });
  } else {
  }
}

function namepdf() {
  document.querySelector("#btnBuscarRango").addEventListener("click", () => {
    let dateFrom = document.getElementById("dateFrom").value;
    let dateTo = document.getElementById("dateTo").value;

    dateFrom = dateFrom.split("-");
    dateTo = dateTo.split("-");
    let range = ` desde el ${dateFrom[2]}-${dateFrom[1]}-${dateFrom[0]} a ${dateTo[2]}-${dateTo[1]}-${dateTo[0]}`;

    let name = "Historial de retiros de consumibles de impresoras" + range;
    document.title = name;
  });
}
function cancelWithdraw(table) {
  let data = new FormData();

  let id_historial = document.querySelector("#id_historial").innerHTML;

  data.append("id_retiro", id_historial);

  fetchURL("../api/retiro/cancelar_retiro.php", "POST", data)
    .then(function(res) {
      if (res.status === "ok") {
        alertSuccess();
        jQuery.noConflict();
        jQuery("#dataHistorial");
        table.ajax.reload();
        $("#dataHistorial").removeClass("d-none");
        $("#dataHistorial").modal("hide");
        $("#confirmacion-retiro").modal("hide");
      } else if (res.status === "not_exists") {
        customAlertError(
          "La bodega o la impresora relacionada ya no existe en el sistema, por lo que no se puede volver a ingresar"
        );
      } else {
        alertError();
      }
    })
    .catch(function(err) {
      console.error(err);
    });
}

function showModalConfirmWithdraw() {
  jQuery.noConflict();
  // jQuery("#confirmacion-retiro");
  $("#confirmacion-retiro").modal("show");
  $("#dataHistorial").addClass("d-none");
}
