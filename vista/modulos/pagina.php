 <style>
    :root{
      --sidebar-width: 280px;
      --accent: #6f66ff;        /* moradito */
      --accent-soft: #eef0ff;   /* fondo seleccionado */
      --text: #5d6678;
    }

    body { background:#f7f8fc; }

    .sidebar {
      width: var(--sidebar-width);
      min-height: 100vh;
      background: #fff;
      border-right: 1px solid #eef0f4;
      position: sticky;
      top: 0;
    }

    .brand {
      font-weight: 700;
      font-size: 1.6rem;
      letter-spacing: .3px;
    }

    .nav-title {
      font-size: .75rem;
      letter-spacing: .08em;
      color: #9aa3b2;
      margin: 1.25rem 0 .5rem;
    }

    .side-link {
      color: var(--text);
      border-radius: .75rem;
      padding: .8rem .9rem;
      display: flex;
      align-items: center;
      gap: .75rem;
      text-decoration: none;
    }

    .side-link:hover {
      background: #f3f5ff;
      color: #394152;
    }

    .side-link.active {
      background: var(--accent-soft);
      color: var(--accent);
      font-weight: 600;
    }

    .side-icon {
      width: 24px;
      display: inline-flex;
      justify-content: center;
      font-size: 1.1rem;
    }

    .submenu .side-link {
      padding-left: 2.4rem;
      font-size: .95rem;
    }

    /* pequeño “pill” a la derecha (opcional) */
    .badge-pill {
      margin-left: auto;
      font-size: .75rem;
      background: #eef0ff;
      color: var(--accent);
      border: 1px solid #dfe2ff;
    }
  </style>
</head>

<body>
  <div class="d-flex">
    <!-- SIDEBAR -->
    <aside class="sidebar p-3">
      <!-- Logo / Marca -->
      <div class="d-flex align-items-center gap-2 mb-3">
        <div class="rounded-3 d-inline-flex align-items-center justify-content-center"
             style="width:40px;height:40px;background:#eef0ff;color:var(--accent);">
          <i class="bi bi-lightning-charge-fill"></i>
        </div>
        <div class="brand text-dark">sgd</div>
      </div>

      <!-- Menú principal -->
      <nav class="d-grid gap-2">
        <a class="side-link" href="#">
          <span class="side-icon"><i class="bi bi-house"></i></span>
          <span>Inicio</span>
        </a>

        <a class="side-link" href="#">
          <span class="side-icon"><i class="bi bi-person"></i></span>
          <span>Perfil</span>
          <i class="bi bi-chevron-right ms-auto"></i>
        </a>

        <div class="nav-title">PANEL APRENDIZ</div>

        <a class="side-link active" href="#">
          <span class="side-icon"><i class="bi bi-check2-circle"></i></span>
          <span>Seguimientos asignados</span>
        </a>

        <a class="side-link" href="#">
          <span class="side-icon"><i class="bi bi-journal-text"></i></span>
          <span>Bitácoras</span>
        </a>

        <a class="side-link" href="#">
          <span class="side-icon"><i class="bi bi-award"></i></span>
          <span>Certificación</span>
        </a>

        <a class="side-link" href="#">
          <span class="side-icon"><i class="bi bi-file-earmark-text"></i></span>
          <span>GFPI-F-165</span>
        </a>

        <div class="nav-title">HORARIOS</div>

  <a class="side-link" data-bs-toggle="collapse" href="#horariosSubmenu" role="button"
     aria-expanded="false" aria-controls="horariosSubmenu" data-id="mis_horarios">
    <span class="side-icon"><i class="bi bi-calendar3"></i></span>
    <span>Mis horarios</span>
    <span class="badge rounded-pill badge-pill">3</span>
    <i class="bi bi-chevron-down ms-2"></i>
  </a>

  <div class="collapse submenu" id="horariosSubmenu">
    <a class="side-link" href="#" data-id="horario_semanal">
      <span class="side-icon"><i class="bi bi-clock"></i></span>
      <span>Horario semanal</span>
    </a>

    <a class="side-link" href="#" data-id="asistencia">
      <span class="side-icon"><i class="bi bi-calendar-check"></i></span>
      <span>Asistencia</span>
    </a>

    <a class="side-link" href="#" data-id="cambios_solicitudes">
      <span class="side-icon"><i class="bi bi-arrow-repeat"></i></span>
      <span>Cambios / Solicitudes</span>
    </a>
  </div>
</aside>
</nav>

<main class="flex-grow-1 p-4">
<!-- Barra superior del contenido (aquí va el botón) -->
<div class="d-flex justify-content-end mb-3">
<button class="btn btn-primary" id="btnAccionListarArea">
<i class="bi bi-plus-lg me-1"></i> Nuevo
</button>
</div>