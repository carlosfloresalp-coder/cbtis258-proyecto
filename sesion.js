document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Evita que la página se recargue

    const usuarioCorrecto = "escolares";
    const usuarioCorreo = "escolares@cbtis258.edu.mx";
    const passwordCorrecto = "cbtis258";

    const inputUsuario = document.getElementById('usuario').value;
    const inputCorreo = document.getElementById('correo').value;
    const inputPassword = document.getElementById('contrasena').value;
    const mensaje = document.getElementById('mensaje');

    if ((inputUsuario === usuarioCorrecto || inputCorreo === usuarioCorreo) && inputPassword === passwordCorrecto) {
        mensaje.style.color = "green";
        mensaje.textContent = "¡Inicio de sesión exitoso!";
        window.location.href = "inicio.php";
    } else {
        mensaje.style.color = "red";
        mensaje.textContent = "Usuario o contraseña incorrectos.";
    }
 });
