document.addEventListener("DOMContentLoaded", function() {
  const table = list();
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
      method: "POST",
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
