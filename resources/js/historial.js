document.addEventListener("DOMContentLoaded", function() {
  const table = list();
  document.querySelector("#filter").value = "";

  document.querySelector("#btnBuscarMes").addEventListener("click", e => {
    e.preventDefault();
    search(table);
  });
  document.querySelector("#filter").addEventListener("input", () => {
    showForm();
  });
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
        title: "Historial de retiros de consumibles de impresoras",
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
        console.log(json);

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
  let formMes = document.querySelector("#formMesHistorial");
  let formRange = document.querySelector("#formRange");
  let filter = document.querySelector("#filter");
  jQuery.noConflict();
  jQuery("#formRange");
  jQuery("#formMesHistorial");

  if (filter.value === "mes") {
    $("#formRange").collapse("hide");

    setTimeout(() => {
      $("#formMesHistorial").collapse("show");
    }, 260);

    /* if (formRange.classList.contains("d-none") === false) {
      formRange.classList.add("d-none");
    } */

    /*if (formMes.classList.contains("d-none")) {
      formMes.classList.remove("d-none");
    } */
  }

  if (filter.value === "rango") {
    $("#formMesHistorial").collapse("hide");
    setTimeout(() => {
      $("#formRange").collapse("show");
    }, 260);

    //$("#formRange").collapse("show");

    /*   if (formMes.classList.contains("d-none")) {
      formMes.classList.add("d-none");
    } */
    /* if (formRange.classList.contains("d-none")) {
      formRange.classList.remove("d-none");
    } */
  }

  if (filter.value === "") {
    $("#formMesHistorial").collapse("hide");
    $("#formRange").collapse("hide");
    /* if (!formRange.classList.contains("d-none")) {
      formRange.classList.add("d-none");
    }
    if (!formMes.classList.contains("d-none")) {
      formMes.classList.add("d-none");
    } */
  }
}
