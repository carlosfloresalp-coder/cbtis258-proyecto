<?php
session_start();

if (!isset($_SESSION['login_ok']) || $_SESSION['login_ok'] !== true) {
    header("Location: SESION.php");
    exit;
}

if (isset($_POST['cerrar_sesion'])) {
    session_unset();
    session_destroy();
    header("Location: SESION.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBTis 258 | Justificante de Salida</title>
    <link rel="icon" type="image/png" href="cbtis 258.jfif">
    <link rel="stylesheet" href="Inicio.css">
</head>
<body>
    <div class="page-shell">
        <header class="topbar">
            <div class="brand">
                <img src="cbtis 258.jfif" alt="Logo CBTis 258">
                <div>
                    <span class="eyebrow">Plantel educativo</span>
                    <h1>CBTis 258</h1>
                </div>
            </div>

            <form method="POST" action="Inicio.php" style="margin: 0;">
                <button type="submit" name="cerrar_sesion" value="1" class="logout-btn">
                    Cerrar sesión
                </button>
            </form>
        </header>

        <main class="content">
            <section class="hero">
                <p class="tag">Sistema escolar</p>
                <center><h2>Justificante de Salida</h2></center>
                <p class="subtitle">Consulta información del alumno o registra un nuevo justificante de salida de manera rápida y ordenada.</p>
            </section>

            <section class="actions">
                <a href="buscar.php" class="action-card">
                    <span class="icon">🔎</span>
                    <h3>Consultar Alumno</h3>
                    <p>Busca al estudiante por nombre o matrícula.</p>
                </a>
                <a href="bajas.php" class="action-card">
                    <span class="icon">🔎</span>
                    <h3>Eliminar Alumno</h3>
                    <p>Registra bajas de estudiantes en el sistema.</p>
                </a>

                <a href="altas.php" class="action-card">
                    <span class="icon">📝</span>
                    <h3>Registrar Alumno</h3>
                    <p>Registra nuevos estudiantes en el sistema.</p>
                </a>
            </section>
        </main>
    </div>
</body>
</html>