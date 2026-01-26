 $(document).ready(function () {
      
      $('#togglePassword').on('click', function () {
        const input = $('#password');
        const isPass = input.attr('type') === 'password';
        input.attr('type', isPass ? 'text' : 'password');
        // Cambiar icono de Bootstrap Icons
        $(this).find('i').toggleClass('bi-eye bi-eye-slash');
      });

      const redirectByRole = {
        aprendiz: 'vista/modulos/pagina.php',
        instructor: 'vista/modulos/pagina.php',
        coordinador: 'vista/modulos/pagina.php'
      };

      $('#loginForm').on('submit', function (e) {
        e.preventDefault();
        $('#message').removeClass('text-danger text-success').text('');

        const rol = $('input[name="rol"]:checked').val();

        $.ajax({
          url: 'controlador/loginControlador.php',
          type: 'POST',
          dataType: 'json',
          data: {
            email: $('#email').val(),
            password: $('#password').val(),
            rol: rol
          },
          success: function (response) {
            if (response.success) {
              window.location.href = redirectByRole[rol] || '../modulos/pagina.php';
            } else {
              $('#message').addClass('text-danger').text(response.message || 'Credenciales invalidas.');
            }
          },
          error: function () {
            $('#message').addClass('text-danger').text('Error en la conexion. Por favor, intente mas tarde.');
          }
        });
      });

    });