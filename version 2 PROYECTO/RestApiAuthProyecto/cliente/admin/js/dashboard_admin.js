// Declarar funciones globales antes del DOMContentLoaded
window.listarClientes = function () {
  fetch("http://localhost/RestApiAuthProyecto/servidor/api/UsuariosAPI.php?action=listarClientes")
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success") {
        const clientes = data.message;
        document.getElementById("tbodyClientes").innerHTML = generarFilasClientes(clientes);
      } else {
        document.getElementById("tbodyClientes").innerHTML = "<tr><td colspan='6'>Error al cargar los clientes.</td></tr>";
      }
    })
    .catch((error) => {
      document.getElementById("tbodyClientes").innerHTML = "<tr><td colspan='6'>Error de conexión.</td></tr>";
      console.error("Error al listar clientes:", error);
    });
};

window.listarContratistas = function () {
  fetch("http://localhost/RestApiAuthProyecto/servidor/api/UsuariosAPI.php?action=listarContratistas")
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success") {
        const contratistas = data.message;
        document.getElementById("tbodyContratistas").innerHTML = generarFilasContratistas(contratistas);
      } else {
        document.getElementById("tbodyContratistas").innerHTML = "<tr><td colspan='7'>Error al cargar los contratistas.</td></tr>";
      }
    })
    .catch((error) => {
      document.getElementById("tbodyContratistas").innerHTML = "<tr><td colspan='7'>Error de conexión.</td></tr>";
      console.error("Error al listar contratistas:", error);
    });
};

document.addEventListener("DOMContentLoaded", function () {
  // ✅ Llamadas iniciales
  listarClientes();
  listarContratistas();

  // 👇 Eventos como guardar cliente, guardar contratista, cerrar modales, etc.
  document.getElementById("formEditarCliente").addEventListener("submit", function (e) {
    e.preventDefault();
    const datos = {
      Id_Cliente: document.getElementById("editarIdCliente").value,
      Nombre: document.getElementById("editarNombreCliente").value,
      Direccion: document.getElementById("editarDireccionCliente").value,
      Telefono: document.getElementById("editarTelefonoCliente").value,
      Correo_electronico: document.getElementById("editarCorreoCliente").value,
    };

    fetch("../../servidor/api/UsuariosAPI.php?action=actualizarCliente", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(datos),
    })
      .then((res) => res.json())
      .then((data) => {
        alert(data.message);
        console.log("Respuesta API:", data);
        cerrarModal("Cliente");
        listarClientes(); // <- esta ya está definida globalmente
      })
      .catch((error) => console.error("Error al actualizar cliente:", error));
  });

});
function generarFilasClientes(clientes) {
  return clientes.map(cliente => `
    <tr>
      <td>${cliente.Id_Cliente}</td>
      <td>${cliente.Nombre}</td>
      <td>${cliente.Direccion}</td>
      <td>${cliente.Telefono}</td>
      <td>${cliente.Correo_electronico}</td>
      <td>
        <button class="btn-editar" onclick='abrirModalEditarCliente(${JSON.stringify(cliente)})'>✏️ Editar</button>
        <button class="btn-eliminar" onclick='eliminarCliente(${cliente.Id_Cliente})'>🗑️ Eliminar</button>
      </td>
    </tr>
  `).join('');
}

