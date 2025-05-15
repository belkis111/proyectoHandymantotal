// Modal para solicitud directa
let modalSolicitud;

document.addEventListener('DOMContentLoaded', () => {
  modalSolicitud = new bootstrap.Modal(document.getElementById('modalSolicitudDirecta'), { backdrop: 'static', keyboard: false });

  // Inicializar pestañas
  document.querySelectorAll('#dashboardTabs button[data-bs-toggle="tab"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', onTabChange);
  });

  // Cargar inicialmente la pestaña activa
  const activeTab = document.querySelector('#dashboardTabs .active');
  if (activeTab) onTabChange({ target: activeTab });

  // Toggle campo Contratista en formulario
  const tipoSelect = document.getElementById('tipoSolicitud');
  const divContr = document.getElementById('div-contratista');
  tipoSelect?.addEventListener('change', () => {
    divContr.style.display = tipoSelect.value === 'servicio' ? 'block' : 'none';
  });

  // Eventos de botones y formularios
  document.getElementById('btnCerrarSesion')?.addEventListener('click', logout);
  document.getElementById('btnFiltrar')?.addEventListener('click', () => filtrarContratistas(
    document.getElementById('especialidadTexto').value.trim().toLowerCase() ||
    document.getElementById('especialidadSelect').value.trim().toLowerCase()
  ));
  document.getElementById('formPresupuesto')?.addEventListener('submit', e => { e.preventDefault(); enviarPresupuesto(); });
  document.getElementById('formSolicitudDirecta')?.addEventListener('submit', e => { e.preventDefault(); enviarSolicitudDirecta(); });
});

function onTabChange(e) {
  const target = e.target.getAttribute('data-bs-target');
  if (target === '#pane-contratistas') cargarContratistas();
  else if (target === '#pane-mis') cargarMisSolicitudes();
  else if (target === '#pane-ofertas-recibidas') cargarOfertasRecibidas();
}

function logout() {
  sessionStorage.clear();
  window.location.href = '../logout.php';
}

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
  const tbody = document.querySelector('#tablaContratistas tbody'); if (!tbody) return;
  tbody.innerHTML = '';
  lista.forEach(c => {
    tbody.insertAdjacentHTML('beforeend', `
      <tr>
        <td>${c.Nombre}</td>
        <td>${c.Especialidad}</td>
        <td>
          <button class="btn btn-sm btn-primary" onclick="abrirModalSolicitud(${c.Id_contratista}, '${c.Nombre.replace(/'/g,"\\'")}', '${c.Especialidad.replace(/'/g,"\\'")}')">Solicitar Servicio</button>
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

/* ==================== Solicitudes (Cliente) ==================== */
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
    let acciones = `<button class="btn btn-sm btn-danger" onclick="eliminarSolicitud(${s.Id_solicitud})">Cancelar</button>`;

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
        <td>${acciones}</td>
      </tr>
    `);
  });
}


function eliminarSolicitud(id) {
  if (!confirm('¿Deseas cancelar esta solicitud?')) return;
  fetch('../../servidor/api/SolicitudesAPI.php?action=eliminarSolicitud', { method:'DELETE', credentials:'include', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:`id_solicitud=${id}` })
    .then(res=>res.json())
    .then(res=>{ alert(res.message); if(res.status==='success') cargarMisSolicitudes(); })
    .catch(err=>console.error('Error al cancelar:',err));
}

/* ==================== Ofertas Recibidas ==================== */
function cargarOfertasRecibidas() {
  fetch('../../servidor/api/OfertasAPI.php?action=ofertasPorCliente', { credentials:'include' })
    .then(res => res.json())
    .then(renderizarOfertasRecibidas)
    .catch(err => {
      console.error('Error al cargar ofertas:', err);
      document.getElementById('ofertas-recibidas-body').innerHTML =
        '<tr><td colspan="8" class="text-danger">Error al cargar ofertas.</td></tr>';
    })
  }

