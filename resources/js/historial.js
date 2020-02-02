document.addEventListener("DOMContentLoaded", function() {
  const table = list();
  document.querySelector("#filter").value = "";
  document.querySelector("#mes").value = "";

  document.querySelector("#btnBuscarMes").addEventListener("click", e => {
    e.preventDefault();
    search(table);
  });
  document.querySelector("#filter").addEventListener("input", () => {
    let filter = document.querySelector("#filter").value;

    if (filter === "") {
      table.rows().remove();
      table.ajax.reload();
      document.title = "Historial";
    }
    showForm();
  });
  document.querySelector("#btnBuscarRango").addEventListener("click", () => {
    DateRange(table);
  });

  namepdf();
});

function list() {
  const table = $("#tableHist").DataTable({
    destroy: true,
    //responsive: true,
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
        messageTop:
          "Emitido el " +
          new Date().getDate() +
          "/" +
          new Date().getMonth() +
          1 +
          "/" +
          new Date().getFullYear()
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
    ]
  });
  setInterval(function() {
    table.ajax.reload();
  }, 100000);

  return table;
}

function search(table) {
  let mes = document.querySelector("#mes").value;

  if (mes !== "") {
    let data = new FormData();
    data.append("mes", mes);
    data.append("case", 1);

    fetch("../api/retiro/get_retiro.php", {
      method: "POST",
      body: data
    })
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
  } else {
    table.rows().remove();
    table.ajax.reload();
    customAlertError("Seleccione un mes");
  }
}

function showForm() {
  //let formMes = document.querySelector("#formMesHistorial");
  //let formRange = document.querySelector("#formRange");
  let filter = document.querySelector("#filter");
  jQuery.noConflict();
  jQuery("#formRange");
  jQuery("#formMesHistorial");

  if (filter.value === "mes") {
    $("#formRange").collapse("hide");

    setTimeout(() => {
      $("#formMesHistorial").collapse("show");
    }, 260);
  }

  if (filter.value === "rango") {
    $("#formMesHistorial").collapse("hide");
    setTimeout(() => {
      $("#formRange").collapse("show");
    }, 260);
  }

  if (filter.value === "") {
    $("#formMesHistorial").collapse("hide");
    $("#formRange").collapse("hide");
  }
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
  document.querySelector("#mes").addEventListener("input", () => {
    var mes = document.querySelector("#mes").value;
    var nameMes;

    switch (mes) {
      case "01":
        nameMes = " del mes de Enero";
        break;
      case "02":
        nameMes = " del mes de Febrero";
        break;
      case "03":
        nameMes = " del mes de Marzo";
        break;
      case "04":
        nameMes = " del mes de Abril";
        break;
      case "05":
        nameMes = " del mes de Mayo";
        break;
      case "06":
        nameMes = " del mes de Junio";
        break;
      case "07":
        nameMes = " del mes de Julio";
        break;
      case "08":
        nameMes = " del mes de Agosto";
        break;
      case "09":
        nameMes = " del mes de Septiembre";
        break;
      case "10":
        nameMes = " del mes de Obtubre";
        break;
      case "11":
        nameMes = " del mes de Noviembre";
        break;
      case "12":
        nameMes = " del mes de Diciembre";
        break;

      default:
        nameMes = "";
        break;
    }

    let name = "Historial de retiros de consumibles de impresoras" + nameMes;
    document.title = name;
  });

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
