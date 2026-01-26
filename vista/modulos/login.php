<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - SGD</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  
  <style>
    :root {
      --primary-color: #7c6bff;
      --primary-hover: #6b5ce7;
      --bg-color: #f0f2ff;
    }
    
    body {
      background: var(--bg-color);
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }
    
    /* Patron de puntos decorativo */
    .dots-pattern {
      position: absolute;
      width: 120px;
      height: 120px;
      background-image: radial-gradient(circle, #c5c5e8 2px, transparent 2px);
      background-size: 15px 15px;
      opacity: 0.6;
    }
    .dots-top { top: 80px; right: 15%; }
    .dots-bottom { bottom: 80px; left: 10%; }
    
    /* Formas decorativas */
    .shape {
      position: absolute;
      border-radius: 20px;
      opacity: 0.15;
    }
    .shape-1 {
      width: 200px;
      height: 200px;
      background: var(--primary-color);
      bottom: 10%;
      left: 5%;
      transform: rotate(-15deg);
    }
    .shape-2 {
      width: 150px;
      height: 100px;
      background: var(--primary-color);
      bottom: 15%;
      left: 15%;
      transform: rotate(10deg);
    }
    
    /* Tarjeta principal */
    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.8);
      box-shadow: 0 20px 60px rgba(124, 107, 255, 0.1), 0 8px 25px rgba(0, 0, 0, 0.06);
    }
    
    /* Selector de roles tipo pill */
    .role-selector {
      display: flex;
      background: #f5f5fa;
      border-radius: 12px;
      padding: 5px;
      gap: 5px;
    }
    
    .role-selector .form-check {
      flex: 1;
      margin: 0;
      padding: 0;
    }
    
    .role-selector .form-check-input {
      display: none;
    }
    
    .role-selector .form-check-label {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 10px 12px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 13px;
      font-weight: 500;
      color: #6c757d;
      transition: all 0.25s ease;
      text-align: center;
      width: 100%;
    }
    
    .role-selector .form-check-input:checked + .form-check-label {
      background: white;
      color: var(--primary-color);
      box-shadow: 0 2px 8px rgba(124, 107, 255, 0.2);
    }
    
    .role-selector .form-check-label:hover {
      color: var(--primary-color);
    }
    
    /* Inputs mejorados */
    .form-control {
      border: 2px solid #e9ecef;
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 15px;
      transition: all 0.25s ease;
    }
    
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(124, 107, 255, 0.1);
    }
    
    .form-control::placeholder {
      color: #adb5bd;
    }
    
    /* Input group para password */
    .input-group .form-control {
      border-right: none;
    }
    
    .input-group .btn-toggle-pass {
      background: white;
      border: 2px solid #e9ecef;
      border-left: none;
      border-radius: 0 10px 10px 0;
      color: #6c757d;
      transition: all 0.25s ease;
    }
    
    .input-group:focus-within .form-control,
    .input-group:focus-within .btn-toggle-pass {
      border-color: var(--primary-color);
    }
    
    .input-group:focus-within .form-control {
      box-shadow: none;
    }
    
    /* Boton principal */
    .btn-login {
      background: linear-gradient(135deg, var(--primary-color) 0%, #9d8fff 100%);
      border: none;
      border-radius: 10px;
      padding: 14px 24px;
      font-weight: 600;
      font-size: 15px;
      color: white;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(124, 107, 255, 0.35);
    }
    
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(124, 107, 255, 0.45);
      background: linear-gradient(135deg, var(--primary-hover) 0%, #8a7aee 100%);
      color: white;
    }
    
    .btn-login:active {
      transform: translateY(0);
    }
    
    /* Labels */
    .form-label-custom {
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #6c757d;
    }
    
    /* Link */
    .link-forgot {
      color: var(--primary-color);
      font-size: 12px;
      text-decoration: none;
      font-weight: 500;
    }
    
    .link-forgot:hover {
      color: var(--primary-hover);
      text-decoration: underline;
    }
    
    /* Logo/Icono superior */
    .brand-icon {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, var(--primary-color) 0%, #9d8fff 100%);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 24px;
      margin-bottom: 20px;
      box-shadow: 0 4px 15px rgba(124, 107, 255, 0.3);
    }
  </style>
</head>

<body class="d-flex align-items-center">
  
  <!-- Elementos decorativos -->
  <div class="dots-pattern dots-top"></div>
  <div class="dots-pattern dots-bottom"></div>
  <div class="shape shape-1"></div>
  <div class="shape shape-2"></div>
  
  <div class="container position-relative" style="z-index: 10;">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">

        <div class="card login-card border-0 rounded-4">
          <div class="card-body p-4 p-md-5">
            
            <!-- Icono de marca -->
            <div class="brand-icon">
              <i class="bi bi-shield-lock"></i>
            </div>

            <h4 class="fw-bold mb-1">Bienvenido a SGD!</h4>
            <p class="text-secondary mb-4">Por favor inicia sesion en tu cuenta</p>

            <form id="loginForm" autocomplete="off">
              
              <!-- Selector de Rol - Estilo Pill -->
              <div class="mb-4">
                <label class="form-label-custom mb-2">Selecciona tu rol</label>
                <div class="role-selector">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="rol" id="rolCoordinador" value="coordinador" checked>
                    <label class="form-check-label" for="rolCoordinador">
                      <i class="bi bi-person-gear"></i>
                      Coordinador
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="rol" id="rolInstructor" value="instructor">
                    <label class="form-check-label" for="rolInstructor">
                      <i class="bi bi-person-video3"></i>
                      Instructor
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="rol" id="rolAprendiz" value="aprendiz">
                    <label class="form-check-label" for="rolAprendiz">
                      <i class="bi bi-mortarboard"></i>
                      Aprendiz
                    </label>
                  </div>
                </div>
              </div>

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label-custom">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Ingresa tu email" required>
              </div>

              <!-- Password -->
              <div class="mb-2 d-flex justify-content-between align-items-center">
                <label for="password" class="form-label-custom mb-0">Contrasena</label>
                <a href="#" class="link-forgot">Olvidaste tu Contrasena?</a>
              </div>

              <div class="input-group mb-4">
                <input type="password" class="form-control" id="password" placeholder="Ingresa tu contrasena" required>
                <button class="btn btn-toggle-pass" type="button" id="togglePassword">
                  <i class="bi bi-eye"></i>
                </button>
              </div>

              <!-- Boton Submit -->
              <button type="submit" class="btn btn-login w-100">
                Iniciar Sesion
              </button>

              <!-- Mensajes -->
              <div id="message" class="mt-3 small text-center"></div>

              <p class="text-center text-secondary small mt-4 mb-0">
                Politica de proteccion de datos personales.
              </p>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  