"use strict";
document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("inicio-gral").value = "";
  document.getElementById("termino-gral").value = "";

  document.getElementById("btn-searchGeneral").addEventListener("click", e => {
    e.preventDefault();
    e.stopPropagation();
    reportGeneral();
  });

  document.getElementById("btn-searchDep").addEventListener("click", e => {
    e.preventDefault();
    e.stopPropagation();
    listDepart();
  });
});

function reportGeneral() {
  let inicio = document.getElementById("inicio-gral");
  let termino = document.getElementById("termino-gral");

  if (inicio.value != "" && termino.value != "") {
    var data = new FormData();
    data.append("inicio", inicio.value.trim());
    data.append("termino", termino.value.trim());
    data.append("case", "general");

    let config = {
      method: "POST",
      headers: {
        Accept: "application/json"
      },
      body: data
    };

    fetch("../api/retiro/reporte.php", config)
      .then(response => {
        if (response.ok) {
          return response.json();
        }
      })
      .then(json => {
        if (json.status != "bad") {
          let fecha_inicio = inicio.value.split("-");
          let fecha_termino = termino.value.split("-");

          document.getElementById(
            "title_report"
          ).innerHTML = `Entregas ${fecha_inicio[2]}-${fecha_inicio[1]}-${fecha_inicio[0]} a ${fecha_termino[2]}-${fecha_termino[1]}-${fecha_termino[0]}`;

          document.getElementById("tbody-depart").innerHTML = "";
          document.getElementById("tbody-model").innerHTML = "";

          for (const dep of json.depart) {
            document.getElementById(
              "tbody-depart"
            ).innerHTML += `<tr><td>${dep.depart}</td><td>${dep.Cantidad}</td></tr>`;
          }
          for (const element of json.model) {
            document.getElementById(
              "tbody-model"
            ).innerHTML += `<tr><td>${element.Marca}</td><td>${element.Modelo}</td><td>${element.Tipo}</td><td>${element.Cantidad}</td></tr>`;

            inicio.value = "";
            termino.value = "";
          }
        } else {
          cleanDataGeneral();

          alertWarning("Nada encontrado");
        }
      })
      .catch(err => {
        cleanDataGeneral();

        console.log(err);
        alertError();
      });
  } else {
    customAlertError("Especifique un rango de fechas");
  }
}

function cleanDataGeneral() {
  document.getElementById("title_report").innerHTML = "Entregas";
  document.getElementById("tbody-depart").innerHTML = "";
  document.getElementById("tbody-model").innerHTML = "";
}

function listDepart() {
  let inicio = document.getElementById("inicio_dep");
  let termino = document.getElementById("termino_dep");

  if (inicio.value != "" && termino.value != "") {
    var data = new FormData();
    data.append("inicio_dep", inicio.value.trim());
    data.append("termino_dep", termino.value.trim());
    data.append("case", "dep");

    let config = {
      method: "POST",
      headers: {
        Accept: "application/json"
      },
      body: data
    };

    fetch("../api/retiro/reporte.php", config)
      .then(response => {
        if (response.ok) {
          return response.json();
        }
      })
      .then(json => {
        if (json.status != "bad") {
          /* let fecha_inicio = inicio.value.split("-");
          let fecha_termino = termino.value.split("-"); */

          document.getElementById("tbody-dep").innerHTML = "";

          for (const dep of json) {
            document.getElementById(
              "tbody-dep"
            ).innerHTML += `<tr><td id=${dep.Id_departamento}>${dep.Departamento}</td><td><a href='#'>Generar pdf</a></td></tr>`;
          }

          inicio.value = "";
          termino.value = "";
        } else {
          document.getElementById("tbody-dep").innerHTML = "";
          alertWarning("Nada encontrado");
        }
      })
      .catch(err => {
        console.log(err);
        /* document.getElementById(
          "tbody-dep"
        ).innerHTML = `<td colspan='2'>No hay datos</td>`; */
        alertError();
      });
  } else {
    customAlertError("Especifique un rango de fechas");
  }
}
