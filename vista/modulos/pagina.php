<style>
       :root {
    --primary: #7c6bff;
    --primary-hover: #6b5ce7;
    --primary-light: #f4f3ff;
    --text-dark: #1e293b;
    --text-muted: #64748b;
    --bg-light: #f8fafc;
}

/* Reset básico */
body {
    font-family: 'Inter', sans-serif;
    background: var(--bg-light);
    margin: 0;
    padding: 40px 20px;
}

/* Contenedor principal */
.container-custom {
    max-width: 1100px;
    margin: 0 auto;
}

/* Card principal */
.card-construction {
    background: white;
    border-radius: 20px;
    padding: 50px 40px;
    box-shadow: 0 15px 40px rgba(15, 23, 42, 0.08);
    text-align: center;
}

/* Ícono superior */
.icon-container {
    width: 90px;
    height: 90px;
    margin: 0 auto 25px;
    background: var(--primary-light);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-container i {
    font-size: 40px;
    color: var(--primary);
}

/* Título */
h1 {
    font-size: 32px;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 10px;
}

/* Subtítulo */
.subtitle {
    font-size: 16px;
    color: var(--text-muted);
    margin-bottom: 30px;
}

/* Grid de accesos */
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 25px;
    margin-top: 40px;
}

/* Tarjetas internas */
.feature-card {
    background: var(--primary-light);
    padding: 30px 20px;
    border-radius: 16px;
    transition: all 0.25s ease;
    cursor: pointer;
}

.feature-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 30px rgba(124, 107, 255, 0.15);
    background: white;
    border: 1px solid rgba(124,107,255,0.15);
}

.feature-card i {
    font-size: 32px;
    color: var(--primary);
    margin-bottom: 12px;
}

.feature-card h3 {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 6px;
}

.feature-card p {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
}

/* Botón principal */
.btn-home {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 28px;
    background: var(--primary);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    margin-top: 35px;
    transition: all 0.2s ease;
}

.btn-home:hover {
    background: var(--primary-hover);
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .card-construction {
        padding: 35px 25px;
    }

    h1 {
        font-size: 24px;
    }
}

    </style>
</head>
<body>
    <div class="container-custom">
        <div class="card-construction">
            <!-- Icono Principal -->
            <div class="icon-container">
                <i class="bi bi-tools"></i>
            </div>

            <!-- Título -->
            <h1>¡Estamos Construyendo Algo Increíble!</h1>
            <p class="subtitle">Sistema de Gestión de Horarios SENA</p>

            <!-- Descripción -->
            <p class="description">
                Nuestro equipo está trabajando arduamente para traerte la mejor experiencia 
                en la gestión de horarios, ambientes y fichas. Pronto podrás disfrutar de 
                todas las funcionalidades.
            </p>

            <!-- Barra de Progreso -->
            <div class="progress-container">
                <div class="progress-label">
                    <span>Progreso del Desarrollo</span>
                    <span>1%</span>
                </div>
                <div class="progress-custom">
                    <div class="progress-bar-custom"></div>
                </div>
            </div>

            <!-- Características -->
            <div class="features-grid">
                <div class="feature-card">
                    <i class="bi bi-calendar-check"></i>
                    <h3>Horarios</h3>
                    <p>Gestión inteligente de horarios</p>
                </div>
                <div class="feature-card">
                    <i class="bi bi-door-open"></i>
                    <h3>Ambientes</h3>
                    <p>Control total de espacios</p>
                </div>
                <div class="feature-card">
                    <i class="bi bi-people"></i>
                    <h3>Instructores</h3>
                    <p>Administración eficiente</p>
                </div>
            </div>

            <!-- Badges de Estado -->
            <div class="badges">
                <span class="badge-custom">
                    <span class="spinner"></span>
                    En Desarrollo
                </span>
                <span class="badge-custom">
                    <i class="bi bi-lightning-charge"></i>
                    Próximamente
                </span>
                <span class="badge-custom">
                    <i class="bi bi-shield-check"></i>
                    Seguro
                </span>
            </div>

            <!-- Botón de Inicio -->
            <a href="index.php" class="btn-home">
                <i class="bi bi-house-door"></i>
                Volver al Inicio
            </a>
        </div>
    </div>