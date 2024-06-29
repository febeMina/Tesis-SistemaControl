<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductosRequisicionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'requisicion_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'producto_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'fecha_vencimiento' => [
                'type' => 'DATE',
            ],
            'cantidad' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('requisicion_id', 'solicitud_productos', 'idSolicitudProductos', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('producto_id', 'productos', 'idProducto', 'CASCADE', 'CASCADE');
        $this->forge->createTable('productos_requisicion');
    }

    public function down()
    {
        $this->forge->dropTable('productos_requisicion');
    }
}
