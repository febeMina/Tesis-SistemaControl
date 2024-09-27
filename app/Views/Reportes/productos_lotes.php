<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <!-- Mensajes de éxito, error o advertencia -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">
            <?= session('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger">
            <?= session('error') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->has('inactive')): ?>
        <div class="alert alert-warning">
            <?= session('inactive') ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #4CAF50; border-radius: 10px;">
                    <h4 class="header-title text-center">Reportes de lotes por producto</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0; padding: 20px;">
                    <!-- Formulario de selección de producto -->
                    <div class="form-group">
                        <label for="productoSelect"><strong style="color: black;">Seleccionar producto:</strong></label>
                        <select id="productoSelect" class="form-control">
                            <option value="" selected disabled>Seleccione un producto</option>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?= $producto['idProducto'] ?>"><?= $producto['descripcionProducto'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="button" id="btnGetLotes" class="btn btn-primary">Mostrar lotes</button>

                            <button type="button" id="btnGenerarPDF" class="btn btn-primary">Generar PDF</button>
                        </div>
                    
                    
                    <!-- Tabla de lotes -->
                    <div id="tablaLotes" class="table-responsive mt-4" style="display:none;">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Código de lote</th>
                                    <th>Fechas</th>
                                    <th>Unidades de caja</th>
                                    <th>Unidades por caja</th>
                                    <th>Existencia actual<br>(unidades)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- El contenido de la tabla se llenará con JavaScript -->
                            </tbody>
                        </table>
                        <!-- Botón para generar PDF -->
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Obtener lotes cuando se hace clic en "Mostrar Lotes"
    $('#btnGetLotes').on('click', function() {
        let productoId = $('#productoSelect').val();
        if (productoId) {
            $.ajax({
                url: "getLotes/" + productoId,
                type: 'GET',
                success: function(data) {
                    let response = data;
                    let lotesHtml = '';
                    if (response.length > 0) {
                        response.forEach((lote, index) => {
                            lotesHtml += `<tr>
                                <td>${index + 1}</td>
                                <td>${lote.codigoLote}</td>
                                <td>Ingreso: ${new Date(lote.fechaIngreso).toLocaleDateString('es-ES')}<br>Vencimiento: ${new Date(lote.fechaVencimiento).toLocaleDateString('es-ES')}</td>
                                <td class="text-right">${lote.existenciaCaja}<br>${lote.udmCaja}</td>
                                <td class="text-right">${lote.existenciaIndividual}<br>${lote.udmIndividual}</td>
                                <td class="text-right">${lote.existenciaTotal} u</td>
                            </tr>`;
                        });
                    } else {
                        lotesHtml += `<tr>
                            <td colspan="6" class="text-center">No hay lotes disponibles para este producto.</td>
                        </tr>`;
                    }
                    $('#tablaLotes tbody').html(lotesHtml);
                    $('#tablaLotes').show();
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud:', error);
                    alert('Ocurrió un error al obtener los lotes. Intente nuevamente más tarde.');
                }
            });
        } else {
            alert('Por favor, seleccione un producto.');
        }
    });

    // Generar reporte PDF cuando se hace clic en "Generar PDF"
    $('#btnGenerarPDF').on('click', function() {
        let productoId = $('#productoSelect').val();
        if (productoId) {
            // Redirigir a la ruta de generación de PDF
            window.location.href = "generarReporte/" + productoId;
        } else {
            alert('Por favor, seleccione un producto antes de generar el PDF.');
        }
    });
});
</script>

<?= $this->endSection() ?>
