<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="mt-4">Editar Maestro</h1>
    <form action="/maestros/update/<?= $maestro['id'] ?>" method="post">
        <!-- Campos del formulario precargados con los datos del maestro -->
    </form>
</div>
<?= $this->endSection() ?>
