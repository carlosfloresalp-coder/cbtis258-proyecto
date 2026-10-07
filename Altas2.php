<?php

$conexion = mysqli_connect(
    "localhost",
    "root",
    "",
    "justificantes"
);

if (!$conexion) {
    die("Problema conectando con MySQL: " . mysqli_connect_error());
}

$matricula = $_POST['matricula'];
$nombre = mb_convert_case(trim($_POST['nombre'] ?? ''), MB_CASE_TITLE, 'UTF-8');
$apellido_paterno = mb_convert_case(trim($_POST['apellido_paterno'] ?? ''), MB_CASE_TITLE, 'UTF-8');
$apellido_materno = mb_convert_case(trim($_POST['apellido_materno'] ?? ''), MB_CASE_TITLE, 'UTF-8');

$semestre_grupo = $_POST['semestre_grupo'];
$especialidad = $_POST['especialidad'];
$turno = $_POST['turno'];

if (!preg_match('/\A[0-9]{1,14}\z/', $matricula)) {
    http_response_code(422);
    exit('La matrícula debe contener únicamente números y no exceder 14 dígitos.');
}

$verificarMatricula = mysqli_prepare(
    $conexion,
    'SELECT 1 FROM salidas WHERE matricula = ? LIMIT 1'
);

if (!$verificarMatricula) {
    die('Problema verificando la matrícula: ' . mysqli_error($conexion));
}

mysqli_stmt_bind_param($verificarMatricula, 's', $matricula);

if (!mysqli_stmt_execute($verificarMatricula)) {
    $errorConsulta = mysqli_stmt_error($verificarMatricula);
    mysqli_stmt_close($verificarMatricula);
    mysqli_close($conexion);
    die('Problema verificando la matrícula: ' . $errorConsulta);
}

mysqli_stmt_store_result($verificarMatricula);
$matriculaDuplicada = mysqli_stmt_num_rows($verificarMatricula) > 0;
mysqli_stmt_close($verificarMatricula);

if ($matriculaDuplicada) {
    mysqli_close($conexion);
    header('Location: altas.php?error=matricula_duplicada');
    exit;
}

$carpeta = "fotos/";

if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}

$foto1 = "";
$foto2 = "";
$foto3 = "";
$foto4 = "";

function guardarFoto($archivo, $matricula, $numero, $carpeta)
{
    if (
        isset($_FILES[$archivo]) &&
        $_FILES[$archivo]['error'] === UPLOAD_ERR_OK
    ) {

        $extension = pathinfo(
            $_FILES[$archivo]['name'],
            PATHINFO_EXTENSION
        );

        $nombre = $matricula . "_foto" . $numero . "." . $extension;

        $ruta = $carpeta . $nombre;

        if (move_uploaded_file(
            $_FILES[$archivo]['tmp_name'],
            $ruta
        )) {
            return $ruta;
        }
    }

    return "";
}

$foto1 = guardarFoto(
    "foto1",
    $matricula,
    1,
    $carpeta
);

$foto2 = guardarFoto(
    "foto2",
    $matricula,
    2,
    $carpeta
);

$foto3 = guardarFoto(
    "foto3",
    $matricula,
    3,
    $carpeta
);

$foto4 = guardarFoto(
    "foto4",
    $matricula,
    4,
    $carpeta
);

$sql = "INSERT INTO salidas
(
    matricula,
    nombre,
    apellido_paterno,
    apellido_materno,
    semestre_grupo,
    especialidad,
    turno,
    foto1,
    foto2,
    foto3,
    foto4
)
VALUES
(
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
)";

$consulta = mysqli_prepare($conexion, $sql);

if (!$consulta) {
    die("Problema preparando el INSERT: " . mysqli_error($conexion));
}

mysqli_stmt_bind_param(
    $consulta,
    "sssssssssss",
    $matricula,
    $nombre,
    $apellido_paterno,
    $apellido_materno,
    $semestre_grupo,
    $especialidad,
    $turno,
    $foto1,
    $foto2,
    $foto3,
    $foto4
);