function renderizarOfertasRecibidas(ofertas) {
  const tbody = document.getElementById('ofertas-recibidas-body');
  if (!tbody) return;
  tbody.innerHTML = '';
  ofertas.forEach((o,i) => {
    tbody.insertAdjacentHTML('beforeend', `
      <tr>
        <td>${i+1}</td>
        <td>${o.Tipo_solicitud}</td>
        <td>${o.Titulo}</td>
        <td>${o.Nombre_contratista}</td>
        <td>$${o.Precio_ofertado}</td>
        <td>${o.Mensaje || '-'}</td>
        <td>${new Date(o.Fecha_oferta).toLocaleString()}</td>
        <td>
          ${o.Estado_oferta === 'pendiente'
            ? `<button class="btn btn-sm btn-success" onclick="aceptarOferta(${o.Id_oferta})">Aceptar</button>
               <button class="btn btn-sm btn-danger" onclick="rechazarOferta(${o.Id_oferta})">Rechazar</button>`
            : `<span class="badge bg-secondary">${o.Estado_oferta}</span>`
          }
        </td>
      </tr>
    `);
  });
}

function aceptarOferta(id) {
  if (!confirm('¿Aceptar esta oferta?')) return;
  fetch('../../servidor/api/OfertasAPI.php?action=aceptarOferta', {
    method: 'POST', credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ Id_oferta: id })
  })
  .then(r => r.json())
  .then(res => {
    alert(res.message);
    cargarOfertasRecibidas();
    cargarMisSolicitudes();
  })
  .catch(err => console.error('Error al aceptar oferta:', err));
}

function rechazarOferta(id) {
  if (!confirm('¿Rechazar esta oferta?')) return;
  fetch('../../servidor/api/OfertasAPI.php?action=rechazarOferta', {
    method: 'POST', credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ Id_oferta: id })
  })
  .then(r => r.json())
  .then(res => {
    alert(res.message);
    cargarOfertasRecibidas();
  })
  .catch(err => console.error('Error al rechazar oferta:', err));
}


/* ==================== Crear / Responder ==================== */
function enviarPresupuesto() {
  const data = {
    tipo_solicitud: 'presupuesto',
    categoria: document.getElementById('categoria').value,
    subcategoria: document.getElementById('subcategoria').value,
    titulo: document.getElementById('titulo').value.trim(),
    descripcion: document.getElementById('descripcion').value.trim(),
    ubicacion: document.getElementById('ubicacion').value.trim()
  };
  fetch('../../servidor/api/SolicitudesAPI.php?action=presupuesto', { method:'POST', credentials:'include', headers:{'Content-Type':'application/json'}, body:JSON.stringify(data) })
    .then(res=>res.json()).then(r=>{alert(r.message); if(r.status==='success') document.getElementById('formPresupuesto').reset();}).catch(()=>alert('Error al solicitar presupuesto'));
}

function enviarSolicitudDirecta() {
  const payload = {
    tipo_solicitud: document.getElementById('solTipo').value,
    titulo: document.getElementById('solTitulo').value.trim(),
    descripcion: document.getElementById('solDescripcion').value.trim(),
    ubicacion: document.getElementById('solUbicacion').value.trim(),
    id_contratista: document.getElementById('solIdContratista').value
  };
  fetch('../../servidor/api/SolicitudesAPI.php?action=presupuesto', { method:'POST', credentials:'include', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload) })
    .then(res=>res.json()).then(r=>{alert(r.message); if(r.status==='success'){document.getElementById('formSolicitudDirecta').reset(); modalSolicitud.hide(); cargarMisSolicitudes();}})
    .catch(err=>{console.error('Error al solicitar servicio:',err); alert('Error al solicitar servicio');});
}

/* ==================== Ofertas de una Solicitud ==================== */
function cargarOfertasParaSolicitud(idSol) {
  // Puedes reutilizar endpoint de ofertasRecibidas y filtrar en cliente o crear uno nuevo
  cargarOfertasRecibidas();
}

/* ==================== Acción de Ofertas ==================== */

// 1) Aceptar oferta
function aceptarOferta(id) {
  if (!confirm('¿Aceptar esta oferta?')) return;
  fetch('../../servidor/api/OfertasAPI.php?action=aceptarOferta', {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ Id_oferta: id })
  })
  .then(r => r.json())
  .then(res => {
    alert(res.message);
    cargarOfertasRecibidas();
    cargarMisSolicitudes();
  })
  .catch(err => console.error('Error al aceptar oferta:', err));
}

// 2) Rechazar oferta
function rechazarOferta(id) {
  if (!confirm('¿Rechazar esta oferta?')) return;
  fetch('../../servidor/api/OfertasAPI.php?action=rechazarOferta', {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ Id_oferta: id })
  })
  .then(r => r.json())
  .then(res => {
    alert(res.message);
    cargarOfertasRecibidas();
  })
  .catch(err => console.error('Error al rechazar oferta:', err));
}



