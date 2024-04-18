<!-- app/Views/permiso_magisterial/registrar_permiso.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Permiso Magisterial</title>
</head>
<body>
    <h1>Registrar Permiso Magisterial</h1>
    <?php if (session()->has('error')): ?>
        <div style="color: red;"><?= session('error') ?></div>
    <?php endif; ?>
    <?php if (session()->has('success')): ?>
        <div style="color: green;"><?= session('success') ?></div>
    <?php endif; ?>
    <form action="<?= site_url('permiso_magisterial/registrar') ?>" method="post">
 <!-- Corregido el nombre de la ruta -->
        <!-- Campos del formulario para registrar un nuevo permiso -->
        <label for="id_docente">Seleccione un docente:</label>
        <select name="id_docente" id="id_docente">
            <!-- Aquí puedes mostrar opciones de docentes recuperados de la base de datos -->
        </select>
        <br>
        <label for="id_tipo_permiso">Seleccione el tipo de permiso:</label>
        <select name="id_tipo_permiso" id="id_tipo_permiso">
            <!-- Aquí puedes mostrar opciones de tipos de permiso recuperados de la base de datos -->
        </select>
        <br>
        <label for="fecha_inicio">Fecha de inicio:</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio">
        <br>
        <label for="fecha_fin">Fecha de fin:</label>
        <input type="date" name="fecha_fin" id="fecha_fin">
        <br>
        <button type="submit">Registrar Permiso</button>
    </form>
</body>
</html>
