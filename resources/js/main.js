/**Funcion para llamar al toast de exito  */
function alertSuccess() {
  toastr.success("Operación exitosa", "Exito", {
    positionClass: "toast-bottom-right",
    timeOut: "3500"
  });
}

/**Funcion para llamar al toast de error  */
function alertError() {
  toastr.error("Operación fallida", "Error", {
    positionClass: "toast-bottom-right"
    // timeOut: "3500"
  });
}

function customAlertError(mensaje) {
  toastr.error(mensaje, "Error", {
    positionClass: "toast-bottom-right",
    timeOut: "3500"
  });
}
function customAlertSuccess(mensaje) {
  toastr.success(mensaje, "Exito", {
    positionClass: "toast-bottom-right",
    timeOut: "3500"
  });
}

function alertErrorFormEmpty() {
  toastr.error("Complete todos los campos", "Error", {
    positionClass: "toast-bottom-right",
    timeOut: "3500"
  });
}

function validClass(selector) {
  let input = document.querySelector(selector);

  if (input.value === "") {
    if (input.classList.contains("is-valid")) {
      input.classList.remove("is-valid");
      input.classList.add("is-invalid");
    }
    input.classList.add("is-invalid");

    // return false;
  } else {
    if (input.classList.contains("is-invalid")) {
      input.classList.remove("is-invalid");
      input.classList.add("is-valid");
    }
    input.classList.add("is-valid");
    //return true;
  }
}
