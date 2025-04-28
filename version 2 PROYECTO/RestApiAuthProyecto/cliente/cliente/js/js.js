// 1) Creamos UNA sola instancia global
const modalSolicitud = new bootstrap.Modal(
    document.getElementById('modalSolicitudDirecta'),
    { backdrop: 'static', keyboard: false }
  );
  
  document.addEventListener("DOMContentLoaded", () => {
    console.log("JS cargado");
  
  
    // Carga inicial de contratistas
    cargarContratistas();
  
   
    // Toggle de contratista en nueva solicitud
    const tipoSelect = document.getElementById("tipoSolicitud");
    const divContr  = document.getElementById("div-contratista");
    tipoSelect?.addEventListener("change", () => {
      divContr.style.display = tipoSelect.value === "servicio" ? "block" : "none";
    });
  
    // Logica de pestañas
    document.getElementById("dashboardTabs")?.addEventListener("shown.bs.tab", e => {
      const tgt = e.target.getAttribute("data-bs-target");
      if (tgt === "#pane-contratistas") cargarContratistas();
      if (tgt === "#pane-mis")          cargarMisSolicitudes();
      if (tgt === "#pane-presupuestos") cargarPresupuestos();
    });
  
    // Logout
    document.getElementById("btnCerrarSesion")?.addEventListener("click", () => {
      sessionStorage.clear();
      window.location.href = "../logout.php";
    });
  
    // Filtrar contratistas
    document.getElementById("btnFiltrar")?.addEventListener("click", () => {
      const sel = document.getElementById("especialidadSelect").value.toLowerCase().trim();
      const txt = document.getElementById("especialidadTexto").value.toLowerCase().trim();
      filtrarContratistas(txt || sel);
    });
  
    // Envío de nuevo presupuesto
    document.getElementById("formPresupuesto")?.addEventListener("submit", e => {
      e.preventDefault();
      enviarPresupuesto();
    });
  
    // Envío de solicitud directa (servicio)
    document.getElementById("formSolicitudDirecta")?.addEventListener("submit", e => {
      e.preventDefault();
      enviarSolicitudDirecta();
    });
  });
  /** Carga y render de contratistas */
  function cargarContratistas() {
    fetch('../../servidor/api/SolicitudesAPI.php?action=listarContratistas', {
      credentials: 'include'
    })
      .then(res => res.json())
      .then(mostrarContratistas)
      .catch(err => console.error("Error al cargar contratistas:", err));
  }
  function filtrarContratistas(esp) {
    fetch(`../../servidor/api/SolicitudesAPI.php?action=filtrarContratistas&especialidad=${encodeURIComponent(esp)}`, {
      credentials: 'include'
    })
      .then(res => res.json())
      .then(mostrarContratistas)
      .catch(err => console.error("Error al filtrar contratistas:", err));
  }
  
  function mostrarContratistas(lista) {
    const tbody = document.querySelector("#tablaContratistas tbody");
    if (!tbody) return console.error("No existe #tablaContratistas tbody");
    tbody.innerHTML = "";
  
    lista.forEach(c => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${c.Nombre}</td>
        <td>${c.Especialidad}</td>
        <td>
          <button class="btn btn-sm btn-primary"
                  onclick="abrirModalSolicitud(${c.Id_contratista}, '${c.Nombre.replace(/'/g, "\\'")}', '${c.Especialidad.replace(/'/g, "\\'")}') ">
            Solicitar Servicio
          </button>
        </td>`;
      tbody.appendChild(tr);
    });
  }
  
  /** Abre el modal de "Solicitud Directa" */
  function abrirModalSolicitud(id, nombre, especialidad) {
    document.getElementById("solIdContratista").value    = id;
    document.getElementById("solNombreContratista").textContent = nombre;
    document.getElementById("solEspecialidadContratista").textContent = especialidad;
    modalSolicitud.show();
  }
  
  /** Enviar presupuesto */
  function enviarPresupuesto() {
    const data = {
      tipo_solicitud: "presupuesto",
      categoria:      document.getElementById("categoria").value,
      subcategoria:   document.getElementById("subcategoria").value,
      titulo:         document.getElementById("titulo").value.trim(),
      descripcion:    document.getElementById("descripcion").value.trim(),
      ubicacion:      document.getElementById("ubicacion").value.trim()
    };
    fetch('../../servidor/api/SolicitudesAPI.php?action=presupuesto', {
      method: 'POST',
      credentials: 'include',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
      alert(res.message);
      if (res.status === 'success') document.getElementById("formPresupuesto").reset();
    })
    .catch(() => alert("Error al solicitar presupuesto"));
  }
  
  /** Enviar solicitud directa de servicio */
  function enviarSolicitudDirecta() {
    const payload = {
      tipo_solicitud: document.getElementById("solTipo").value,
      titulo:         document.getElementById("solTitulo").value.trim(),
      descripcion:    document.getElementById("solDescripcion").value.trim(),
      ubicacion:      document.getElementById("solUbicacion").value.trim(),
      id_contratista: document.getElementById("solIdContratista").value
    };
  
    fetch('../../servidor/api/SolicitudesAPI.php?action=presupuesto', {
      method: 'POST',
      credentials: 'include',               // para enviar cookies / sesión
      headers:    {'Content-Type':'application/json'},
      body:       JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(res => {
      alert(res.message);
      if (res.status === 'success') {
        // reinicia el formulario y cierra el modal
        document.getElementById("formSolicitudDirecta").reset();
        modalSolicitud.hide();
        // tal vez recargar “Mis Solicitudes” de paso:
        cargarMisSolicitudes();
      }
    })
    .catch(err => {
      console.error("Error al solicitar servicio:", err);
      alert("Error al solicitar servicio");
    });
  }
  
  
  
  
  // Resto de funciones: cargarContratistas, filtrarContratistas, cargarMisSolicitudes, mostrarSolicitudes, eliminarSolicitud, cargarPresupuestos, renderizarPresupuestos, aceptarPresupuesto, rechazarPresupuesto (idénticas a tu versión previa, solo guardadas y con getElementById?. )
  /** LISTAR CONTRATISTAS */
  function cargarContratistas() {
    fetch('../../servidor/api/SolicitudesAPI.php?action=listarContratistas',{
      credentials: 'include'
    })
       
      .then(res => res.json())
      .then(data => mostrarContratistas(data))
      .catch(err => console.error("Error al cargar contratistas:", err));
  }
  
  /** FILTRAR CONTRATISTAS */
  function filtrarContratistas(esp) {
    fetch(`../../servidor/api/SolicitudesAPI.php?action=filtrarContratistas&especialidad=${encodeURIComponent(esp)}`, {
      credentials: 'include'
    })
      .then(res => res.json())
      .then(data => mostrarContratistas(data))
      .catch(err => console.error("Error al filtrar contratistas:", err));
  }
  
  /** RENDER TABLA DE CONTRATISTAS */
  function mostrarContratistas(lista) {
    const tbody = document.querySelector("#tablaContratistas tbody");
    tbody.innerHTML = "";
    lista.forEach(c => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${c.Nombre}</td>
        <td>${c.Especialidad}</td>
        <td>
          <button class="btn btn-sm btn-primary"
                  onclick="abrirModalSolicitud(${c.Id_contratista}, '${c.Nombre.replace(/'/g,"\\'")}')">
            Solicitar Servicio
          </button>
        </td>`;
      tbody.appendChild(tr);
    });
  }
  
  
  /** SELECCIONAR CONTRATISTA (guarda en un <input hidden> para solicitudes de servicio) */
  function seleccionarContratista(id, nombre) {
    const span = document.getElementById("nombreSeleccionado");
    const input = document.getElementById("idContratista");
    if (span && input) {
      span.textContent = nombre;
      input.value = id;
    }
  }
  
  /** ENVÍA LA SOLICITUD (tipo: presupuesto o servicio) */
  function enviarSolicitud() {
    const tipo = document.getElementById("tipoSolicitud").value;  
    const categoria   = document.getElementById("categoria").value;
    const subcategoria= document.getElementById("subcategoria").value;
    const titulo      = document.getElementById("titulo").value.trim();
    const descripcion = document.getElementById("descripcion").value.trim();
    const ubicacion   = document.getElementById("ubicacion").value.trim();
  
    // Si es servicio, asegúrate que haya contratista seleccionado:
    let idCont = null;
    if (tipo === 'servicio') {
      idCont = document.getElementById("idContratista").value;
      if (!idCont) {
        return alert("Para solicitar un servicio debes seleccionar un contratista primero.");
      }
    }
  
    // Validación mínima
    if (!categoria || !subcategoria || !titulo || !descripcion || !ubicacion) {
      return alert("Por favor completa todos los campos de la solicitud.");
    }
  
    // Construye el body
    const data = {
      tipo_solicitud: tipo,
      categoria,
      subcategoria,
      titulo,
      descripcion,
      ubicacion
    };
    if (tipo === 'servicio') {
      data.id_contratista = idCont;  // añadimos el contratista
    }
  
    fetch('../../servidor/api/SolicitudesAPI.php?action=presupuesto', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
      alert(res.message);
      if (res.status === 'success') {
        document.getElementById("formPresupuesto").reset();
        // oculta de nuevo el bloque de contratista
        document.getElementById('div-contratista').style.display = 'none';
        // refresca tus listados si hace falta...
      }
    })
    .catch(err => {
      console.error("Error al enviar la solicitud:", err);
      alert("Ocurrió un error al procesar la solicitud.");
    });
  }
  /** Nuevas funciones para "Mis Solicitudes" */
  function cargarMisSolicitudes() {
    fetch('../../servidor/api/SolicitudesAPI.php?action=listarMisSolicitudes', {
      credentials: 'include'
    })
      .then(res => res.json())
      .then(data => mostrarSolicitudes(data))
      .catch(err => console.error("Error al cargar mis solicitudes:", err));
  }
  
  function mostrarSolicitudes(lista) {
    const tbody = document.querySelector("#tablaSolicitudes tbody");
    if (!tbody) return;
  
    tbody.innerHTML = "";  // limpia
  
    lista.forEach((s, i) => {
      const fila = document.createElement("tr");
      fila.innerHTML = `
        <td>${i+1}</td>
        <td>${s.Tipo_solicitud}</td>
        <td>${s.Categoria}</td>
        <td>${s.Titulo}</td>
        <td>${s.Estado}</td>
        <td>${s.Precio_estimado ?? '-'}</td>
        <td>${s.Nombre_contratista ?? '-'}</td>
        <td>${new Date(s.Fecha_solicitud).toLocaleString()}</td>
         <td>
          <button class="btn btn-sm btn-danger"
                  onclick="eliminarSolicitud(${s.Id_solicitud})">
            Cancelar
          </button>
        </td>
        <td>
          <!-- Solo si el estado es 'completado' y aún no tiene review -->
          <button class="btn btn-sm btn-warning"
                  onclick="abrirModalCalificacion(${s.Id_solicitud})">
          Calificar
        </button>
        </td>
  
      `;
      tbody.appendChild(fila);
    });
  }
  
  
  function eliminarSolicitud(id) {
    if (!confirm("¿Deseas cancelar esta solicitud?")) return;
  
    fetch('/RestApiAuthProyecto/servidor/api/SolicitudesAPI.php?action=eliminarSolicitud', {
      method: 'DELETE',
      credentials: 'include',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id_solicitud=${id}`
    })
    .then(response => response.json())
    .then(res => {
      if (res.status === 'success') {
        alert(res.message);
        cargarMisSolicitudes(); // Actualiza la tabla
      } else {
        alert("No se pudo cancelar la solicitud.");
      }
    })
    .catch(err => {
      console.error("Error al cancelar la solicitud:", err);
      alert("Ocurrió un error al cancelar la solicitud.");
    });
  }
  
  function renderizarPresupuestos(presupuestos) {
    const tbody = document.querySelector('#tablaPresupuestos tbody');
    tbody.innerHTML = '';
  
    presupuestos.forEach(p => {
      const fila = document.createElement('tr');
  
      fila.innerHTML = `
        <td>${p.id_presupuesto}</td>
        <td>${p.descripcion}</td>
        <td>${p.monto}</td>
        <td>${p.estado}</td>
        <td>
          ${p.estado === 'pendiente' ? `
            <button class="btn btn-sm btn-success" onclick="aceptarPresupuesto(${p.id_presupuesto})">Aceptar</button>
            <button class="btn btn-sm btn-danger" onclick="rechazarPresupuesto(${p.id_presupuesto})">Rechazar</button>
          ` : ''}
        </td>
      `;
  
      tbody.appendChild(fila);
    });
  }
  function aceptarPresupuesto(id) {
    if (!confirm("¿Deseas aceptar este presupuesto?")) return;
  
    fetch('/RestApiAuthProyecto/servidor/api/PresupuestosAPI.php?action=aceptarPresupuesto', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id_presupuesto=${id}`
    })
    .then(response => response.json())
    .then(res => {
      alert(res.message);
      if (res.status === 'success') {
        cargarPresupuestos(); // Función que recarga la lista de presupuestos
      }
    })
    .catch(err => {
      console.error("Error al aceptar el presupuesto:", err);
      alert("Ocurrió un error al aceptar el presupuesto.");
    });
  }
  
  function rechazarPresupuesto(id) {
    if (!confirm("¿Deseas rechazar este presupuesto?")) return;
  
    fetch('/RestApiAuthProyecto/servidor/api/PresupuestosAPI.php?action=rechazarPresupuesto', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id_presupuesto=${id}`
    })
    .then(response => response.json())
    .then(res => {
      alert(res.message);
      if (res.status === 'success') {
        cargarPresupuestos(); // Función que recarga la lista de presupuestos
      }
    })
    .catch(err => {
      console.error("Error al rechazar el presupuesto:", err);
      alert("Ocurrió un error al rechazar el presupuesto.");
    });
  }
  
  function cargarPresupuestos() {
    fetch('/RestApiAuthProyecto/servidor/api/PresupuestosAPI.php?action=obtenerPresupuestos', {
      method: 'GET',
      credentials: 'include'
    })
    .then(response => response.text())
    .then(text => {
      console.log("Respuesta del servidor:", text);
      try {
        const presupuestos = JSON.parse(text);
        renderizarPresupuestos(presupuestos);
      } catch (e) {
        console.error("Error al analizar JSON:", e);
      }
    })
    .catch(err => {
      console.error("Error al cargar los presupuestos:", err);
      alert("Ocurrió un error al cargar los presupuestos.");
    });
  }
  function abrirModalSolicitud(id, nombre) {
    document.getElementById("solIdContratista").value = id;
    document.getElementById("solNombreContratista").textContent = nombre;
    new bootstrap.Modal(document.getElementById("modalSolicitudDirecta")).show();
  }
  
  document.getElementById("formSolicitudDirecta").addEventListener("submit", function (e) {
    e.preventDefault();
  
    const solicitud = {
      tipo: document.getElementById("solTipo").value,
      titulo: document.getElementById("solTitulo").value,
      descripcion: document.getElementById("solDescripcion").value,
      ubicacion: document.getElementById("solUbicacion").value,
      id_contratista: document.getElementById("solIdContratista").value,
    };
  
    fetch("../../servidor/api/SolicitudesAPI.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(solicitud),
    })
      .then((res) => res.json())
      .then((data) => {
        alert("Solicitud enviada con éxito");
        bootstrap.Modal.getInstance(document.getElementById("modalSolicitudDirecta")).hide();
        // Opcional: recargar solicitudes
      })
      .catch((err) => {
        console.error(err);
        alert("Error al enviar la solicitud");
      });
  });
  
  
  