
<?php
$matriculaDuplicada = ($_GET['error'] ?? '') === 'matricula_duplicada';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Alumnos</title>
    <link rel="icon" type="image/png" href="cbtis 258.jfif">
    <link rel="stylesheet" href="altas1.css">
</head>

<script>
function mostrarFoto(input, id) {
    const imagen = document.getElementById(id);

    if (input.files && input.files[0]) {
        const lector = new FileReader();

        lector.onload = function(e) {
            imagen.src = e.target.result;
        };

        lector.readAsDataURL(input.files[0]);
    }
}

function normalizarNombre(input) {
    input.value = input.value
        .trim()
        .toLocaleLowerCase('es-MX')
        .replace(/(^|\s)([a-záéíóúüñ])/g, function (coincidencia, espacio, letra) {
            return espacio + letra.toLocaleUpperCase('es-MX');
        });
}
</script>

<body>

<div class="page-shell">

    <div class="content">

        <div class="hero">
            <span class="tag">Control escolar</span>

            <h2>Registro de Alumnos</h2>

            <p class="subtitle">
                Registra los datos del alumno.
            </p>
        </div>

        <div class="form-panel">

            <div class="contenedor">

                <h3>REGISTRAR ALUMNO</h3>

                <div class="linea"></div>

                <?php if ($matriculaDuplicada): ?>
                    <p class="error-message" role="alert">
                        Ya existe un alumno registrado con esa matrícula.
                    </p>
                <?php endif; ?>

<form action="altas2.php" method="POST" enctype="multipart/form-data">

                    <div class="dato">
                        <label>Matrícula:</label>
                        <input
                            type="text"
                            name="matricula"
                            placeholder="Matrícula"
                            inputmode="numeric"
                            maxlength="14"
                            pattern="[0-9]{1,14}"
                            title="Ingresa únicamente números, hasta 14 dígitos"
                            oninput="this.value = this.value.replace(/\D/g, '')"
                            required
                        >
                    </div>

                    <div class="dato">
                        <label>Nombre:</label>
                        <input
                            type="text"
                            name="nombre"
                            placeholder="Nombre completo"
                            onblur="normalizarNombre(this)"
                            required
                        >
                    </div>

                    <div class="dato">
                        <label>Apellido Paterno:</label>
                        <input
                            type="text"
                            name="apellido_paterno"
                            placeholder="Apellido paterno"
                            onblur="normalizarNombre(this)"
                            required
                        >
                    </div>

                    <div class="dato">
                        <label>Apellido Materno:</label>
                        <input
                            type="text"
                            name="apellido_materno"
                            placeholder="Apellido materno"
                            onblur="normalizarNombre(this)"
                            required
                        >
                    </div>

                    <div class="dato">
                        <label>Semestre y grupo:</label>
                        <input
                            type="text"
                            name="semestre_grupo"
                            placeholder="Ej. 4A"
                            required
                        >
                    </div>

                    <div class="dato">
                        <label for="especialidad">Especialidad:</label>
                        <select id="especialidad" name="especialidad" required>
                            <option value="">Selecciona una especialidad</option>
                            <option value="Mecanica">Mecanica</option>
                            <option value="Programacion">Programacion</option>
                            <option value="Alimentos y Bebidas">Alimentos y Bebidas</option>
                            <option value="Contabilidad">Contabilidad</option>
                            <option value="Hospedaje">Hospedaje</option>
                            <option value="Logistica">Logistica</option>
                            <option value="Ciberseguridad">Ciberseguridad</option>
                            <option value="Inteligencia artificial">Inteligencia artificial</option>
                        </select>
                    </div>

                    <div class="dato">
                        <label>Turno:</label>

                        <select name="turno" required>
                            <option value="">Selecciona un turno</option>
                            <option value="MATUTINO">MATUTINO</option>
                            <option value="VESPERTINO">VESPERTINO</option>
                        </select>
                    </div>

                    <div class="credenciales">

                        <p>
                            CREDENCIAL(ES) DE PERSONA(S) AUTORIZADA(S)
                        </p>

                        <div class="fotos">

                            <div class="credencial">

                                <div class="foto">
                                    <img id="preview1" src="" alt="">

                                    <input
                                        type="file"
                                        name="foto1"
                                        accept="image/*"
                                        onchange="mostrarFoto(this, 'preview1')"
                                    >
                                </div>

                                <input
                                    type="text"
                                    name="autorizado1"
                                    placeholder="Nombre completo"
                                    onblur="normalizarNombre(this)"
                                    required
                                >

                            </div>


                            <div class="credencial">

                                <div class="foto">
                                    <img id="preview2" src="" alt="">

                                    <input
                                        type="file"
                                        name="foto2"
                                        accept="image/*"
                                        onchange="mostrarFoto(this, 'preview2')"
                                    >
                                </div>

                                <input
                                    type="text"
                                    name="autorizado2"
                                    placeholder="Nombre completo"
                                    onblur="normalizarNombre(this)"
                                >

                            </div>


                            <div class="credencial">

                                <div class="foto">
                                    <img id="preview3" src="" alt="">

                                    <input
                                        type="file"
                                        name="foto3"
                                        accept="image/*"
                                        onchange="mostrarFoto(this, 'preview3')"
                                    >
                                </div>

                                <input
                                    type="text"
                                    name="autorizado3"
                                    placeholder="Nombre completo"
                                    onblur="normalizarNombre(this)"
                                >

                            </div>


                            <div class="credencial">

                                <div class="foto">
                                    <img id="preview4" src="" alt="">

                                    <input
                                        type="file"
                                        name="foto4"
                                        accept="image/*"
                                        onchange="mostrarFoto(this, 'preview4')"
                                    >
                                </div>

                                <input
                                    type="text"
                                    name="autorizado4"
                                    placeholder="Nombre completo"
                                    onblur="normalizarNombre(this)"
                                >

                            </div>

                        </div>

                    </div>


                    <div class="botones">

                        <button type="submit">
                            DAR DE ALTA
                        </button>

                        <a
                            href="inicio.php"
                            class="btn-volver"
                        >
                            VOLVER AL INICIO
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>
```
s