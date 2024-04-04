<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
    <!-- Contenido de la página -->
    <div class="container-scroller">
        <!-- Partial para la barra lateral y la barra de navegación -->
        <!-- Aquí va el código para la barra lateral y la barra de navegación -->
        
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <!-- Aquí va el código para el contenido de la página -->
                    <div class="conteiner">
                        <div class="main-content-inner">
                            <!-- data table start -->
                            <div class="col-12 mt-5">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h4 class="header-title text-center">PADRES</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <br>
                                        </div>   
                                        <div class="col-lg-12 col-ml-12 mt-5">
                                            <div class="data-tables">
                                                <table id="example" class="text-center">
                                                    <thead class="bg-primary text-capitalize text-white">
                                                        <tr>
                                                            <th>Nombre Completo</th>
                                                            <th>DUI</th>
                                                            <th>Teléfono</th>
                                                            <th>Estado</th>
                                                            <th>Opciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach($padres as $padre): ?>
                                                        <tr>
                                                            <td class="col-4"><?= $padre->nombreCompleto; ?></td>
                                                            <td class="col-2"><?= $padre->DUI; ?></td>
                                                            <td class="col-2"><?= $padre->telefono; ?></td>
                                                            <td class="col-1"><?= $padre->estado; ?></td>
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-1"></div>
                                                                    <div class="col-2">
                                                                        <button type="submit" class="btn btn-warning">
                                                                            <i class="fa fa-pencil-square-o"></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="col-1"></div>
                                                                    <div class="col-2">
                                                                        <button type="submit" class="btn btn-danger">
                                                                            <i class="fa fa-trash-o"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