function generarFilasContratistas(contratistas) {
  return contratistas.map(contratista => `
    <tr>
      <td>${contratista.Id_contratista}</td>
      <td>${contratista.Nombre}</td>
      <td>${contratista.Direccion}</td>
      <td>${contratista.Telefono}</td>
      <td>${contratista.Correo_electronico}</td>
      <td>${contratista.Especialidad}</td>
      <td>
        <button class="btn-editar" onclick='abrirModalEditarContratista(${JSON.stringify(contratista)})'>✏️ Editar</button>
        <button class="btn-eliminar" onclick='eliminarContratista(${contratista.Id_contratista})'>🗑️ Eliminar</button>
      </td>
    </tr>
  `).join('');
}

  
  const btnClientes = document.getElementById("btnClientes");
  const btnContratistas = document.getElementById("btnContratistas");
  const btnLogout = document.getElementById("btnLogout");

  const seccionClientes = document.getElementById("seccionClientes");
  const seccionContratistas = document.getElementById("seccionContratistas");

  // Buscar cliente por ID
  document.getElementById("btnBuscarCliente").addEventListener("click", () => {
    const id = document.getElementById("buscarClienteID").value;
    if (!id) return alert("Ingresa un ID");

    fetch(`../../servidor/api/UsuariosAPI.php?action=obtenerClientePorID&id=${id}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === "success") {
          const cliente = data.message;
          document.getElementById("tbodyClientes").innerHTML = generarFilasClientes([cliente]);
        } else {
          document.getElementById("tbodyClientes").innerHTML = "<tr><td colspan='6'>Cliente no encontrado.</td></tr>";
        }
      })
      .catch(error => {
        console.error("Error buscando cliente:", error);
        document.getElementById("tbodyClientes").innerHTML = "<tr><td colspan='6'>Error al buscar cliente.</td></tr>";
      });
  });

  // Buscar contratista por ID
  document.getElementById("btnBuscarContratista").addEventListener("click", () => {
    const id = document.getElementById("buscarContratistaID").value;
    if (!id) return alert("Ingresa un ID");

    fetch(`../../servidor/api/UsuariosAPI.php?action=obtenerContratistaPorID&id=${id}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === "success") {
          const contratista = data.message;
          document.getElementById("tbodyContratistas").innerHTML = generarFilasContratistas([contratista]);
        } else {
          document.getElementById("tbodyContratistas").innerHTML = "<tr><td colspan='7'>Contratista no encontrado.</td></tr>";
        }
      })
      .catch(error => {
        console.error("Error buscando contratista:", error);
        document.getElementById("tbodyContratistas").innerHTML = "<tr><td colspan='7'>Error al buscar contratista.</td></tr>";
      });
  });

  // Mostrar sección de Clientes
  btnClientes.addEventListener("click", function () {
    mostrarSeccion(seccionClientes);
    listarClientes();
  });

  // Mostrar sección de Contratistas
  btnContratistas.addEventListener("click", function () {
    mostrarSeccion(seccionContratistas);
    listarContratistas();
  });

  // Cerrar sesión
  btnLogout.addEventListener("click", function () {
    localStorage.removeItem("token");
    localStorage.removeItem("tipoUsuario");
    window.location.href = "http://localhost/RestApiAuthProyecto/cliente/login.php";
  });

  // Mostrar solo una sección a la vez
  function mostrarSeccion(seccion) {
    seccionClientes.style.display = "none";
    seccionContratistas.style.display = "none";
    seccion.style.display = "block";
  }

  
  // Listar Clientes desde API
  function listarClientes() {
    fetch("http://localhost/RestApiAuthProyecto/servidor/api/UsuariosAPI.php?action=listarClientes")
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          const clientes = data.message;
          document.getElementById("tbodyClientes").innerHTML = generarFilasClientes(clientes);
        } else {
          document.getElementById("tbodyClientes").innerHTML = "<tr><td colspan='6'>Error al cargar los clientes.</td></tr>";
        }
      })
      .catch((error) => {
        document.getElementById("tbodyClientes").innerHTML = "<tr><td colspan='6'>Error de conexión.</td></tr>";
        console.error("Error al listar clientes:", error);
      });
  }

  // Listar Contratistas desde API
  function listarContratistas() {
    fetch("http://localhost/RestApiAuthProyecto/servidor/api/UsuariosAPI.php?action=listarContratistas")
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          const contratistas = data.message;
          document.getElementById("tbodyContratistas").innerHTML = generarFilasContratistas(contratistas);
        } else {
          document.getElementById("tbodyContratistas").innerHTML = "<tr><td colspan='7'>Error al cargar los contratistas.</td></tr>";
        }
      })
      .catch((error) => {
        document.getElementById("tbodyContratistas").innerHTML = "<tr><td colspan='7'>Error de conexión.</td></tr>";
        console.error("Error al listar contratistas:", error);
      });
  }

  // Tabla de Clientes
  function generarFilasClientes(clientes) {
    let html = "";
    clientes.forEach((cliente) => {
      html += `<tr>
        <td>${cliente.Id_Cliente}</td>
        <td>${cliente.Nombre}</td>
        <td>${cliente.Direccion}</td>
        <td>${cliente.Telefono}</td>
        <td>${cliente.Correo_electronico}</td>
        <td>
          <button class="btnEditarCliente" data-id="${cliente.Id_Cliente}">Editar</button>
          <button class="btnEliminarCliente" data-id="${cliente.Id_Cliente}">Eliminar</button>
        </td>
      </tr>`;
    });

    // Editar cliente
    
document.getElementById("tbodyClientes").addEventListener("click", (e) => {
  if (e.target.classList.contains("btnEditarCliente")) {
    const id = e.target.dataset.id;

    fetch(`../../servidor/api/UsuariosAPI.php?action=obtenerClientePorID&id=${id}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === "success") {
          abrirModalEditarCliente(data.message); 
        } else {
          alert("Cliente no encontrado.");
        }
      });
  }
});
    return html;
  }

  // Tabla de Contratistas
  function generarFilasContratistas(contratistas) {
    let html = "";
    contratistas.forEach((contratista) => {
      html += `<tr>
        <td>${contratista.Id_contratista}</td>
        <td>${contratista.Nombre}</td>
        <td>${contratista.Direccion}</td>
        <td>${contratista.Telefono}</td>
        <td>${contratista.Correo_electronico}</td>
        <td>${contratista.Especialidad}</td>
        <td>
          <button class="btnEditarContratista" data-id="${contratista.Id_contratista}">Editar</button>
          <button class="btnEliminarContratista" data-id="${contratista.Id_contratista}">Eliminar</button>
        </td>
      </tr>`;
    });
    // Editar contratista
document.getElementById("tbodyContratistas").addEventListener("click", (e) => {
  if (e.target.classList.contains("btnEditarContratista")) {
    const id = e.target.dataset.id;

    fetch(`../../servidor/api/UsuariosAPI.php?action=obtenerContratistaPorID&id=${id}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === "success") {
          abrirModalEditarContratista(data.message); 
        } else {
          alert("Contratista no encontrado.");
        }
      });
  }
});
    return html;

  }
