<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0; background-color: #090066 !important;">
                    <h3 class="text-center">Nuevo Consumo por Producto</h3>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('consumo/store') ?>" method="post">
                        <div class="form-group">
                            <label for="fecha" style="color: #000;"><i class="fas fa-calendar-alt"></i> Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <div class="form-group">
                            <label for="producto" style="color: #000;"><i class="fas fa-box"></i> Producto</label>
                            <select class="form-control" id="producto" name="producto" required>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?= $producto['id'] ?>"><?= $producto['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descripcion" style="color: #000;"><i class="fas fa-info-circle"></i> Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="fecha_vencimiento" style="color: #000;"><i class="fas fa-calendar-alt"></i> Fecha de Vencimiento</label>
                            <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
                        </div>
                        <div class="form-group">
                            <label for="saldo_inicial" style="color: #000;"><i class="fas fa-balance-scale"></i> Saldo Inicial</label>
                            <input type="number" class="form-control" id="saldo_inicial" name="saldo_inicial" required>
                        </div>
                        <div class="form-group">
                            <label for="salidas" style="color: #000;"><i class="fas fa-arrow-up"></i> Salidas</label>
                            <input type="number" class="form-control" id="salidas" name="salidas" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #090066;">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cargar jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Tu script JavaScript -->
<script>
    $(document).ready(function() {
        $('form').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        var alert = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">';
                        alert += 'El consumo por producto ha sido creado exitosamente.';
                        alert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        alert += '<span aria-hidden="true">&times;</span>';
                        alert += '</button>';
                        alert += '</div>';
                        $(alert).insertBefore($('form'));
                    }
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>
