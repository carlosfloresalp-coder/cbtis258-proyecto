<?php

$matricula = $_GET['matricula'] ?? '';

$datosAlumno = [];

if ($matricula !== '') {

    $conexion = mysqli_connect(
        "localhost",
        "root",
        "",
        "justificantes"
    );

    if (!$conexion) {
        die("Error al conectar con MySQL: " . mysqli_connect_error());
    }

    $sql = "SELECT *,
                   CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) AS nombre_alumno
            FROM salidas
            WHERE matricula = ?";

    $stmt = mysqli_prepare($conexion, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $matricula
    );

    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    if ($resultado && mysqli_num_rows($resultado) > 0) {

        $datosAlumno = mysqli_fetch_assoc($resultado);

    }

    mysqli_close($conexion);
}


/* =========================
   FECHA Y HORA AUTOMÁTICAS
   ========================= */

date_default_timezone_set("America/Monterrey");

$fecha_actual = date("Y-m-d");
$hora_actual = date("H:i");


/* =========================
   FOLIO AUTOMÁTICO
   ========================= */

$folio_autogenerado =
    "FOL-" .
    date("Ymd") .
    "-" .
    date("His") .
    "-" .
    substr($matricula, -4);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Reporte</title>

    <link
        rel="icon"
        type="image/png"
        href="cbtis 258.jfif"
    >

    <link
        rel="stylesheet"
        href="reporte.css"
    >

</head>


<body>


<img
    class="logo-superior-izquierdo"
    src="cbtis 258.jfif"
    alt="Logo DGETI"
>


<button
    class="boton-imprimir"
    type="button"
    onclick="if (document.getElementById('motivo').value === '') { alert('Selecciona un motivo antes de imprimir.'); document.getElementById('motivo').focus(); return; } window.print();"
>
    IMPRIMIR
</button>


<a
    href="Inicio.php"
    class="boton-volver"
>
    VOLVER AL INICIO
</a>


<main class="contenedor-reporte">


<form
    class="formulario-reporte"
    action="procesar.php"
    method="POST"
    onsubmit="if (document.getElementById('motivo').value === '') { alert('Selecciona un motivo antes de imprimir.'); document.getElementById('motivo').focus(); return false; } window.print(); return true;"
>


    <!-- FOLIO -->

    <div class="campo">

        <label for="folio">
            NÚMERO DE FOLIO:
        </label>

        <input
            id="folio"
            name="folio"
            type="text"
            value="<?php echo htmlspecialchars($folio_autogenerado); ?>"
            readonly
        >

    </div>


    <!-- MATRÍCULA -->

    <div class="campo">

        <label for="matricula">
            MATRÍCULA:
        </label>

        <input
            id="matricula"
            name="matricula"
            type="text"
            value="<?php echo htmlspecialchars($datosAlumno['matricula'] ?? $matricula); ?>"
            readonly
        >

    </div>


    <!-- NOMBRE -->

    <div class="campo campo-nombre">

        <label for="nombre-alumno">
            NOMBRE DEL ALUMNO:
        </label>

        <input
            id="nombre-alumno"
            name="nombre_alumno"
            type="text"
            value="<?php echo htmlspecialchars($datosAlumno['nombre_alumno'] ?? ''); ?>"
            readonly
            required
        >

    </div>


    <!-- MOTIVO -->

    <div class="campo campo-motivo">

        <label for="motivo">
            MOTIVO:
        </label>

        <select
            id="motivo"
            name="motivo"
            required
        >

            <option value="" selected disabled>
                Seleccionar motivo
            </option>

            <option value="Salud">
                Salud
            </option>

            <option value="familiar">
                Temas familiares
            </option>

            <option value="academico">
                Situación académica
            </option>

            <option value="Tramite Legal">
                Trámite Legal
            </option>

        </select>

    </div>


    <!-- CARRERA / ESPECIALIDAD AUTOMÁTICA -->

    <div class="campo campo-especialidad">

        <label for="especialidad">
            CARRERA:
        </label>

        <input
            id="especialidad"
            name="especialidad"
            type="text"
            value="<?php echo htmlspecialchars($datosAlumno['especialidad'] ?? ''); ?>"
            readonly
            required
        >

    </div>


    <!-- GRUPO AUTOMÁTICO -->

    <div class="campo campo-grupo">

        <label for="grupo">
            GRUPO:
        </label>

        <input
            id="grupo"
            name="grupo"
            type="text"
            value="<?php echo htmlspecialchars($datosAlumno['semestre_grupo'] ?? ''); ?>"
            readonly
            required
        >

    </div>


    <!-- TURNO AUTOMÁTICO -->

    <div class="campo">

        <label for="turno">
            TURNO:
        </label>

        <input
            id="turno"
            name="turno"
            type="text"
            value="<?php echo htmlspecialchars($datosAlumno['turno'] ?? ''); ?>"
            readonly
            required
        >

    </div>


    <!-- HORA AUTOMÁTICA -->

    <div class="campo campo-hora">

        <label for="hora">
            HORA:
        </label>

        <input
            id="hora"
            name="hora"
            type="time"
            value="<?php echo $hora_actual; ?>"
            readonly
            required
        >

    </div>


    <!-- FECHA AUTOMÁTICA -->

    <div class="campo campo-fecha">

        <label for="fecha">
            FECHA:
        </label>

        <input
            id="fecha"
            name="fecha"
            type="date"
            value="<?php echo $fecha_actual; ?>"
            readonly
            required
        >

    </div>


    <footer class="firmas">

        <div class="firma">
            SELLO DE LA INSTITUCION
        </div>

        <div class="firma">
            FIRMA DE LA ENCARGADA
        </div>

    </footer>


   <button
    class="boton-imprimir"
    type="button"
    onclick="window.print()"
>
    IMPRIMIR
    </button>

</form>


</main>


</body>

</html>
