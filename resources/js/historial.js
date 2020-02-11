document.addEventListener("DOMContentLoaded", function() {
  const table = list();

  document.querySelector("#btnBuscarRango").addEventListener("click", () => {
    DateRange(table);
  });

  namepdf();
});

function list() {
  const table = $("#tableHist").DataTable({
    destroy: true,
    // responsive: true,
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
          doc.content[1].margin = [0, 0, 0, 0];
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
      { data: "Impresora" }
    ],
    responsive: true
  });
  setInterval(function() {
    table.ajax.reload();
  }, 100000);

  return table;
}

function DateRange(table) {
  let dateFrom = document.querySelector("#dateFrom").value;
  let dateTo = document.querySelector("#dateTo").value;

  let data = new FormData();

  data.append("inicio", dateFrom);
  data.append("termino", dateTo);
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
      if (json.data[0].Fecha != "") {
        table.rows().remove();
        table.rows.add(json.data).draw();
        alertSuccess();
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
