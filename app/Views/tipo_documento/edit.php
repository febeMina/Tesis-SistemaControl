<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0; background-color: #090066 !important;">
                    <h3 class="text-center">Editar Tipo de Documento</h3>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('public/tipo-documento/update/'.$tipoDocumento['idTipoDocumento']) ?>" method="post">
                        <div class="form-group mb-3">
                            <label for="nombre" style="color: #000;"><i class="fas fa-file"></i> Nombre del Tipo de Documento</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= esc($tipoDocumento['nombre']) ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="mascara" style="color: #000;"><i class="fas fa-mask"></i> Máscara</label>
                            <input type="text" class="form-control" id="mascara" name="mascara" value="<?= esc($tipoDocumento['mascara']) ?>" required oninput="validateMascara(this)">
                            <small class="form-text text-muted">Ejemplo de máscara: ####-####-#### (para un formato de 4-4-4 dígitos).</small>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #090066;">Actualizar</button>
                            <a href="<?= base_url('public/tipo-documento') ?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validateMascara(input) {
        // Permitir solo '#' y '-'
        const regex = /[^#-]/g;
        const invalidChars = input.value.match(regex);
        if (invalidChars) {
            // Reemplazar caracteres inválidos con una cadena vacía
            input.value = input.value.replace(regex, '');
        }
    }
</script>

<?= $this->endSection() ?>
