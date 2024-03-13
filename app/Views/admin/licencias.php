<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Listado de Licencias</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nombre</th>
                        <th>Días</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí iría el código PHP para mostrar cada licencia en la tabla -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
