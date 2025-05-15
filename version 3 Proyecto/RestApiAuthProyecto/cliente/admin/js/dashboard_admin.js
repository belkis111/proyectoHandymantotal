// dashboard_admin.js - Versión corregida y unificada

// Instancias de modales Bootstrap
let modalEditarCliente, modalEditarContratista;

document.addEventListener("DOMContentLoaded", () => {
  // Crear instancias de modal
  const modalCliEl = document.getElementById("modalEditarCliente");
  modalEditarCliente = new bootstrap.Modal(modalCliEl);

  const modalConEl = document.getElementById("modalEditarContratista");
  modalEditarContratista = new bootstrap.Modal(modalConEl);

  // 1) Cargar datos iniciales de la pestaña activa (Clientes)
  listarClientes();

  // 2) Eventos de pestañas de Bootstrap
  document.getElementById("tab-clientes").addEventListener("shown.bs.tab", listarClientes);
  document.getElementById("tab-contratistas").addEventListener("shown.bs.tab", listarContratistas);

  // 3) Búsqueda por ID
  document.getElementById("btnBuscarCliente").addEventListener("click", buscarClientePorID);
  document.getElementById("btnBuscarContratista").addEventListener("click", buscarContratistaPorID);

  // 4) Delegación: editar / eliminar en tablas
  document.getElementById("tbodyClientes").addEventListener("click", manejarClickClientes);
  document.getElementById("tbodyContratistas").addEventListener("click", manejarClickContratistas);

  // 5) Envió formularios de edición
  document.getElementById("formEditarCliente").addEventListener("submit", submitEditarCliente);
  document.getElementById("formEditarContratista").addEventListener("submit", submitEditarContratista);

  // 6) Cerrar sesión

document.getElementById("btnLogout").addEventListener("click", () => {
  localStorage.clear(); // Limpia cualquier token o datos locales
  window.location.href = "../../cliente/logout.php"; // 🔁 Redirige directamente al logout en el servidor
});
});

// ================= API y Listados =================
window.listarClientes = () => {
  fetch("http://localhost/RestApiAuthProyecto/servidor/api/UsuariosAPI.php?action=listarClientes")
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById("tbodyClientes");
      tbody.innerHTML = data.status === "success"
        ? generarFilasClientes(data.message)
        : "<tr><td colspan='6'>Error al cargar los clientes.</td></tr>";
    })
    .catch(() => {
      document.getElementById("tbodyClientes").innerHTML =
        "<tr><td colspan='6'>Error de conexión.</td></tr>";
    });
};

window.listarContratistas = () => {
  fetch("http://localhost/RestApiAuthProyecto/servidor/api/UsuariosAPI.php?action=listarContratistas")
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById("tbodyContratistas");
      tbody.innerHTML = data.status === "success"
        ? generarFilasContratistas(data.message)
        : "<tr><td colspan='7'>Error al cargar los contratistas.</td></tr>";
    })
    .catch(() => {
      document.getElementById("tbodyContratistas").innerHTML =
        "<tr><td colspan='7'>Error de conexión.</td></tr>";
    });
};

// ================= Generadores de filas =================
function generarFilasClientes(clientes) {
  return clientes.map(c => `
    <tr>
      <td>${c.Id_Cliente}</td>
      <td>${c.Nombre}</td>
      <td>${c.Direccion}</td>
      <td>${c.Telefono}</td>
      <td>${c.Correo_electronico}</td>
      <td>
        <button
          class="btnEditarCliente btn btn-sm btn-light"
          data-id="${c.Id_Cliente}"
          data-bs-toggle="modal"
          data-bs-target="#modalEditarCliente"
        >✏️</button>
        <button class="btnEliminarCliente btn btn-sm btn-danger" data-id="${c.Id_Cliente}">🗑️</button>
      </td>
    </tr>
  `).join("");
}

function generarFilasContratistas(contratistas) {
  return contratistas.map(c => `
    <tr>
      <td>${c.Id_contratista}</td>
      <td>${c.Nombre}</td>
      <td>${c.Direccion}</td>
      <td>${c.Telefono}</td>
      <td>${c.Correo_electronico}</td>
      <td>${c.Especialidad}</td>
      <td>
        <button
          class="btnEditarContratista btn btn-sm btn-light"
          data-id="${c.Id_contratista}"
          data-bs-toggle="modal"
          data-bs-target="#modalEditarContratista"
        >✏️</button>
        <button class="btnEliminarContratista btn btn-sm btn-danger" data-id="${c.Id_contratista}">🗑️</button>
      </td>
    </tr>
  `).join("");
}

