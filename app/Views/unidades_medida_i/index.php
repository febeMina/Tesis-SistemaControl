<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row">
        <!-- Columna para Unidades de Medida General -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Unidades de medida: Por caja</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Botón para agregar una nueva unidad de medida general -->
                    <div class="mb-3">
                        <a href="<?= site_url('unidadesporcaja/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Agregar
                        </a>
                    </div>
                    <!-- Tabla de unidades de medida general -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $n = 0;
                                    foreach ($udm_caja as $unidad) : 
                                        $n++;
                                ?>
                                    <tr>
                                        <td><?= $n ?></td>
                                        <td><?= $unidad['nombreCaja'] ?></td>
                                        <td><?= $unidad['estado'] ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= site_url('unidadesporcaja/edit/' . $unidad['idUdmCaja']) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i> <!-- Icono de edición -->
                                                </a>
                                                <a href="<?= site_url('unidadesporcaja/delete/' . $unidad['idUdmCaja']) ?>" class="btn btn-delete">
                                                    <i class="mdi mdi-delete"></i> <!-- Icono de eliminación -->
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php 
                                    endforeach; 

                                    if($n == 0) {
                                        echo '<tr><td colspan="4" class="text-center">No se encontraron registros...</td></tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Columna para Unidades de Medida Individual -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Unidades de medida: Individual</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Botón para agregar una nueva unidad de medida individual -->
                    <div class="mb-3">
                        <a href="<?= site_url('unidadesindividuales/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Agregar
                        </a>
                    </div>
                    <!-- Tabla de unidades de medida individual -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $n = 0;
                                    foreach ($udm_individual as $unidad) :
                                        $n++;
                                ?>
                                    <tr>
                                        <td><?= $n; ?></td>
                                        <td><?= $unidad['nombreIndividual'] ?></td>
                                        <td><?= $unidad['estado'] ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= site_url('unidadesindividuales/edit/' . $unidad['idUdmIndividual']) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i> <!-- Icono de edición -->
                                                </a>
                                                <a href="<?= site_url('unidadesindividuales/delete/' . $unidad['idUdmIndividual']) ?>" class="btn btn-delete">
                                                    <i class="mdi mdi-delete"></i> <!-- Icono de eliminación -->
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php 
                                    endforeach; 
                                    if($n == 0) {
                                        echo '<tr><td colspan="4" class="text-center">No se encontraron registros...</td></tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>