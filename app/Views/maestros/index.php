<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="mt-4">Maestros</h1>
    <a href="/maestros/create" class="btn btn-primary mb-4">Agregar Maestro</a>
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre Completo</th>
                            <th scope="col">NIP</th>
                            <th scope="col">Escalafón</th>
                            <th scope="col">Fecha de Ingreso</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($maestros as $maestro) : ?>
                            <tr>
                                <th scope="row"><?= $maestro['id'] ?></th>
                                <td><?= $maestro['nombre_completo'] ?></td>
                                <td><?= $maestro['nip'] ?></td>
                                <td><?= $maestro['escalafon'] ?></td>
                                <td><?= $maestro['fecha_ingreso'] ?></td>
                                <td><?= $maestro['estado'] ?></td>
                                <td>
                                    <a href="/maestros/edit/<?= $maestro['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                    <a href="/maestros/delete/<?= $maestro['id'] ?>" class="btn btn-sm btn-danger">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