// ================= Manejadores de evento =================
async function manejarClickClientes(e) {
  const target = e.target;
  if (target.classList.contains("btnEditarCliente")) {
    const id = target.dataset.id;
    try {
      const res = await fetch(`../../servidor/api/UsuariosAPI.php?action=obtenerClientePorID&id=${id}`);
      const data = await res.json();
      if (data.status === "success") {
        const c = data.message;
        document.getElementById("editarIdCliente").value = c.Id_Cliente;
        document.getElementById("editarNombreCliente").value = c.Nombre;
        document.getElementById("editarDireccionCliente").value = c.Direccion;
        document.getElementById("editarTelefonoCliente").value = c.Telefono;
        document.getElementById("editarCorreoCliente").value = c.Correo_electronico;
        modalEditarCliente.show();
      }
    } catch (err) {
      console.error("Error al obtener cliente:", err);
    }
  }
  if (target.classList.contains("btnEliminarCliente")) {
    if (confirm("¿Eliminar cliente?")) {
      const id = target.dataset.id;
      fetch(`../../servidor/api/UsuariosAPI.php?action=eliminarCliente&id=${id}`, { method: "DELETE" })
        .then(r => r.json())
        .then(r => { alert(r.message); listarClientes(); });
    }
  }
}

async function manejarClickContratistas(e) {
  const target = e.target;
  if (target.classList.contains("btnEditarContratista")) {
    const id = target.dataset.id;
    try {
      const res = await fetch(`../../servidor/api/UsuariosAPI.php?action=obtenerContratistaPorID&id=${id}`);
      const data = await res.json();
      if (data.status === "success") {
        const c = data.message;
        document.getElementById("editarIdContratista").value = c.Id_contratista;
        document.getElementById("editarNombreContratista").value = c.Nombre;
        document.getElementById("editarDireccionContratista").value = c.Direccion;
        document.getElementById("editarTelefonoContratista").value = c.Telefono;
        document.getElementById("editarCorreoContratista").value = c.Correo_electronico;
        document.getElementById("editarEspecialidadContratista").value = c.Especialidad;
        modalEditarContratista.show();
      }
    } catch (err) {
      console.error("Error al obtener contratista:", err);
    }
  }
  if (target.classList.contains("btnEliminarContratista")) {
    if (confirm("¿Eliminar contratista?")) {
      const id = target.dataset.id;
      fetch(`../../servidor/api/UsuariosAPI.php?action=eliminarContratista&id=${id}`, { method: "DELETE" })
        .then(r => r.json())
        .then(r => { alert(r.message); listarContratistas(); });
    }
  }
}

// ================= Búsqueda por ID =================
function buscarClientePorID() {
  const id = document.getElementById("buscarClienteID").value;
  if (!id) return alert("Ingresa un ID");
  fetch(`../../servidor/api/UsuariosAPI.php?action=obtenerClientePorID&id=${id}`)
    .then(res => res.json())
    .then(res => {
      document.getElementById("tbodyClientes").innerHTML =
        res.status === "success"
          ? generarFilasClientes([res.message])
          : "<tr><td colspan='6'>Cliente no encontrado.</td></tr>";
    });
}

function buscarContratistaPorID() {
  const id = document.getElementById("buscarContratistaID").value;
  if (!id) return alert("Ingresa un ID");
  fetch(`../../servidor/api/UsuariosAPI.php?action=obtenerContratistaPorID&id=${id}`)
    .then(res => res.json())
    .then(res => {
      document.getElementById("tbodyContratistas").innerHTML =
        res.status === "success"
          ? generarFilasContratistas([res.message])
          : "<tr><td colspan='7'>Contratista no encontrado.</td></tr>";
    });
}

// ================= Form submissions =================
function submitEditarCliente(e) {
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
    .then(r => r.json())
    .then(r => {
      alert(r.message);
      if (r.status === "success") {
        modalEditarCliente.hide();
        listarClientes();
      }
    });
}

function submitEditarContratista(e) {
  e.preventDefault();
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
    .then(r => r.json())
    .then(r => {
      alert(r.message);
      if (r.status === "success") {
        modalEditarContratista.hide();
        listarContratistas();
      }
    });
}