// eliminar cliente
  document.getElementById("tbodyClientes").addEventListener("click", (e) => {
    if (e.target.classList.contains("btnEliminarCliente")) {
      const id = e.target.dataset.id;
      if (confirm("¿Seguro que deseas eliminar este cliente?")) {
        fetch(`../../servidor/api/UsuariosAPI.php?action=eliminarCliente&id=${id}`, { method: "DELETE" })
          .then(res => res.json())
          .then(data => {
            alert(data.message);
            listarClientes(); // Recargar la lista después de eliminar
          })
          .catch(err => console.error("Error al eliminar cliente:", err));
      }
    }
  
  });

//eliminar contratista
    document.getElementById("tbodyContratistas").addEventListener("click", (e) => {
      if (e.target.classList.contains("btnEliminarContratista")) {
        const id = e.target.dataset.id;
        if (confirm("¿Seguro que deseas eliminar este Contratista?")) {
          fetch(`../../servidor/api/UsuariosAPI.php?action=eliminarContratista&id=${id}`, { method: "DELETE" })
            .then(res => res.json())
            .then(data => {
              alert(data.message);
              listarContratistas(); // Recargar la lista después de eliminar
            })
            .catch(err => console.error("Error al eliminar Contratista:", err));
        }
      }
    });

// GUARDAR CAMBIOS CLIENTE
      document.getElementById("formEditarCliente").addEventListener("submit", function (e) {
        e.preventDefault(); // <-- Previene el envío por defecto
        
        const datos = {
          Id_Cliente: document.getElementById("editarIdCliente").value,
          Nombre: document.getElementById("editarNombreCliente").value,
          Direccion: document.getElementById("editarDireccionCliente").value,
          Telefono: document.getElementById("editarTelefonoCliente").value,
          Correo_electronico: document.getElementById("editarCorreoCliente").value,
        };
        
        fetch("../../servidor/api/UsuariosAPI.php?action=actualizarCliente", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(datos),
        })
          .then((res) => res.json())
          .then((data) => {
            alert(data.message);
            console.log("Respuesta API:", data);
            cerrarModal("Cliente");
            listarClientes();
          })
        .catch((error) => console.error("Error al actualizar cliente:", error));
      });
        
          // GUARDAR CAMBIOS CONTRATISTA
      document.getElementById("formEditarContratista").addEventListener("submit", function (e) {
        e.preventDefault(); // <-- Previene el envío por defecto
        
        const datos = {
          Id_contratista: document.getElementById("editarIdContratista").value,
          Nombre: document.getElementById("editarNombreContratista").value,
          Direccion: document.getElementById("editarDireccionContratista").value,
          Telefono: document.getElementById("editarTelefonoContratista").value,
          Correo_electronico: document.getElementById("editarCorreoContratista").value,
          Especialidad: document.getElementById("editarEspecialidadContratista").value,
        };
        
        fetch("../../servidor/api/UsuariosAPI.php?action=actualizarContratista", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(datos),
        })
          .then((res) => res.json())
          .then((data) => {
            alert(data.message);
            console.log("Respuesta API:", data);
            cerrarModal("Contratista");
            listarContratistas();
            })
          .catch((error) => console.error("Error al actualizar contratista:", error));
        });
    
      // CANCELAR CLIENTE
  document.getElementById("btnCancelarCliente").addEventListener("click", function () {
    cerrarModal("Cliente");
      });
    
      // CANCELAR CONTRATISTA
  document.getElementById("btnCancelarContratista").addEventListener("click", function () {
    cerrarModal("Contratista");
      
      });
    
// Abrir modal de cliente
function abrirModalEditarCliente(cliente) {
  document.getElementById("editarIdCliente").value = cliente.Id_Cliente;
  document.getElementById("editarNombreCliente").value = cliente.Nombre;
  document.getElementById("editarDireccionCliente").value = cliente.Direccion;
  document.getElementById("editarTelefonoCliente").value = cliente.Telefono;
  document.getElementById("editarCorreoCliente").value = cliente.Correo_electronico;
  document.getElementById("modalEditarCliente").style.display = "flex";
}

// Abrir modal de contratista
function abrirModalEditarContratista(contratista) {
  document.getElementById("editarIdContratista").value = contratista.Id_contratista;
  document.getElementById("editarNombreContratista").value = contratista.Nombre;
  document.getElementById("editarDireccionContratista").value = contratista.Direccion;
  document.getElementById("editarTelefonoContratista").value = contratista.Telefono;
  document.getElementById("editarCorreoContratista").value = contratista.Correo_electronico;
  document.getElementById("editarEspecialidadContratista").value = contratista.Especialidad;
  document.getElementById("modalEditarContratista").style.display = "flex";
}

function cerrarModal(tipo) {
  if (tipo === "Cliente") {
    document.getElementById("modalEditarCliente").style.display = "none";
  } else if (tipo === "Contratista") {
    document.getElementById("modalEditarContratista").style.display = "none";
  }
}
