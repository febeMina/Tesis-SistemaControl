<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Metas</h4>
                <canvas id="goalsChart" height="200"></canvas>
            </div> 
        </div>
    </div>
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Fondos</h4>
                <canvas id="fundsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/welcome_message/grafico.js"></script>
<?= $this->endSection() ?>