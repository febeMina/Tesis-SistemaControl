<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Detalles del registro diariooo - <?= esc($registro->fecha) ?></h2>
    <h5>Familias beneficiadas: <?= esc($registro->familiasBeneficiadas) ?></h5>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Grado</th>
                <th>Docente</th>
                <th>Cantidad de niños</th>
                <th>Cantidad de niñas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $detalle): ?>
                <tr>
                    <td><?= esc($detalle->nombre_grado ?? 'No disponible') ?></td>
                    <td><?= esc($detalle->nombre_docente) ?></td>
                    <td><?= esc($detalle['cantidadNinos']) ?></td>
                    <td><?= esc($detalle['cantidadNinas']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="<?= base_url('public/registro-diario') ?>" class="btn btn-primary">Regresar</a>
</div>

<?= $this->endSection() ?>
