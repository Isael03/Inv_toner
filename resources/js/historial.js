document.addEventListener("DOMContentLoaded", function() {
  $("#tableHistorial").DataTable({
    destroy: true,
    responsive: true,
    select: true,
    order: [[0, "desc"]],
    pageLength: 5,
    lengthMenu: [
      [5, 10, 20, -1],
      [5, 10, 20, 100]
    ],
    order: [[0, "desc"]],
    language: Datatable_ES,
    ajax: {
      method: "POST",
      //dataSrc: "data",
      url: `../api/retiro/get_retiro.php`
    },
    columns: [
      { data: "Fecha" },
      { data: "Retira" },
      { data: "Entrega" },
      { data: "Departamento" },
      { data: "Marca" },
      { data: "Modelo" },
      { data: "Tipo" },
      { data: "Codigo" },
      { data: "Bodega" }
    ]
  });
  setInterval(function() {
    table.ajax.reload();
  }, 100000);
});
