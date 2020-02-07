/**Funcion para llamar al toast de exito  */
function alertSuccess() {
  toastr.success("Operación exitosa", "Exito", {
    positionClass: "toast-bottom-right",
    timeOut: "3500"
  });
}
function alertWarning(mensaje) {
  toastr.warning(mensaje, "Advertencia", {
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

function autocompletar() {
  const inputFuncionarios = document.querySelector("#receivedBy");
  let indexFocus = -1;

  inputFuncionarios.addEventListener("input", function() {
    const funcionario = this.value;

    if (!funcionario) return false;

    cerrarLista();

    //lsita de sugerencias
    const divList = document.createElement("div");
    divList.setAttribute("id", this.id + "-lista-autocompletar");
    divList.setAttribute("class", "lista-autocompletar-item");
    this.parentNode.appendChild(divList);

    //conexion a bd

    httpRequest("./api/funcionario/list.php?nombre=" + funcionario, function() {
      // console.log(this.responseText);
      const ListaFuncionarios = JSON.parse(this.responseText);

      /* validar arreglo vs input */
      if (ListaFuncionarios.length == 0) return false;

      for (miembro of ListaFuncionarios) {
        if (
          miembro.substr(0, funcionario.length).toLowerCase() === funcionario
        ) {
          const elementoLista = document.createElement("div");
          elementoLista.innerHTML = `<strong>${miembro.substr(
            0,
            funcionario.length
          )}</strong>${miembro.substr(funcionario.length)}`;

          elementoLista.addEventListener("click", function() {
            inputFuncionarios.value = this.innerText;
            cerrarLista();
            return false;
          });

          divList.appendChild(elementoLista);
        }
      }
    });
  });

  inputFuncionarios.addEventListener("keydown", function(e) {
    const divList = document.querySelector(
      "#" + this.id + "-lista-autocompletar"
    );
    let items;
    if (divList) {
      items = divList.querySelectorAll("div");

      switch (e.keyCode) {
        case 40 /* tecla abajo */:
          indexFocus++;
          if (indexFocus > items.length - 1) indexFocus = items.length - 1;
          break;
        case 38 /* tecla arriba */:
          indexFocus--;
          if (indexFocus < 0) indexFocus = 0;
          break;
        case 13 /* presiona enter */:
          e.preventDefault();
          items[indexFocus].click();
          index = -1;
          break;

        default:
          break;
      }

      seleccionar(items, indexFocus);
      return false;
    }
  });

  document.addEventListener("click", function() {
    cerrarLista();
  });
}

function seleccionar(items, indexFocus) {
  if (!items || indexFocus == -1) return false;

  for (item of items) {
    item.classList.remove("autocompletar-active");
    items[indexFocus].classList.add("autocompletar-active");
  }
}

function cerrarLista() {
  const items = document.querySelectorAll(".lista-autocompletar-item");

  for (const item of items) {
    item.parentNode.removeChild(item);
  }
  indexFocus = -1;
}
//autocompletar(["perro", "gato", "conejo", "pez"]);

function httpRequest(url, callback) {
  const http = new XMLHttpRequest();
  http.open("GET", url);
  http.send();

  http.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      callback.apply(http);
    }
  };
}

/**@param {array} selectores */

function validClass(selectores) {
  for (let index = 0; index < selectores.length; index++) {
    let input = document.querySelector(selectores[index]);

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
}

function clean_Validations(selectores) {
  for (let index = 0; index < selectores.length; index++) {
    let input = document.querySelector(selectores[index]);
    if (input.classList.contains("is-valid")) {
      input.classList.remove("is-valid");
    }
    if (input.classList.contains("is-invalid")) {
      input.classList.remove("is-invalid");
    }
  }
}

async function fetchURL(url, method = "GET", data = {}) {
  let config = {
    method: method,
    headers: {
      Accept: "application/json"
    },
    body: data
  };

  try {
    const response = await fetch(url, config);

    if (response.ok) {
      const json = await response.json();
      return json;
    } else {
      alertError();
    }
  } catch (error) {
    alertError();
    console.log(error);
  }
}
