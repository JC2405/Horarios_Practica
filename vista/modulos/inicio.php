<?php
require_once __DIR__ . '/menu.php';
?>
<link rel="stylesheet" href="vista/css/nav.css">

<div class="app-layout">

  <aside class="sidebar">
    <ul id="menuOpciones" class="menu-inner py-1">
      <?php renderMenu($menuActual); ?>
    </ul>
  </aside>

  <main class="content">
   <?php
   require_once "vista/modulos/prueba.php"; ?>
  </main>

</div>



<script src="../js/nav.js" defer></script>

 