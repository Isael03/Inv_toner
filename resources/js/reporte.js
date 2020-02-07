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

          document.getElementById(
            "linkToPdf"
          ).innerHTML = `<a class="btn btn-secondary mb-2 text-white" href="reporte_pdf.php?inicio=${inicio.value}&&termino=${termino.value}" target="_blank">PDF</a>`;

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

          alertWarning("No se encontraron datos");
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
            ).innerHTML += `<tr><td id=${dep.iddireccion}>${dep.direccion}</td><td><a href='./reporte_dep_pdf.php?iddir=${dep.iddireccion}&&nombre_dir=${dep.direccion}&&inicio_dep=${inicio.value}&&termino_dep=${termino.value}' target="_blank">Generar pdf</a></td></tr>`;
          }

          inicio.value = "";
          termino.value = "";
        } else {
          document.getElementById("tbody-dep").innerHTML = "";
          alertWarning("No se encontraron datos");
        }
      })
      .catch(err => {
        console.log(err);
        alertError();
      });
  } else {
    customAlertError("Especifique un rango de fechas");
  }
}
