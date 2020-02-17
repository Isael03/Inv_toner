document.addEventListener("DOMContentLoaded", () => {
  const table = listStorage();
  document.querySelector("#nuevaBodega").value = "";

  /**Evento de para la creacion de la bodega  */
  document.querySelector("#btnNuevabodega").addEventListener("click", e => {
    e.preventDefault();
    e.stopPropagation();
    addStorage(table);
  });

  /**Evento del boton actualizar del modal actualizar bodega */
  document
    .querySelector("#btnModalUpdateStorage")
    .addEventListener("click", e => {
      e.preventDefault();
      e.stopPropagation();
      updateStorage(table);
    });

  /**Evento del boton delete del modal eliminar bodega */
  document
    .querySelector("#btnModalDeleteStorage")
    .addEventListener("click", e => {
      e.preventDefault();
      e.stopPropagation();
      deleteStorage(table);
    });
});
/**Configuración Datatable*/
function listStorage() {
  var table = $("#tableStorage").DataTable({
    destroy: true,
    //responsive: true,
    order: [[0, "desc"]],
    select: true,
    paging: false,
    info: false,
    searching: false,
    dom: "Bfrtip",
    buttons: [
      {
        text: " <span class='fas fa-wrench text-white'></span>",
        titleAttr: "Actualizar",
        className: "btn btn-warning",
        action: function() {
          if (table.row(".selected").length > 0) {
            jQuery.noConflict();
            $("#modalUpdateStorage").modal("show");
          } else {
            customAlertError("Seleccione un elemento");
          }
        }
      },
      {
        text: " <span class='fas fa-trash'></span>",
        titleAttr: "Eliminar",
        className: "btn btn-danger",
        action: function() {
          if (table.row(".selected").length > 0) {
            jQuery.noConflict();
            $("#modalDeleteStorage").modal("show");
          } else {
            customAlertError("Seleccione un elemento");
          }
        }
      }
    ],
    language: Datatable_ES,
    ajax: {
      method: "GET",
      url: "../api/bodega/bodegaGet.php",
      data: { case: "listStorage" }
    },

    columns: [{ data: "Lugar" }, { data: "Cantidad" }]
  });

  setInterval(function() {
    table.ajax.reload();
  }, 100000);

  return table;
}

/**Añadir nueva bodega */
function addStorage(table) {
  let nameBodega = document.querySelector("#nuevaBodega");

  let selectores = ["#nuevaBodega"];
  validClass(selectores);

  if (nameBodega.value !== "") {
    let data = new FormData();
    data.append("nombreBodega", nameBodega.value.trim());
    data.append("case", "addStorage");

    fetchURL("../api/bodega/bodegaPost.php", "POST", data)
      .then(res => {
        console.log(res);

        if (res.status) {
          table.ajax.reload();

          nameBodega.value = "";
          alertSuccess();
          clean_Validations(selectores);
        } else {
          alertError();
        }
      })
      .catch(err => {
        console.log(err);
      });
  } else {
    alertErrorFormEmpty();
  }
}
/**Actualizar nombre de la bodega */
function updateStorage(table) {
  let newName = document.querySelector("#newName");
  if (newName.value !== "") {
    var datatable = table.row(".selected").data();

    let data = new FormData();
    data.append("nuevoNombre", newName.value.trim());
    data.append("id", datatable.Id_bodega);
    data.append("case", "updateStorage");

    fetchURL("../api/bodega/bodegaPost.php", "POST", data)
      .then(res => {
        if (res.status) {
          table.ajax.reload();
          newName.value = "";
          alertSuccess();
          jQuery.noConflict();
          $("#modalUpdateStorage").modal("hide");
        } else {
          alertError();
        }
      })
      .catch(err => {
        console.log(err);
      });
  } else {
    alertErrorFormEmpty();
  }
}

/**Eliminar bodega */
function deleteStorage(table) {
  var datatable = table.row(".selected").data();

  let data = new FormData();
  data.append("id", parseInt(datatable.Id_bodega));
  data.append("case", "deleteStorage");

  fetchURL("../api/bodega/bodegaPost.php", "POST", data)
    .then(res => {
      if (res.status) {
        table.ajax.reload();
        alertSuccess();
        jQuery.noConflict();
        $("#modalDeleteStorage").modal("hide");
      } else {
        alertError();
      }
    })
    .catch(err => {
      console.log(err);
    });
}
