document.addEventListener("DOMContentLoaded", function() {
  var forms = document.getElementsByClassName("needs-validation");

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

  //Validar formulario
});

async function sendData() {
  let cantidad = document.getElementById("inputCantidad").value.trim();
  let marca = document.getElementById("inputMarca").value.trim();
  let modelo = document.getElementById("inputModelo").value.trim();
  let tipo = document.getElementById("selectTipo").value.trim();
  let bodega = document.getElementById("selectUbicacion").value.trim();
  let impresora = document.getElementById("inputModeloImpresora").value.trim();

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
        alertSuccess();
        document.getElementById("formNuevo").reset();
        setTimeout(() => document.location.reload(), 1000);
      } else {
        alertError();
        //throw "Error en la llamada fetch";
      }
    })
    .catch(err => {
      alertError();
      console.log(err);
    });
}

function cleanForm() {
  let input = document
    .querySelector("#inputMarca")
    .classList.contains(".is-invalid");
  console.log(input);
}

function validationForm() {}
