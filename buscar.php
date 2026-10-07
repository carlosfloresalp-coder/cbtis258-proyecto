<?php
if (($_GET['accion'] ?? '') === 'filtrar') {
    header('Content-Type: application/json; charset=utf-8');

    $nombreBuscar = trim($_GET['nombre'] ?? '');
    if (strlen($nombreBuscar) < 2) {
        echo json_encode([]);
        exit;
    }

    $conexion = mysqli_connect('localhost', 'root', '', 'justificantes');
    if (!$conexion) {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo consultar la base de datos.']);
        exit;
    }

    mysqli_set_charset($conexion, 'utf8mb4');
        $sql = "SELECT matricula,
               CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) AS nombre_alumno
            FROM salidas
            WHERE CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) LIKE ?
            ORDER BY nombre_alumno ASC
            LIMIT 10";
    $consulta = mysqli_prepare($conexion, $sql);

    if (!$consulta) {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo realizar la búsqueda.']);
        exit;
    }

    $filtro = '%' . $nombreBuscar . '%';
    mysqli_stmt_bind_param($consulta, 's', $filtro);
    mysqli_stmt_execute($consulta);
    $resultado = mysqli_stmt_get_result($consulta);
    $alumnos = [];

    while ($alumno = mysqli_fetch_assoc($resultado)) {
        $alumnos[] = $alumno;
    }

    echo json_encode($alumnos, JSON_UNESCAPED_UNICODE);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Consultar Alumno</title>

    <link rel="icon" type="image/png" href="cbtis 258.jfif">

    <link rel="stylesheet" href="buscar.css">
    <style>
        .campo-nombre {
            position: relative;
        }

        .sugerencias {
            position: absolute;
            z-index: 5;
            top: calc(100% + 4px);
            right: 0;
            left: 0;
            display: none;
            max-height: 280px;
            overflow-y: auto;
            margin: 0;
            padding: 5px;
            list-style: none;
            border: 1px solid #d5d5d5;
            border-radius: 8px;
            background: white;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.22);
        }

        .sugerencias.visible {
            display: block;
        }

        .sugerencias button {
            width: 100%;
            padding: 10px 12px;
            border: 0;
            border-radius: 5px;
            background: white;
            color: #1d2433;
            text-align: left;
            cursor: pointer;
        }

        .sugerencias button:hover,
        .sugerencias button:focus {
            outline: none;
            background: #f2e5e5;
        }

        .sugerencias .sin-resultados {
            padding: 10px 12px;
            color: #5b6475;
        }
    </style>

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
                Consultar alumno
            </h2>

            <p class="subtitle">
                Busca la información del estudiante por nombre o matrícula.
            </p>

        </section>


        <section class="form-panel">

            <form
                class="formulario"
                action="datos del alumno.php"
                method="POST"
            >

                <div class="campo campo-nombre">

                    <label for="nombre">
                        NOMBRE DEL ALUMNO
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Escribe el nombre del alumno"
                        autocomplete="off"
                        aria-autocomplete="list"
                        aria-expanded="false"
                        aria-controls="sugerencias-nombre"
                    >
                    <ul id="sugerencias-nombre" class="sugerencias" role="listbox"></ul>

                </div>


                <div class="campo">

                    <label for="matricula">
                        MATRÍCULA
                    </label>

                    <input
                        type="text"
                        id="matricula"
                        name="matricula"
                        placeholder="Escribe la matrícula"
                    >

                </div>


                <div class="botones">

                    <button
                        type="submit"
                        class="btn-buscar"
                    >
                        CONSULTAR
                    </button>

                    <a
                        href="Inicio.php"
                        class="btn-volver"
                    >
                        VOLVER AL INICIO
                    </a>

                </div>

            </form>

        </section>

    </main>

</div>

<script>
    const campoNombre = document.getElementById('nombre');
    const campoMatricula = document.getElementById('matricula');
    const listaSugerencias = document.getElementById('sugerencias-nombre');
    let temporizadorBusqueda;
    let solicitudActual;

    function ocultarSugerencias() {
        listaSugerencias.classList.remove('visible');
        listaSugerencias.replaceChildren();
        campoNombre.setAttribute('aria-expanded', 'false');
    }

    campoNombre.addEventListener('input', () => {
        clearTimeout(temporizadorBusqueda);
        if (solicitudActual) {
            solicitudActual.abort();
        }
        campoMatricula.value = '';

        const nombre = campoNombre.value.trim();
        if (nombre.length < 2) {
            ocultarSugerencias();
            return;
        }

        temporizadorBusqueda = setTimeout(async () => {
            solicitudActual = new AbortController();

            try {
                const parametros = new URLSearchParams({ accion: 'filtrar', nombre });
                const respuesta = await fetch(`buscar.php?${parametros}`, {
                    signal: solicitudActual.signal
                });

                if (!respuesta.ok) {
                    throw new Error('Error al buscar alumnos');
                }

                const alumnos = await respuesta.json();
                listaSugerencias.replaceChildren();

                if (alumnos.length === 0) {
                    const sinResultados = document.createElement('li');
                    sinResultados.className = 'sin-resultados';
                    sinResultados.textContent = 'No se encontraron alumnos.';
                    listaSugerencias.append(sinResultados);
                } else {
                    alumnos.forEach((alumno) => {
                        const elemento = document.createElement('li');
                        const opcion = document.createElement('button');
                        opcion.type = 'button';
                        opcion.setAttribute('role', 'option');
                        opcion.textContent = `${alumno.nombre_alumno} · ${alumno.matricula}`;
                        opcion.addEventListener('click', () => {
                            campoNombre.value = alumno.nombre_alumno;
                            campoMatricula.value = alumno.matricula;
                            ocultarSugerencias();
                        });
                        elemento.append(opcion);
                        listaSugerencias.append(elemento);
                    });
                }

                listaSugerencias.classList.add('visible');
                campoNombre.setAttribute('aria-expanded', 'true');
            } catch (error) {
                if (error.name !== 'AbortError') {
                    ocultarSugerencias();
                }
            }
        }, 250);
    });

    document.addEventListener('click', (evento) => {
        if (!evento.target.closest('.campo-nombre')) {
            ocultarSugerencias();
        }
    });
</script>
</body>

</html>
