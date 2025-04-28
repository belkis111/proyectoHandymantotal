// Instancia global del modal de solicitud directa
const modalSolicitud = new bootstrap.Modal(
  document.getElementById('modalSolicitudDirecta'),
  { backdrop: 'static', keyboard: false }
);

document.addEventListener('DOMContentLoaded', () => {
  console.log('JS cargado');

  // Carga inicial y evento de pestañas
  cargarContratistas();
  document.getElementById('dashboardTabs')?.addEventListener('shown.bs.tab', (e) => {
    const target = e.target.getAttribute('data-bs-target');
    switch (target) {
      case '#pane-contratistas': cargarContratistas(); break;
      case '#pane-mis': cargarMisSolicitudes(); break;
      case '#pane-presupuestos': cargarPresupuestos(); break;
    }
  });

  // Toggle para mostrar campo Contratista en "Nueva Solicitud"
  const tipoSelect = document.getElementById('tipoSolicitud');
  const divContr = document.getElementById('div-contratista');
  tipoSelect?.addEventListener('change', () => {
    divContr.style.display = tipoSelect.value === 'servicio' ? 'block' : 'none';
  });

  // Logout
  document.getElementById('btnCerrarSesion')?.addEventListener('click', () => {
    sessionStorage.clear();
    window.location.href = '../logout.php';
  });

  // Filtrar contratistas
  document.getElementById('btnFiltrar')?.addEventListener('click', () => {
    const sel = document.getElementById('especialidadSelect').value.trim().toLowerCase();
    const txt = document.getElementById('especialidadTexto').value.trim().toLowerCase();
    filtrarContratistas(txt || sel);
  });

  // Envío de presupuesto (tab "Nueva Solicitud")
  document.getElementById('formPresupuesto')?.addEventListener('submit', (e) => {
    e.preventDefault();
    enviarPresupuesto();
  });

  // Envío de solicitud directa de servicio
  document.getElementById('formSolicitudDirecta')?.addEventListener('submit', (e) => {
    e.preventDefault();
    enviarSolicitudDirecta();
  });
});

/* ==================== Contratistas ==================== */
function cargarContratistas() {
  fetch('../../servidor/api/SolicitudesAPI.php?action=listarContratistas', { credentials: 'include' })
    .then(res => res.json())
    .then(mostrarContratistas)
    .catch(err => console.error('Error al cargar contratistas:', err));
}

function filtrarContratistas(especialidad) {
  fetch(`../../servidor/api/SolicitudesAPI.php?action=filtrarContratistas&especialidad=${encodeURIComponent(especialidad)}`, { credentials: 'include' })
    .then(res => res.json())
    .then(mostrarContratistas)
    .catch(err => console.error('Error al filtrar contratistas:', err));
}

