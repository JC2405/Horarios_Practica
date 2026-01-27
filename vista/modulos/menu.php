<?php

$rawRole = $_SESSION['rol'] ?? 'aprendiz'; 
$role = strtolower(trim($rawRole));


$tipoRol = ['aprendiz', 'instructor', 'coordinador'];


if (!in_array($role, $tipoRol)) {
    $role = 'aprendiz';
}

// 3) Menús
$menus = [
  'aprendiz' => [
    [
      'type' => 'tree',
      'label' => 'Aprendiz',
      'icon' => 'bx bx-user',
      'items' => [
        ['label' => 'Horarios', 'href' => 'horarios'],
      ]
    ],
  ],

  'instructor' => [
    [
      'type' => 'tree',
      'label' => 'Instructor',
      'icon' => 'bx bx-book',
      'items' => [
        ['label' => 'Fichas', 'href' => 'fichasInstructor'],
        ['label' => 'Horarios', 'href' => 'horarios'],
      ]
    ],
  ],

  'coordinador' => [
    [
      'type' => 'tree',
      'label' => 'Programa Formación',
      'icon' => 'bx bx-book',
      'items' => [
        ['label' => 'Crear ficha', 'href' => 'crearFicha'],
        ['label' => 'Listar fichas', 'href' => 'listarFichas'],
        ['label' => 'Carga aprendices', 'href' => 'aprendicesCarga'],
        
      ]
    ],
    [
      'type' => 'tree',
      'label' => 'Instructores',
      'icon' => 'bx bx-user',
      'items' => [
        ['label' => 'Áreas', 'href' => 'areas'],
      ]
    ],
    [
      'type' => 'tree',
      'label' => 'Horario',
      'icon' => 'bx bx-time',
      'items' => [
        ['label' => 'Crear horario', 'href' => 'crearHorario'],
      ]
    ],
  ],
];

// 4) Seleccionar menú
$menuActual = $menus[$role];

// 5) Render
function renderMenu(array $menu): void {
  foreach ($menu as $item) {
    echo '<li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons '.htmlspecialchars($item['icon']).'"></i>
        <div>'.htmlspecialchars($item['label']).'</div>
      </a>
      <ul class="menu-sub">';

    foreach ($item['items'] as $sub) {
      echo '<li class="menu-item">
        <a href="'.htmlspecialchars($sub['href']).'" class="menu-link">
          <div>'.htmlspecialchars($sub['label']).'</div>
        </a>
      </li>';
    }

    echo '</ul></li>';
  }
}