<?php
require_once __DIR__ . '/menu.php';

// Obtener datos del usuario de la sesion
$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Usuario';
$userAvatar = isset($_SESSION['user_avatar']) ? $_SESSION['user_avatar'] : null;

// Detectar la URL base del proyecto
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';
?>

<link rel="stylesheet" href="vista/css/nav.css">

<script src="../js/nav.js" defer></script>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">H</div>
        <span class="logo-text">horarios</span>
    </div>
    
    <ul id="menuOpciones" class="menu-inner">
        <?php renderMenu($menuActual); ?>
    </ul>
</aside>

<!--TOPBAR -->
<header class="topbar">
    <div class="topbar-left">
        <span class="hello">Hola!</span>
        <span class="username"><?php echo htmlspecialchars($userName); ?></span>
    </div>

    <div class="topbar-right">
        <button class="user-btn" id="userBtn" aria-haspopup="true" aria-expanded="false">
            <?php if ($userAvatar): ?>
                <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="Avatar" class="avatar">
            <?php else: ?>
                <div class="avatar placeholder"><?php echo strtoupper(substr($userName, 0, 1)); ?></div>
            <?php endif; ?>
        </button>

        <div class="dropdown" id="userDropdown">
            <a href="<?php echo $baseUrl; ?>perfil" class="dropdown-item">Mi perfil</a>
            <div class="dropdown-divider"></div>
            <a href="<?php echo $baseUrl; ?>logout.php" class="dropdown-item danger">Cerrar sesion</a>
        
        </div>
    </div>
</header>

<!-- LAYOUT PRINCIPAL -->
<div class="app-layout">
    <main class="content">

  

<script src="../js/nav.js" defer></script>

