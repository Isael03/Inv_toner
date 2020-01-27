"use strict";

document.addEventListener("DOMContentLoaded", function() {
  const table = list();
  document.querySelector("#btnBuscarRetiro").addEventListener("click", e => {
    e.preventDefault();
    e.stopPropagation();
    search(table);
  });

  document.querySelector("#btnWithdraw").addEventListener("click", () => {
    getDataWithdraw(table);
  });
  document.querySelector("#btnModalWithdraw").addEventListener("click", () => {
    confirmWithdraw(table);
  });
});

function search(table) {
  let bodega = document.querySelector("#bodega");
  let marca = document.querySelector("#marca");
  let modelo = document.querySelector("#modelo");

  if (validField(bodega, marca, modelo)) {
    let data = new FormData(document.getElementById("formRetiroCons"));

    fetch("../api/consumible/get_consumible.php", {
      method: "POST",
      body: data
    })
      .then(function(response) {
        if (response.ok) {
          return response.json();
        } else {
          throw "Error en la llamada fetch";
        }
      })
      .then(json => {
        if (json.data[0].Lugar === "") {
          table.rows().remove();
          table.rows.add(json.data).draw();
          customAlertError("Nada encontrado");
        } else {
          table.rows().remove();
          table.rows.add(json.data).draw();
          customAlertSuccess("Datos encontrados");
        }
      })
      .catch(err => {
        console.log(err);
      });
  } else {
    customAlertError("Complete al menos un campo");
  }
}

function validField(bodega, marca, tipo) {
  let valid = false;
  if (bodega.value !== "" || marca.value !== "" || tipo.value !== "") {
    valid = true;
  }
  return valid;
}

function list() {
  const table = $("#tableRetiro").DataTable({
    destroy: true,
    responsive: true,
    language: Datatable_ES,
    select: true,
    columns: [
      { data: "Marca" },
      { data: "Modelo" },
      { data: "Tipo" },
      { data: "Codigo_barra" },
      { data: "Lugar" }
    ]
  });
  return table;
}

function getDataWithdraw(table) {
  if (table.row(".selected").length > 0) {
    jQuery.noConflict();
    jQuery("#modalWithdraw");
    $("#modalWithdraw").modal("show");

    setModalWithdraw(table);
  } else {
    customAlertError("Seleccione un elemento");
  }
}

function setModalWithdraw(table) {
  var data = table.row(".selected").data();

  document.querySelector("#submitter").value = "Falta";
  document.querySelector("#mMarca").value = data.Marca;
  document.querySelector("#mModelo").value = data.Modelo;
  document.querySelector("#mCodigo").value = data.Codigo_barra;
  document.querySelector("#mTipo").value = data.Tipo;

  let bodega = document.querySelector("#mBodega");

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
}

async function confirmWithdraw(table) {
  var data = table.row(".selected").data();
  let receivedBy = document.querySelector("#receivedBy");

  if (receivedBy.value.trim() === "") {
    alertErrorFormEmpty();
  } else {
    let usuarioRetira = document.querySelector("#submitter").value.trim();
    let bodega = document.querySelector("#mBodega").value.trim();

    console.log(data);

    let form = new FormData();
    form.append("usuarioRetira", usuarioRetira);
    form.append("usuarioRecibe", receivedBy.value.trim());
    form.append("marca", data.Marca);
    form.append("modelo", data.Modelo);
    form.append("codigo", data.Codigo_barra);
    form.append("tipo", data.Tipo);
    form.append("bodega", bodega);
    form.append("id", parseInt(data.Id_consumible));

    let config = {
      method: "POST",
      headers: {
        Accept: "application/json"
      },
      body: form
    };

    await fetch("../api/retiro/insert_retiro.php", config)
      .then(response => {
        return response.json();
      })
      .then(json => {
        console.log(json);

        if (json.status === "bad") {
          alertError();
        } else {
          jQuery.noConflict();
          jQuery("#modalWithdraw");
          $("#modalWithdraw").modal("hide");

          alertSuccess();
          table
            .row(".selected")
            .remove()
            .draw(false);
          //table.ajax.reload();
        }
      })
      .catch(err => console.log(err));
  }
}
