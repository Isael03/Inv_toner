document.addEventListener("DOMContentLoaded", () => {
  /*  document.getElementById("inicio-gral").value = "";
  document.getElementById("termino-gral").value = ""; */

  document.getElementById("btn-searchGeneral").addEventListener("click", () => {
    /* e.preventDefault();
    e.stopPropagation(); */
    reportGeneral();
  });

  document.getElementById("btn-searchDep").addEventListener("click", e => {
    e.preventDefault();
    e.stopPropagation();
    listDepart();
  });
  document.querySelector("#myTabReport").addEventListener("click", () => {
    let selectores = [
      "#inicio-gral",
      "#termino-gral",
      "#inicio_dep",
      "#termino_dep"
    ];
    clean_Validations(selectores);
  });
});

function reportGeneral() {
  let inicio = document.getElementById("inicio-gral");
  let termino = document.getElementById("termino-gral");

  let selectores = ["#inicio-gral", "#termino-gral"];
  validClass(selectores);
  if (inicio.value != "" && termino.value != "") {
    var data = new FormData();
    data.append("inicio", inicio.value.trim() + " 00:00:00");
    data.append("termino", termino.value.trim() + " 23:59:59");
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

          let fechaInicio = inicio.value + " 00:00:00";
          let fechaTermino = termino.value + " 23:59:59";

          document.getElementById(
            "linkToPdf"
          ).innerHTML = `<a class="btn btn-secondary mb-2 text-white" href="reporte_pdf.php?inicio=${fechaInicio}&&termino=${fechaTermino}" target="_blank">PDF</a>`;

          for (const dep of json.depart) {
            document.getElementById(
              "tbody-depart"
            ).innerHTML += `<tr><td class='text-uppercase'>${dep.depart}</td><td>${dep.Cantidad}</td></tr>`;
          }
          for (const element of json.model) {
            document.getElementById(
              "tbody-model"
            ).innerHTML += `<tr><td>${element.Marca}</td><td>${element.Modelo}</td><td>${element.Tipo}</td><td>${element.Cantidad}</td></tr>`;
            clean_Validations(selectores);
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

  let selectores = ["#inicio_dep", "#termino_dep"];
  validClass(selectores);
  if (inicio.value != "" && termino.value != "") {
    var data = new FormData();
    data.append("inicio_dep", inicio.value.trim() + " 00:00:00");
    data.append("termino_dep", termino.value.trim() + " 23:59:59");
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
            Fechainicio = inicio.value + " 00:00:00";
            Fechatermino = termino.value + " 23:59:59";

            document.getElementById(
              "tbody-dep"
            ).innerHTML += `<tr><td id=${dep.iddireccion}>${dep.direccion}</td><td><a href='./reporte_dep_pdf.php?iddir=${dep.iddireccion}&&nombre_dir=${dep.direccion}&&inicio_dep=${Fechainicio}&&termino_dep=${Fechatermino} ' target="_blank">Generar pdf</a></td></tr>`;
          }
          clean_Validations(selectores);
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
