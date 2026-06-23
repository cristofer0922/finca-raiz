<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // ---- Nuevos campos en inmuebles (propiedades) ----
        Schema::table('inmuebles', function (Blueprint $t) {
            if (!Schema::hasColumn('inmuebles','estado_propiedad'))
                $t->enum('estado_propiedad', ['Disponible','Vendida','Reservada','Arrendada'])->default('Disponible')->after('estado');
            if (!Schema::hasColumn('inmuebles','disponible'))
                $t->boolean('disponible')->default(true)->after('estado_propiedad');
            if (!Schema::hasColumn('inmuebles','fecha_publicacion'))
                $t->timestamp('fecha_publicacion')->nullable()->after('disponible');
            if (!Schema::hasColumn('inmuebles','fecha_venta'))
                $t->timestamp('fecha_venta')->nullable()->after('fecha_publicacion');
            if (!Schema::hasColumn('inmuebles','visitas'))
                $t->unsignedInteger('visitas')->default(0)->after('fecha_venta');
            if (!Schema::hasColumn('inmuebles','destacado'))
                $t->boolean('destacado')->default(false)->after('visitas');
            if (!Schema::hasColumn('inmuebles','tour_virtual'))
                $t->string('tour_virtual', 500)->nullable()->after('destacado');
            // video_url, latitud, longitud ya existen en la migración base
        });

        // ---- creditos ----
        Schema::create('creditos', function (Blueprint $t) {
            $t->increments('id_credito');
            $t->unsignedInteger('id_inmueble')->nullable();
            $t->string('nombre_completo', 150);
            $t->string('documento', 30);
            $t->string('correo', 120);
            $t->string('telefono', 30);
            $t->decimal('ingresos_mensuales', 14, 2)->nullable();
            $t->string('tipo_contrato', 80)->nullable();
            $t->string('empresa', 150)->nullable();
            $t->string('banco', 60);
            $t->decimal('valor_propiedad', 14, 2)->nullable();
            $t->decimal('cuota_inicial', 14, 2)->nullable();
            $t->text('comentarios')->nullable();
            $t->enum('estado', ['Pendiente','Aprobado','Rechazado'])->default('Pendiente');
            $t->unsignedInteger('id_agente')->nullable();
            $t->timestamp('fecha_solicitud')->useCurrent();
            $t->timestamp('fecha_decision')->nullable();
            $t->foreign('id_inmueble')->references('id_inmueble')->on('inmuebles')->nullOnDelete();
        });

        // ---- solicitudes_informacion ----
        Schema::create('solicitudes_informacion', function (Blueprint $t) {
            $t->increments('id_solicitud_info');
            $t->unsignedInteger('id_inmueble')->nullable();
            $t->string('nombre', 120);
            $t->string('correo', 120);
            $t->string('telefono', 30);
            $t->text('mensaje')->nullable();
            $t->enum('estado', ['Pendiente','Atendida','Cerrada'])->default('Pendiente');
            $t->timestamp('fecha')->useCurrent();
            $t->foreign('id_inmueble')->references('id_inmueble')->on('inmuebles')->nullOnDelete();
        });

        // ---- agentes ----
        Schema::create('agentes', function (Blueprint $t) {
            $t->increments('id_agente');
            $t->unsignedInteger('id_usuario')->nullable();
            $t->string('nombre', 120);
            $t->string('correo', 120)->unique();
            $t->string('telefono', 30)->nullable();
            $t->string('zona', 120)->nullable();
            $t->boolean('activo')->default(true);
            $t->timestamp('created_at')->useCurrent();
            $t->foreign('id_usuario')->references('id_usuario')->on('usuarios')->nullOnDelete();
        });

        // ---- visitas_propiedades ----
        Schema::create('visitas_propiedades', function (Blueprint $t) {
            $t->increments('id_visita_p');
            $t->unsignedInteger('id_inmueble');
            $t->string('ip', 50)->nullable();
            $t->string('user_agent', 255)->nullable();
            $t->timestamp('fecha')->useCurrent();
            $t->foreign('id_inmueble')->references('id_inmueble')->on('inmuebles')->cascadeOnDelete();
        });

        // ---- historial_estados ----
        Schema::create('historial_estados', function (Blueprint $t) {
            $t->increments('id_historial');
            $t->unsignedInteger('id_inmueble');
            $t->string('estado_anterior', 50)->nullable();
            $t->string('estado_nuevo', 50);
            $t->unsignedInteger('id_usuario')->nullable();
            $t->timestamp('fecha')->useCurrent();
            $t->foreign('id_inmueble')->references('id_inmueble')->on('inmuebles')->cascadeOnDelete();
        });

        // ---- imagenes_propiedad (galería extendida HD) ----
        Schema::create('imagenes_propiedad', function (Blueprint $t) {
            $t->increments('id_img_p');
            $t->unsignedInteger('id_inmueble');
            $t->string('url', 500);
            $t->string('titulo', 150)->nullable();
            $t->integer('orden')->default(0);
            $t->boolean('principal')->default(false);
            $t->foreign('id_inmueble')->references('id_inmueble')->on('inmuebles')->cascadeOnDelete();
        });

        // ---- notificaciones ----
        Schema::create('notificaciones', function (Blueprint $t) {
            $t->increments('id_notificacion');
            $t->unsignedInteger('id_usuario')->nullable();
            $t->string('tipo', 50);
            $t->string('titulo', 150);
            $t->text('mensaje')->nullable();
            $t->string('url', 255)->nullable();
            $t->boolean('leida')->default(false);
            $t->timestamp('fecha')->useCurrent();
        });

        // ---- chat_mensajes ----
        Schema::create('chat_mensajes', function (Blueprint $t) {
            $t->increments('id_mensaje');
            $t->string('sesion', 80)->index();
            $t->enum('rol', ['user','assistant','system'])->default('user');
            $t->text('mensaje');
            $t->timestamp('fecha')->useCurrent();
        });

        // ---- documentos_credito ----
        Schema::create('documentos_credito', function (Blueprint $t) {
            $t->increments('id_documento');
            $t->unsignedInteger('id_credito');
            $t->string('nombre', 150);
            $t->string('url', 500);
            $t->timestamp('fecha')->useCurrent();
            $t->foreign('id_credito')->references('id_credito')->on('creditos')->cascadeOnDelete();
        });

        // ---- transacciones ----
        Schema::create('transacciones', function (Blueprint $t) {
            $t->increments('id_transaccion');
            $t->unsignedInteger('id_inmueble')->nullable();
            $t->unsignedInteger('id_credito')->nullable();
            $t->decimal('monto', 14, 2);
            $t->string('tipo', 50);
            $t->string('referencia', 120)->nullable();
            $t->enum('estado', ['Pendiente','Completada','Cancelada'])->default('Pendiente');
            $t->timestamp('fecha')->useCurrent();
        });

        // ---- api_logs ----
        Schema::create('api_logs', function (Blueprint $t) {
            $t->increments('id_log');
            $t->string('endpoint', 255);
            $t->string('metodo', 10);
            $t->integer('status')->nullable();
            $t->string('ip', 50)->nullable();
            $t->text('payload')->nullable();
            $t->timestamp('fecha')->useCurrent();
        });

        // Inicializar valores de estado_propiedad desde el campo 'estado' existente
        DB::statement("UPDATE inmuebles SET estado_propiedad = CASE
            WHEN estado='vendido' THEN 'Vendida'
            WHEN estado='arrendado' THEN 'Arrendada'
            WHEN estado='reservado' THEN 'Reservada'
            ELSE 'Disponible' END");
        DB::statement("UPDATE inmuebles SET disponible = (estado_propiedad = 'Disponible')");
        DB::statement("UPDATE inmuebles SET fecha_publicacion = COALESCE(fecha_registro, NOW())");
    }

    public function down(): void
    {
        Schema::dropIfExists('api_logs');
        Schema::dropIfExists('transacciones');
        Schema::dropIfExists('documentos_credito');
        Schema::dropIfExists('chat_mensajes');
        Schema::dropIfExists('notificaciones');
        Schema::dropIfExists('imagenes_propiedad');
        Schema::dropIfExists('historial_estados');
        Schema::dropIfExists('visitas_propiedades');
        Schema::dropIfExists('agentes');
        Schema::dropIfExists('solicitudes_informacion');
        Schema::dropIfExists('creditos');
        Schema::table('inmuebles', function (Blueprint $t) {
            foreach (['estado_propiedad','disponible','fecha_publicacion','fecha_venta','visitas','destacado','tour_virtual'] as $c) {
                if (Schema::hasColumn('inmuebles', $c)) $t->dropColumn($c);
            }
        });
    }
};
