// Variable global para almacenar el id de la solicitud a responder
let currentSolicitudIdPresupuesto = null;
let currentSolicitudIdOferta = null;


document.addEventListener('DOMContentLoaded', () => {
  // 1️⃣ Cerrar sesión
  document.getElementById('btnCerrarSesion')?.addEventListener('click', () => {
    sessionStorage.clear();
    window.location.href = '../logout.php';
  });

  // 2️⃣ Inicializar pestañas de Bootstrap
  const tabs = document.querySelectorAll('#tabsContratista button[data-bs-toggle="tab"]');
  tabs.forEach(tab => {
    tab.addEventListener('shown.bs.tab', e => {
      const tgt = e.target.getAttribute('data-bs-target');
      if (tgt === '#presupuestos-pendientes') {
        cargarPresupuestosPendientesContratista();
      } else if (tgt === '#pane-servicios-activos') {
        cargarServiciosActivos();
      } else if (tgt === '#servicios-disponibles') {
        cargarServiciosDisponibles();
      } else if (tgt === '#pane-finalizados') {
        cargarFinalizados();
      }
    });
  });

  // Asignar evento para responder dentro del modal
  document.getElementById('btnModalResponder').addEventListener('click', enviarRespuestaSolicitud);
});
cargarPresupuestosPendientesContratista();


// ─── 1) Solicitudes Pendientes ─────────────────────────────────────────────
function cargarPresupuestosPendientesContratista() {
  fetch('/RestApiAuthProyecto/servidor/api/SolicitudesAPI.php?action=presupuestosPendientesContratista', {
    credentials: 'include'
  })
  .then(res => {
    if (res.status === 401) {
      window.location.href = '../login.php';
      return;
    }
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  })
  .then(data => {
    if (!Array.isArray(data)) throw new Error('Esperaba un array en cargarPresupuestosPendientesContratista');
    const body = document.getElementById('presupuestos-pendientes-body');
    body.innerHTML = '';
    data.forEach((s, i) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${i + 1}</td>
        <td>${s.Nombre_cliente}</td>
        <td>${s.Titulo}</td>
        <td>${s.Categoria}</td>
        <td>${s.Subcategoria}</td>
        <td>${s.Descripcion}</td>
        <td>${new Date(s.Fecha_solicitud).toLocaleDateString()}</td>
        <td>
          <button class="btn btn-sm btn-primary" onclick="abrirModalRespuesta(${s.Id_solicitud})">
            Responder
          </button>
        </td>`;
      body.appendChild(tr);
    });
  })
  .catch(err => {
    console.error(err);
    document.getElementById('presupuestos-pendientes-body').innerHTML =
      '<tr><td colspan="7" class="text-danger">Error al cargar pendientes.</td></tr>';
  });
}

// Función para abrir el modal y guardar el id de la solicitud a responder.
function abrirModalRespuesta(id) {
  currentSolicitudIdPresupuesto = id;
  // Limpiar los campos del modal
  document.getElementById('modalPrecio').value = '';
  document.getElementById('modalMensaje').value = '';
  // Mostrar el modal (usando la API de Bootstrap 5)
  const modalElement = document.getElementById('responderModal');
  const modalInstance = new bootstrap.Modal(modalElement);
  modalInstance.show();
}


// Envía la respuesta (precio y mensaje) de PRESUPUESTO a través del API.
function enviarRespuestaSolicitud() {
  const precio = document.getElementById('modalPrecio').value;
  const mensaje = document.getElementById('modalMensaje').value;

  fetch('/RestApiAuthProyecto/servidor/api/SolicitudesAPI.php?action=responderSolicitud', {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id_solicitud: currentSolicitudIdPresupuesto, precio_estimado: precio, mensaje_respuesta: mensaje })
  })
  .then(res => {
    if (res.status === 401) {
      window.location.href = '../login.php';
      return;
    }
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  })
  .then(json => {
    alert(json.message);

    // Ocultar el modal tras la respuesta
    const modalElement = document.getElementById('responderModal');
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    modalInstance.hide();
    // Actualizar la lista de pendientes
    cargarPresupuestosPendientesContratista();
  })
  .catch(err => {
    console.error(err);
    alert('Error al responder solicitud.');
  });
}

// ─── 2) Solicitudes en Proceso ─────────────────────────────────────────────
function cargarServiciosActivos() {
  fetch('/RestApiAuthProyecto/servidor/api/SolicitudesAPI.php?action=serviciosActivos', {
    credentials: 'include'
  })
  .then(res => {
    if (res.status === 401) window.location.href = '../login.php';
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  })
  .then(data => {
    console.log('Servicios activos recibidos:', data);
    const cont = document.getElementById('servicios-activos');
    cont.innerHTML = '';
    data.forEach(s => {
      const div = document.createElement('div');
      div.className = 'alert alert-info d-flex justify-content-between align-items-center';
      div.innerHTML = `
        <div>
          <strong>${s.Nombre_cliente}</strong><br>
          <strong>${s.Titulo}</strong><br>
          <strong>Precio: $${s.Precio_estimado}</strong><br>
          <small>${s.Descripcion}</small>
        </div>
        <button class="btn btn-sm btn-success" onclick="marcarCompletado(${s.Id_solicitud})">
          Completado
        </button>`;
      cont.appendChild(div);
    });
  })
  .catch(err => {
    console.error('Error al cargar servicios activos:', err);
    document.getElementById('servicios-activos').innerHTML =
      '<div class="text-danger">Error al cargar en proceso.</div>';
  });
}

// Asegúrate de recargar al mostrar la pestaña
document.querySelector('#tab-servicios-activos')
  .addEventListener('shown.bs.tab', cargarServiciosActivos);

// Llamada inicial si la pestaña está activa al cargar la página
if (document.querySelector('#tab-servicios-activos').classList.contains('active')) {
  cargarServiciosActivos();
}
// Función para marcar un servicio como completado
function marcarCompletado(id) {
  fetch('/RestApiAuthProyecto/servidor/api/SolicitudesAPI.php?action=marcarCompletado', {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id_solicitud: id })
  })
  .then(res => {
    if (res.status === 401) window.location.href = '../login.php';
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  })
  .then(json => {
    alert(json.message);
    // Actualizamos la lista de servicios activos
    cargarServiciosActivos();
  })
  .catch(err => {
    console.error(err);
    alert('Error al marcar completado.');
  });
}
 
// ─── 3) Solicitudes Completadas ────────────────────────────────────────────

function cargarFinalizados() {
  fetch('/RestApiAuthProyecto/servidor/api/SolicitudesAPI.php?action=serviciosFinalizados', {
    credentials: 'include'
  })
  .then(res => res.json())
  .then(data => {
    console.log("SERVICIOS FINALIZADOS RAW:", data);
    const cont = document.getElementById('finalizados');
    cont.innerHTML = '';

    // Aquí sí puedes usar 's' porque viene de data.forEach
    data.forEach(s => {
      const fecha = s.Fecha_respuesta
        ? new Date(s.Fecha_respuesta).toLocaleDateString()
        : new Date(s.Fecha_solicitud).toLocaleDateString();

      const div = document.createElement('div');
      div.className = 'alert alert-success';
      div.textContent = `${s.Titulo} — completado el ${fecha}`;
      cont.appendChild(div);
    });
  })
  .catch(err => {
    console.error('Error al cargar finalizados:', err);
  });
}

// Llama a cargarFinalizados cuando se abra la pestaña
document.querySelector('#tab-finalizados')
  .addEventListener('shown.bs.tab', cargarFinalizados);


cargarServiciosDisponibles();

// ─── 1) Carga los servicios disponibles en la tabla ────────────────────────



function cargarServiciosDisponibles() {
  fetch('/RestApiAuthProyecto/servidor/api/SolicitudesAPI.php?action=serviciosDisponibles', {
    credentials: 'include'
  })
  .then(res => {
    if (res.status === 401) {
      // Si no está autenticado, redirige al login
      window.location.href = '../login.php';
      return;
    }
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  })
  .then(data => {
    if (!Array.isArray(data)) throw new Error('Se esperaba un array al cargar servicios disponibles');
    const tbody = document.getElementById('servicios-disponibles-body');
    tbody.innerHTML = '';
    data.forEach((s, i) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${i + 1}</td>
        <td>${s.Nombre_cliente}</td>
        <td>${s.Titulo}</td>
        <td>${s.Categoria}</td>
        <td>${s.Descripcion}</td>
        <td>${new Date(s.Fecha_solicitud).toLocaleDateString()}</td>
        <td>
          <button class="btn btn-sm btn-success" onclick="responderServicio(${s.Id_solicitud})">
            Enviar Oferta
          </button>
        </td>`;
      tbody.appendChild(tr);
    });
  })
  .catch(err => {
    console.error(err);
    const tbody = document.getElementById('servicios-disponibles-body');
    tbody.innerHTML = `
      <tr>
        <td colspan="5" class="text-danger">Error al cargar servicios disponibles.</td>
      </tr>`;
  });
}

