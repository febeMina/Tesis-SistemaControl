<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>¿Estás seguro de que deseas eliminar esta licencia?</h1>
            <form action="<?= base_url('admin/licencias/delete/' . $licencia['id']) ?>" method="post">
                <button type="submit" class="btn btn-danger">Sí, Eliminar</button>
                <a href="<?= base_url('admin/licencias') ?>" class="btn btn-primary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