function mostrarContratistas(lista) {
  const tbody = document.querySelector('#tablaContratistas tbody');
  if (!tbody) return;
  tbody.innerHTML = '';
  lista.forEach(c => {
    const nombreEsc = c.Nombre.replace(/'/g, "\\'");
    const espEsc = c.Especialidad.replace(/'/g, "\\'");
    tbody.insertAdjacentHTML('beforeend', `
      <tr>
        <td>${c.Nombre}</td>
        <td>${c.Especialidad}</td>
        <td>
          <button class="btn btn-sm btn-primary"
                  onclick="abrirModalSolicitud(${c.Id_contratista}, '${nombreEsc}', '${espEsc}')">
            Solicitar Servicio
          </button>
        </td>
      </tr>
    `);
  });
}

function abrirModalSolicitud(id, nombre, especialidad) {
  document.getElementById('solIdContratista').value = id;
  document.getElementById('solNombreContratista').textContent = nombre;
  document.getElementById('solEspecialidadContratista').textContent = especialidad;
  modalSolicitud.show();
}

/* ==================== Nueva Solicitud (Presupuesto) ==================== */
function enviarPresupuesto() {
  const data = {
    tipo_solicitud: 'presupuesto',
    categoria: document.getElementById('categoria').value,
    subcategoria: document.getElementById('subcategoria').value,
    titulo: document.getElementById('titulo').value.trim(),
    descripcion: document.getElementById('descripcion').value.trim(),
    ubicacion: document.getElementById('ubicacion').value.trim()
  };
  fetch('../../servidor/api/SolicitudesAPI.php?action=presupuesto', {
    method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data)
  })
    .then(r => r.json())
    .then(res => {
      alert(res.message);
      if (res.status === 'success') document.getElementById('formPresupuesto').reset();
    })
    .catch(() => alert('Error al solicitar presupuesto'));
}

/* ==================== Solicitud Directa (Servicio) ==================== */
function enviarSolicitudDirecta() {
  const payload = {
    tipo_solicitud: document.getElementById('solTipo').value,
    titulo: document.getElementById('solTitulo').value.trim(),
    descripcion: document.getElementById('solDescripcion').value.trim(),
    ubicacion: document.getElementById('solUbicacion').value.trim(),
    id_contratista: document.getElementById('solIdContratista').value
  };
  fetch('../../servidor/api/SolicitudesAPI.php?action=presupuesto', {
    method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
  })
    .then(r => r.json())
    .then(res => {
      alert(res.message);
      if (res.status === 'success') {
        document.getElementById('formSolicitudDirecta').reset();
        modalSolicitud.hide();
        cargarMisSolicitudes();
      }
    })
    .catch(err => {
      console.error('Error al solicitar servicio:', err);
      alert('Error al solicitar servicio');
    });
}

/* ==================== Mis Solicitudes ==================== */
function cargarMisSolicitudes() {
  fetch('../../servidor/api/SolicitudesAPI.php?action=listarMisSolicitudes', { credentials: 'include' })
    .then(res => res.json())
    .then(mostrarSolicitudes)
    .catch(err => console.error('Error al cargar mis solicitudes:', err));
}

function mostrarSolicitudes(lista) {
  const tbody = document.querySelector('#tablaSolicitudes tbody');
  if (!tbody) return;
  tbody.innerHTML = '';
  lista.forEach((s, i) => {
    tbody.insertAdjacentHTML('beforeend', `
      <tr>
        <td>${i+1}</td>
        <td>${s.Tipo_solicitud}</td>
        <td>${s.Categoria}</td>
        <td>${s.Titulo}</td>
        <td>${s.Estado}</td>
        <td>${s.Precio_estimado ?? '-'}</td>
        <td>${s.Nombre_contratista ?? '-'}</td>
        <td>${new Date(s.Fecha_solicitud).toLocaleString()}</td>
        <td><button class="btn btn-sm btn-danger" onclick="eliminarSolicitud(${s.Id_solicitud})">Cancelar</button></td>
        <td><button class="btn btn-sm btn-warning" onclick="abrirModalCalificacion(${s.Id_solicitud})">Calificar</button></td>
      </tr>
    `);
  });
}

function eliminarSolicitud(id) {
  if (!confirm('¿Deseas cancelar esta solicitud?')) return;
  fetch('../../servidor/api/SolicitudesAPI.php?action=eliminarSolicitud', {
    method: 'DELETE', credentials: 'include', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: `id_solicitud=${id}`
  })
    .then(r => r.json())
    .then(res => {
      alert(res.message);
      if (res.status === 'success') cargarMisSolicitudes();
    })
    .catch(err => console.error('Error al cancelar:', err));
}

/* ==================== Presupuestos ==================== */
function cargarPresupuestos() {
  fetch('../../servidor/api/PresupuestosAPI.php?action=obtenerPresupuestos', { credentials: 'include' })
    .then(r => r.json())
    .then(renderizarPresupuestos)
    .catch(err => console.error('Error al cargar presupuestos:', err));
}

function renderizarPresupuestos(presus) {
  const tbody = document.querySelector('#tablaPresupuestos tbody');
  if (!tbody) return;
  tbody.innerHTML = '';
  presus.forEach(p => {
    tbody.insertAdjacentHTML('beforeend', `
      <tr>
        <td>${p.id_presupuesto}</td>
        <td>${p.descripcion}</td>
        <td>${p.monto}</td>
        <td>${p.estado}</td>
        <td>
          ${p.estado === 'pendiente'
            ? `<button class="btn btn-sm btn-success" onclick="aceptarPresupuesto(${p.id_presupuesto})">Aceptar</button>
               <button class="btn btn-sm btn-danger" onclick="rechazarPresupuesto(${p.id_presupuesto})">Rechazar</button>`
            : ''}
        </td>
      </tr>
    `);
  });
}

function aceptarPresupuesto(id) {
  if (!confirm('¿Deseas aceptar este presupuesto?')) return;
  fetch('../../servidor/api/PresupuestosAPI.php?action=aceptarPresupuesto', {
    method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: `id_presupuesto=${id}`
  })
    .then(r => r.json())
    .then(res => { alert(res.message); if (res.status === 'success') cargarPresupuestos(); })
    .catch(err => console.error('Error al aceptar presupuesto:', err));
}

function rechazarPresupuesto(id) {
  if (!confirm('¿Deseas rechazar este presupuesto?')) return;
  fetch('../../servidor/api/PresupuestosAPI.php?action=rechazarPresupuesto', {
    method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: `id_presupuesto=${id}`
  })
    .then(r => r.json())
    .then(res => { alert(res.message); if (res.status === 'success') cargarPresupuestos(); })
    .catch(err => console.error('Error al rechazar presupuesto:', err));
}

/* ==================== Fin de archivo ==================== */
