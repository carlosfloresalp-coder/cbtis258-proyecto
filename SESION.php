<?php
session_start();

$conexion = mysqli_connect("localhost", "root", "", "inicio_de_sesion")
    or die("No se pudo conectar a la base de datos: " . mysqli_connect_error());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';

    $usuario = mysqli_real_escape_string($conexion, $usuario);
    $contraseña = mysqli_real_escape_string($conexion, $contraseña);

    $consulta = "SELECT * FROM inicio_sesion WHERE usuario = '$usuario' AND contraseña = '$contraseña' LIMIT 1";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['login_ok'] = true;
        header("Location: Inicio.php");
        exit;
    } else {
        $mensaje_error = "Usuario o contraseña incorrectos";
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="icon" type="image/png" href="cbtis 258.jfif">
    <link rel="stylesheet" href="sesion.css">
</head>

<body>
    <div class="login">
        <a class="back-link" href="index.html">Volver atrás</a>
        <h2>Iniciar Sesión</h2>
        <form id="loginForm" method="POST" action="SESION.php">
            <div>
                <label for="usuario">Usuario:</label>
                <input type="text" name="usuario" id="usuario" required>
            </div>

            <div>
                <label for="correo">Correo Electrónico:</label>
                <input type="email" name="correo" id="correo" required>
            </div>
            <div>
                <label for="contraseña">Contraseña:</label>
                <input type="password" name="contraseña" id="contraseña" required>
            </div>
            <button type="submit">INICIAR SESIÓN</button>
        </form>
        <?php if (!empty($mensaje_error)): ?>
            <p style="color: red;"><?php echo $mensaje_error; ?></p>
        <?php endif; ?>
        <p id="mensaje"></p>
    </div>
</body>
</html>