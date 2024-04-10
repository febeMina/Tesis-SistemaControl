<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Padres Registrados</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre Completo</th>
                                <th>DUI</th>
                                <th>Teléfono</th>
                                <th>Estado</th>
                                <th>Sexo</th>
                                <th>ID del Alumno</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($padres as $padre): ?>
                                <tr>
                                    <td><?= $padre['nombreCompleto'] ?></td>
                                    <td><?= $padre['DUI'] ?></td>
                                    <td><?= $padre['telefono'] ?></td>
                                    <td><?= $padre['estado'] ?></td>
                                    <td><?= $padre['Sexo'] ?></td>
                                    <!-- Asegúrate de que la columna 'idAlumno' existe en el array $padre -->
                                    <td><?= $padre['idAlumno'] ?></td>
                                    <td>
                                        <a href="<?= site_url('padres/edit/' . $padre['id']) ?>" class="btn btn-primary btn-sm">Editar</a>
                                        <a href="<?= site_url('padres/delete/' . $padre['id']) ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
