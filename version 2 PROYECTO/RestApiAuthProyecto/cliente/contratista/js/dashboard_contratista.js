document.addEventListener('DOMContentLoaded', () => {
  // cerrar sesión
  document.getElementById('btnCerrarSesion')?.addEventListener('click', () => {
    sessionStorage.clear();
    window.location.href = '../logout.php';
  });

  // inicializar pestañas
  const tabs = document.querySelectorAll('#tabsContratista button[data-bs-toggle="tab"]');
  tabs.forEach(tab => {
    tab.addEventListener('shown.bs.tab', (e) => {
      const tgt = e.target.getAttribute('data-bs-target');
      if (tgt === '#pane-pendientes') {
        listarSolicitudesPendientes();
      } else if (tgt === '#pane-enproceso') {
        listarSolicitudesEnProceso();
      } else if (tgt === '#pane-completados') {
        listarSolicitudesCompletadas();
      }
    });
  });

  // Cargar al inicio la pestaña activa
  listarSolicitudesPendientes();
});

// 1) Pendientes
function listarSolicitudesPendientes() {
  fetch('../../servidor/api/SolicitudesAPI.php?action=listarPendientes', { credentials:'include' })
    .then(r => r.json())
    .then(lista => {
      const cont = document.getElementById('solicitudes-pendientes');
      cont.innerHTML = '';
      lista.forEach(s => cont.appendChild(crearCardPendiente(s)));
    })
    .catch(console.error);
}

function crearCardPendiente(s) {
  const col = document.createElement('div');
  col.className = 'col-12 col-md-6 mb-3';
  col.innerHTML = `
    <div class="card h-100">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title">${s.Titulo}</h5>
        <p class="card-text">${s.Descripcion}</p>
        <input type="number" id="precio_${s.Id_solicitud}" class="form-control mb-2" placeholder="Precio estimado">
        <textarea id="mensaje_${s.Id_solicitud}" class="form-control mb-3" placeholder="Mensaje opcional"></textarea>
        <button class="btn btn-primary mt-auto" onclick="responderSolicitud(${s.Id_solicitud})">
          Aceptar Solicitud
        </button>
      </div>
    </div>`;
  return col;
}

function responderSolicitud(id) {
  const precio = document.getElementById(`precio_${id}`).value;
  const mensaje = document.getElementById(`mensaje_${id}`).value;
  fetch('../../servidor/api/SolicitudesAPI.php?action=aceptarSolicitud', {
    method: 'POST',
    credentials: 'include',
    headers:{'Content-Type':'application/json'},
    body: JSON.stringify({ id_solicitud: id })
  })
  .then(r => r.json())
  .then(res => {
    alert(res.message);
    listarSolicitudesPendientes(); // recarga
  })
  .catch(console.error);
}

// 2) En proceso
function listarSolicitudesEnProceso() {
  fetch('../../servidor/api/SolicitudesAPI.php?action=listarEnProceso', { credentials:'include' })
    .then(r => r.json())
    .then(lista => {
      const cont = document.getElementById('solicitudes-enproceso');
      cont.innerHTML = '';
      lista.forEach(s => {
        const col = document.createElement('div');
        col.className = 'col-12 mb-2';
        col.innerHTML = `
          <div class="alert alert-info">
            <strong>${s.Titulo}</strong> — <em>${s.Estado}</em>
            <button class="btn btn-sm btn-success float-end"
                    onclick="marcarCompletado(${s.Id_solicitud})">
              Marcar Completado
            </button>
          </div>`;
        cont.appendChild(col);
      });
    })
    .catch(console.error);
}

function marcarCompletado(id) {
  fetch('../../servidor/api/SolicitudesAPI.php?action=marcarCompletado', {
    method: 'POST',
    credentials:'include',
    headers:{'Content-Type':'application/json'},
    body: JSON.stringify({ id_solicitud: id })
  })
  .then(r=>r.json())
  .then(res=>{
    alert(res.message);
    listarSolicitudesEnProceso();
  })
  .catch(console.error);
}

// 3) Completados
function listarSolicitudesCompletadas() {
  fetch('../../servidor/api/SolicitudesAPI.php?action=listarCompletados', { credentials:'include' })
    .then(r => r.json())
    .then(lista => {
      const cont = document.getElementById('solicitudes-completadas');
      cont.innerHTML = '';
      lista.forEach(s => {
        const div = document.createElement('div');
        div.className = 'alert alert-success';
        div.textContent = `${s.Titulo} — completado el ${new Date(s.Fecha_solicitud).toLocaleDateString()}`;
        cont.appendChild(div);
      });
    })
    .catch(console.error);
}

