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
                            <i class="mdi mdi-plus"></i> Agregar<!-- Icono de Material Design Icons -->
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre Completo</th>
                                    <th>DUI</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Sexo</th>
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
                                        <td><?= $padre['Sexo'] === 'M' ? 'Masculino' : 'Femenino' ?></td> <!-- Modificación aquí -->
                                        <td><?= $padre['idAlumno'] === '0' ? 'Activo' : 'Inactivo' ?></td>

                                        <td>
                                            <div class="btn-group" role="group" aria-label="Acciones">
                                                <!-- Agrega el botón de editar -->
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
