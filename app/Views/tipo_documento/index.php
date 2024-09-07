<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">
            <?= session('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center text-white">Tipos de Documento</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <!-- Botón para crear nuevo tipo de documento -->
                    <a href="<?= site_url('public/tipo-documento/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Agregar
                        </a>
                    
                    <!-- Tabla de tipos de documento -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead >
                                <tr>
                                    <th>Nombre</th>
                                    <th>Máscara</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tiposDocumento as $tipo): ?>
                                    <tr>
                                        <td><?= esc($tipo['nombre']) ?></td>
                                        <td><?= esc($tipo['mascara']) ?></td>
                                        <td>
                                            <a href="<?= base_url('public/tipo-documento/edit/'.$tipo['idTipoDocumento']) ?>" class="btn btn-edit">
                                            <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <form action="<?= base_url('public/tipo-documento/delete/'.$tipo['idTipoDocumento']) ?>" method="post" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este tipo de documento?');">
                                                <button type="submit" class="btn btn-delete">
                                                <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
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
</div>

<?= $this->endSection() ?>
