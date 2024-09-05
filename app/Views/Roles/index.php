<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<!-- Contenido de la página -->
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Listado de Roles</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Botón para agregar un nuevo Rol -->
                 
                    
                    <!-- Tabla de maestros -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre Rol</th>
                                    <th>Accesos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($roles as $rol) : ?>
                                    <tr>
                                        <td><b><?= $rol['nombreRol']; ?></td>
                                        <td><?= $rol['descripcion']; ?></td>
                                       
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