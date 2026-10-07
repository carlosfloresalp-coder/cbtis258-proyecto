<?php

$conexion = mysqli_connect(
    "localhost",
    "root",
    "",
    "justificantes"
);

if (!$conexion) {
    die("Error al conectar con MySQL: " . mysqli_connect_error());
}

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : "";
$matricula = isset($_POST['matricula']) ? trim($_POST['matricula']) : "";

$alumno = false;

if ($matricula != "") {

    $sql = "SELECT *,
                   CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) AS nombre_alumno
            FROM salidas
            WHERE matricula = ?";

    $consulta = mysqli_prepare($conexion, $sql);

    mysqli_stmt_bind_param(
        $consulta,
        "s",
        $matricula
    );

} elseif ($nombre != "") {

    $sql = "SELECT *,
                   CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) AS nombre_alumno
            FROM salidas
            WHERE CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) LIKE ?";

    $consulta = mysqli_prepare($conexion, $sql);

    $nombreBuscar = "%" . $nombre . "%";

    mysqli_stmt_bind_param(
        $consulta,
        "s",
        $nombreBuscar
    );

} else {

    $consulta = false;
}

if ($consulta) {

    mysqli_stmt_execute($consulta);

    $resultado = mysqli_stmt_get_result($consulta);

    if (mysqli_num_rows($resultado) > 0) {

        $alumno = mysqli_fetch_assoc($resultado);

    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Datos del Alumno</title>

    <link
        rel="icon"
        type="image/png"
        href="cbtis 258.jfif"
    >

    <link
        rel="stylesheet"
        href="datos.css"
    >

</head>

<body>

<div class="page-shell">

    <header class="topbar">

        <div class="brand">

            <img
                src="cbtis 258.jfif"
                alt="Logo CBTis 258"
            >

            <div>

                <span class="eyebrow">
                    Plantel educativo
                </span>

                <h1>
                    CBTis 258
                </h1>

            </div>

        </div>

    </header>


    <main class="content">

        <section class="hero">

            <p class="tag">
                Sistema escolar
            </p>

            <h2>
                Datos del alumno
            </h2>

            <p class="subtitle">
                Información del estudiante registrada en el sistema.
            </p>

        </section>


        <?php if ($alumno) { ?>

        <section class="form-panel">

            <div class="contenedor">

                <h3>
                    DATOS DEL/LA ALUMN@
                </h3>

                <div class="linea"></div>


                <div class="dato">

                    <label>
                        NOMBRE:
                    </label>

                    <input
                        type="text"
                        value="<?php
                        echo htmlspecialchars(
                            $alumno['nombre']
                        );
                        ?>"
                        readonly
                    >

                </div>

                <div class="dato">

                    <label>APELLIDO PATERNO:</label>

                    <input
                        type="text"
                        value="<?php echo htmlspecialchars($alumno['apellido_paterno']); ?>"
                        readonly
                    >

                </div>

                <div class="dato">

                    <label>APELLIDO MATERNO:</label>

                    <input
                        type="text"
                        value="<?php echo htmlspecialchars($alumno['apellido_materno']); ?>"
                        readonly
                    >

                </div>


                <div class="dato">

                    <label>
                        MATRÍCULA:
                    </label>

                    <input
                        type="text"
                        value="<?php
                        echo htmlspecialchars(
                            $alumno['matricula']
                        );
                        ?>"
                        readonly
                    >

                </div>


                <div class="dato">

                    <label>
                        SEMESTRE Y GRUPO:
                    </label>

                    <input
                        type="text"
                        value="<?php
                        echo htmlspecialchars(
                            $alumno['semestre_grupo']
                        );
                        ?>"
                        readonly
                    >

                </div>


                <div class="dato">

                    <label>
                        ESPECIALIDAD:
                    </label>

                    <input
                        type="text"
                        value="<?php
                        echo htmlspecialchars(
                            $alumno['especialidad']
                        );
                        ?>"
                        readonly
                    >

                </div>


                <div class="dato">

                    <label>
                        TURNO:
                    </label>

                    <input
                        type="text"
                        value="<?php
                        echo htmlspecialchars(
                            $alumno['turno']
                        );
                        ?>"
                        readonly
                    >

                </div>


                <div class="credenciales">

                    <p>
                        PERSONA(S) AUTORIZADA(S):
                    </p>

                    <div class="fotos">

                        <?php if (!empty($alumno['foto1'])) { ?>

                            <img
                                src="<?php
                                echo htmlspecialchars(
                                    $alumno['foto1']
                                );
                                ?>"
                                alt="Credencial 1"
                            >

                        <?php } else { ?>

                            <div class="foto"></div>

                        <?php } ?>


                        <?php if (!empty($alumno['foto2'])) { ?>

                            <img
                                src="<?php
                                echo htmlspecialchars(
                                    $alumno['foto2']
                                );
                                ?>"
                                alt="Credencial 2"
                            >

                        <?php } else { ?>

                            <div class="foto"></div>

                        <?php } ?>


                        <?php if (!empty($alumno['foto3'])) { ?>

                            <img
                                src="<?php
                                echo htmlspecialchars(
                                    $alumno['foto3']
                                );
                                ?>"
                                alt="Credencial 3"
                            >

                        <?php } else { ?>

                            <div class="foto"></div>

                        <?php } ?>


                        <?php if (!empty($alumno['foto4'])) { ?>

                            <img
                                src="<?php
                                echo htmlspecialchars(
                                    $alumno['foto4']
                                );
                                ?>"
                                alt="Credencial 4"
                            >

                        <?php } else { ?>

                            <div class="foto"></div>

                        <?php } ?>

                    </div>

                </div>


                <div class="botones">

                    <a
                        href="Reporte.php?matricula=<?php echo urlencode($alumno['matricula']); ?>"
                        class="btn-volver"
                    >
                        GENERAR PASE
                    </a>

                    <a
                        href="buscar.php"
                        class="btn-volver"
                    >
                        VOLVER A BUSCAR
                    </a>

                </div>

            </div>

        </section>


        <?php } else { ?>


        <section class="form-panel">

            <div class="contenedor">

                <h3>
                    DATOS DEL/LA ALUMN@
                </h3>

                <div class="linea"></div>


                <div class="dato">

                    <label>
                        RESULTADO:
                    </label>

                    <input
                        type="text"
                        value="<?php

                        if (
                            $nombre == "" &&
                            $matricula == ""
                        ) {

                            echo "DEBES INGRESAR UN DATO";

                        } else {

                            echo "ALUMNO NO ENCONTRADO";

                        }

                        ?>"
                        readonly
                    >

                </div>


                <div class="botones">
                    <a href="Reporte.php" class="btn-volver">
                        GENERAR PASE
                    </a>

                    <a
                        href="Inicio.php"
                        class="btn-volver"
                    >
                        VOLVER A INICIO
                    </a>


</div>

                </div>

            </div>

        </section>


        <?php } ?>


    </main>

</div>


<div id="imageModal" class="modal" aria-modal="true" role="dialog">
    <div class="modal-content">
        <button type="button" class="modal-close" aria-label="Cerrar">×</button>
        <img id="modalImage" src="" alt="Imagen ampliada">
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const closeButton = document.querySelector('.modal-close');
        const images = document.querySelectorAll('.fotos img');

        images.forEach(function (image) {
            image.addEventListener('click', function () {
                modalImage.src = image.src;
                modalImage.alt = image.alt;
                modal.classList.add('show');
            });
        });

        if (closeButton) {
            closeButton.addEventListener('click', function () {
                modal.classList.remove('show');
                modalImage.src = '';
            });
        }

        if (modal) {
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.classList.remove('show');
                    modalImage.src = '';
                }
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal && modal.classList.contains('show')) {
                modal.classList.remove('show');
                modalImage.src = '';
            }
        });
    });
</script>

<?php

mysqli_close($conexion);

?>

</body>

</html>