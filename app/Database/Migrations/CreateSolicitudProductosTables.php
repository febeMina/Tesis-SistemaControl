<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSolicitudProductosTables extends Migration
{
    public function up()
    {
        // Tabla de solicitudes de productos
        $this->forge->addField([
            'idSolicitudProductos' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'Fecha_solicitud' => [
                'type' => 'DATE',
            ],
            'Comida_a_preparar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'responsable_entrega' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'responsable_recibir' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);
        $this->forge->addKey('idSolicitudProductos', true);
        $this->forge->createTable('solicitud_productos');

        // Tabla de productos solicitados
        $this->forge->addField([
            'idProductosSolicitados' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'idSolicitudProductos' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'idProducto' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'cantidad' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey('idProductosSolicitados', true);
        $this->forge->addForeignKey('idSolicitudProductos', 'solicitud_productos', 'idSolicitudProductos', 'CASCADE', 'CASCADE');
        $this->forge->createTable('productos_solicitados');
    }

    public function down()
    {
        $this->forge->dropTable('productos_solicitados');
        $this->forge->dropTable('solicitud_productos');
    }
}