if (!mysqli_stmt_execute($consulta)) {
    die("Problema en el INSERT: " . mysqli_stmt_error($consulta));
}

mysqli_stmt_close($consulta);
mysqli_close($conexion);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <link
        rel="icon"
        type="image/png"
        href="cbtis 258.jfif"
    >

    <title>Registro Exitoso</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, Helvetica, sans-serif;
            background:
                linear-gradient(rgba(56, 10, 10, 0.72), rgba(56, 10, 10, 0.72)),
                url("cobrafondo.png") center center / cover no-repeat fixed;
            padding: 20px;
        }

        .contenedor {
            width: 100%;
            max-width: 650px;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 25px;
            padding: 45px 40px;
            text-align: center;
            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.35);
        }

        .icono {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #7a1212;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            box-shadow:
                0 8px 20px rgba(122, 18, 18, 0.35);
        }

        h1 {
            margin: 0;
            color: #7a1212;
            font-size: 30px;
        }

        .mensaje {
            margin: 15px 0 30px;
            color: #5b6475;
            font-size: 16px;
            line-height: 1.6;
        }

        .datos {
            background: #f5eeee;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: left;
        }

        .dato {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .dato:last-child {
            border-bottom: none;
        }

        .dato strong {
            color: #7a1212;
        }

        .dato span {
            color: #333;
            text-align: right;
        }

        .botones {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .boton {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 200px;
            height: 48px;
            padding: 0 20px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            transition: 0.2s ease;
        }

        .nuevo {
            background: #8d3030;
            border: 2px solid #d8aa52;
            color: white;
        }

        .nuevo:hover {
            background: #a83d3d;
            transform: translateY(-3px);
        }

        .volver {
            background: white;
            border: 2px solid #7a1212;
            color: #7a1212;
        }

        .volver:hover {
            background: #f1dfb5;
            transform: translateY(-3px);
        }

        @media (max-width: 600px) {

            .contenedor {
                padding: 30px 20px;
            }

            h1 {
                font-size: 24px;
            }

            .dato {
                flex-direction: column;
                gap: 4px;
            }

            .dato span {
                text-align: left;
            }

            .boton {
                width: 100%;
            }

        }

    </style>

</head>

<body>

<div class="contenedor">

    <div class="icono">
        ✓
    </div>

    <h1>
        ¡ESTUDIANTE REGISTRADO!
    </h1>

    <p class="mensaje">
        El estudiante fue registrado correctamente
        junto con la información de las personas autorizadas.
    </p>

    <div class="datos">

        <div class="dato">
            <strong>Matrícula:</strong>
            <span>
                <?php echo htmlspecialchars($matricula); ?>
            </span>
        </div>

        <div class="dato">
            <strong>Alumno:</strong>
            <span>
                <?php echo htmlspecialchars($nombre); ?>
            </span>
        </div>

        <div class="dato">
            <strong>Apellido Paterno:</strong>
            <span>
                <?php echo htmlspecialchars($apellido_paterno); ?>
            </span>
        </div>

        <div class="dato">
            <strong>Apellido Materno:</strong>
            <span>
                <?php echo htmlspecialchars($apellido_materno); ?>
            </span>
        </div>

        <div class="dato">
            <strong>Semestre y grupo:</strong>
            <span>
                <?php echo htmlspecialchars($semestre_grupo); ?>
            </span>
        </div>

        <div class="dato">
            <strong>Especialidad:</strong>
            <span>
                <?php echo htmlspecialchars($especialidad); ?>
            </span>
        </div>

        <div class="dato">
            <strong>Turno:</strong>
            <span>
                <?php echo htmlspecialchars($turno); ?>
            </span>
        </div>

    </div>

    <div class="botones">

        <a
            href="altas.php"
            class="boton nuevo"
        >
            NUEVO ESTUDIANTE
        </a>

        <a
            href="inicio.php"
            class="boton volver"
        >
            VOLVER AL INICIO
        </a>

    </div>

</div>

</body>

</html>