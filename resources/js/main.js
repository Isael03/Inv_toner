/**@description Funciones para llamar a los toast */
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
//--------------------------------------------------------------------------------------------------------
/**@description  lista de autocompletado del modal retirar*/

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

      //miembro.substr(0, funcionario.length).toLowerCase()
      for (miembro of ListaFuncionarios) {
        if (
          miembro.substr(0, funcionario.length).toLowerCase() ===
          funcionario.toLowerCase()
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
//-------------------------------------------------------------

/**@description marcar bordes para indicar si el input es valido o no
 * @param {string[]} selectores */
function validClass(selectores) {
  for (let index = 0; index < selectores.length; index++) {
    let input = document.querySelector(selectores[index]);

    if (input.value === "" || input.value < 1) {
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

function validarCantidades(IDmin, IDmax) {
  let idmin = document.querySelector(IDmin);
  let idmax = document.querySelector(IDmax);

  if (idmin.value >= idmax.value) {
    idmin.classList.add("is-invalid");
    idmax.classList.add("is-invalid");
  } else {
    idmin.classList.add("is-valid");
    idmax.classList.add("is-valid");
  }
}

/**@description limpiar bordes de los input validados
 * @param {array} selectores */
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

/**@description funcion generica para hacer peticiones a la bd  */
async function fetchURL(url, method = "GET", data = {}) {
  if (method === "GET") {
    var config = {
      method: method,
      headers: {
        Accept: "application/json"
      }
    };
  }
  if (method === "POST") {
    var config = {
      method: method,
      headers: {
        Accept: "application/json"
      },
      body: data
    };
  }

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

/**Mostrar bodegas en el select de Ubicacion */
function selectStorage(idinput, url) {
  fetchURL(url)
    .then(res => {
      res.data.forEach(bodega => {
        document.getElementById(
          idinput
        ).innerHTML += `<option value=${bodega.Id_bodega}>${bodega.Lugar}</option>`;
      });
    })
    .catch(err => {
      console.log(err);
    });
}
