document.addEventListener("DOMContentLoaded", function() {
  var forms = document.getElementsByClassName("needs-validation");
  printMarcaPrinter();

  document.querySelector("#inputMarca").addEventListener("input", () => {
    document.querySelector(
      "#modelo_imp"
    ).innerHTML = `<option value=''>Seleccione...</option>`;
    printModelPrinter();
  });

  var validation = Array.prototype.filter.call(forms, function(form) {
    form.addEventListener(
      "submit",
      function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
          alertError();
        }
        if (form.checkValidity() === true) {
          event.preventDefault();
          event.stopPropagation();

          //Enviar datos
          sendData();
        }
        form.classList.add("was-validated");
      },
      false
    );
  });
});

async function sendData() {
  let cantidad = document.getElementById("inputCantidad").value.trim();
  let marca = document.getElementById("inputMarca").value.trim();
  let modelo = document.getElementById("modelo_con").value.trim();
  let tipo = document.getElementById("selectTipo").value.trim();
  let bodega = document.getElementById("selectUbicacion").value.trim();
  let impresora = document.getElementById("modelo_imp").value.trim();

  modelo = modelo.toUpperCase().replace(/ /g, "-");

  const data = new FormData();

  data.append("cantidad", parseInt(cantidad));
  data.append("marca", marca.toUpperCase());
  data.append("modelo", modelo);
  data.append("tipo", tipo);
  data.append("bodega", parseInt(bodega));
  data.append("impresora", impresora.toUpperCase());

  await fetch("../api/consumible/insert_consumible.php", {
    method: "POST",
    headers: { Accept: "application/json" },
    body: data
  })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        alertError();
        //throw "Error en la llamada fetch";
      }
    })
    .then(json => {
      console.log(json);

      if (json.status === "ok") {
        alertSuccess();
        document.getElementById("formNuevo").reset();
        setTimeout(() => document.location.reload(), 1000);
      } else {
        alertError();
      }
    })
    .catch(err => {
      alertError();
      console.log(err);
    });
}

async function printMarcaPrinter() {
  //case:printersBrand
  await fetch("../api/impresora/impresora.php?case=printersBrand")
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        alertError();
        //throw "Error en la llamada fetch";
      }
    })
    .then(json => {
      if (json.marca[0].Marca_impresora != "") {
        for (const impresora of json.marca) {
          document.querySelector(
            "#inputMarca"
          ).innerHTML += `<option value=${impresora.Marca_impresora}>${impresora.Marca_impresora}</option>`;
        }

        if (json.modelo_con !== undefined) {
          for (const modelo of json.modelo_con) {
            document.querySelector(
              "#inputModelo"
            ).innerHTML += `<option value=${modelo.Modelo}>`;
          }
        }
      } else {
        document.querySelector(
          "#inputMarca"
        ).innerHTML += `<option value="">No hay impresoras en el sistema</option>`;
      }
    })
    .catch(err => {
      alertError();
      console.log(err);
    });
}

async function printModelPrinter() {
  //case: showModelPrinter
  let marca = document.querySelector("#inputMarca");

  await fetch(
    `../api/impresora/impresora.php?case=showModelPrinter&&marca=${marca.value.toUpperCase()}`
  )
    .then(response => {
      return response.json();
    })
    .then(json => {
      for (const modelo of json) {
        document.querySelector(
          "#modelo_imp"
        ).innerHTML += `<option value=${modelo.Modelo_impresora}>${modelo.Modelo_impresora}</option>`;
      }
    })
    .catch(err => {
      console.log(err);
    });
}
