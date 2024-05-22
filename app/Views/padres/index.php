<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center">Padres Registrados</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <!-- Botón para agregar un nuevo padre -->
                    <div class="mb-3">
                        <a href="<?= site_url('padres/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"> Agregar</i> <!-- Icono de Material Design Icons -->
                        </a>
                    </div>
                    <!-- Formulario de filtros -->
                    <form method="get" action="<?= site_url('padres') ?>" class="mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" name="nombre_completo" class="form-control" placeholder="Nombre Completo" value="<?= isset($filters['nombre_completo']) ? $filters['nombre_completo'] : '' ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="dui" class="form-control" placeholder="DUI" value="<?= isset($filters['dui']) ? $filters['dui'] : '' ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="telefono" class="form-control" placeholder="Teléfono" value="<?= isset($filters['telefono']) ? $filters['telefono'] : '' ?>">
                            </div>
                            <div class="col-md-2">
                                <select name="estado" class="form-control">
                                    <option value="">Estado</option>
                                    <option value="Activo" <?= isset($filters['estado']) && $filters['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                    <option value="Inactivo" <?= isset($filters['estado']) && $filters['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="genero" class="form-control">
                                    <option value="">Género</option>
                                    <option value="M" <?= isset($filters['genero']) && $filters['genero'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                                    <option value="F" <?= isset($filters['genero']) && $filters['genero'] == 'F' ? 'selected' : '' ?>>Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-start">
                                <button type="submit" class="btn btn-primary me-2 btn-icon">
                                    <i class="mdi mdi-filter"></i> <!-- Icono de Material Design Icons -->
                                </button>
                                <a href="<?= site_url('padres') ?>" class="btn btn-secondary btn-icon">
                                    <i class="mdi mdi-close"></i> <!-- Icono de Material Design Icons -->
                                </a>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre Completo</th>
                                    <th>DUI</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Género</th>
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
                                        <td><?= $padre['Genero'] === 'M' ? 'Masculino' : 'Femenino' ?></td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Acciones">
                                                <a href="<?= site_url('padres/edit/' . $padre['idDatosResponsable']) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <a href="<?= site_url('padres/delete/' . $padre['idDatosResponsable']) ?>" class="btn btn-delete" onclick="return confirm('¿Estás seguro de que deseas eliminar este padre?')">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                                <button onclick="showAlumnosModal(<?= $padre['idDatosResponsable'] ?>)" class="btn btn-add-alumno">
                                                    <i class="mdi mdi-account-multiple"></i> 
                                                </button>
                                            </div>
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

<!-- Modal para mostrar los alumnos asociados al padre -->
<div class="modal fade" id="alumnosModal" tabindex="-1" role="dialog" aria-labelledby="alumnosModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #090066; color: white;">
                <h5 class="modal-title" id="alumnosModalLabel">Alumnos Asociados al Padre</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #f0f0f0;">
                <div id="alumnosContainer"></div>
            </div>
            <div class="modal-footer" style="background-color: #090066;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showAlumnosModal(padreId) {
        $.ajax({
            url: '<?= site_url('padres/getAlumnosAjax/') ?>' + padreId, // Cambiado a 'getAlumnosAjax'
            type: 'GET',
            success: function(response) {
                $('#alumnosContainer').html(response);
                $('#alumnosModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
</script>

<?= $this->endSection() ?>