//FUNCION PARA OFERTAS DE SERVICIO

function responderServicio(idSol) {
  currentSolicitudIdOferta = idSol;
  document.getElementById('oferta-precio').value = '';
  document.getElementById('oferta-mensaje').value = '';
  const modal = new bootstrap.Modal(document.getElementById('modalOferta'));
  modal.show();
}

function enviarOfertaServicio() {
  const precio = parseFloat(document.getElementById('oferta-precio').value);
  const mensaje = document.getElementById('oferta-mensaje').value.trim();

  if (!currentSolicitudIdOferta || isNaN(precio) || precio <= 0) {
    alert('Por favor completa todos los datos correctamente.');
    return;
  }

  fetch('/RestApiAuthProyecto/servidor/api/OfertasAPI.php?action=guardar', {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      Id_solicitud: currentSolicitudIdOferta,      // con mayúscula exacta
      Precio_ofertado: precio,                     // igual que en el backend
      Mensaje: mensaje                             // opcional
    })
  })
  .then(res => {
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  })
  .then(json => {
    alert(json.message || 'Oferta enviada correctamente');
    bootstrap.Modal.getInstance(document.getElementById('modalOferta')).hide();
    cargarServiciosDisponibles();
  })
  .catch(err => {
    console.error('Error al enviar oferta:', err);
    alert('Ocurrió un error al enviar la oferta');
  });
}

