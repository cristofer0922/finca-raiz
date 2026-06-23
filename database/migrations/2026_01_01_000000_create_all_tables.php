<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tipo_usuario', function (Blueprint $t) {
            $t->increments('id_tipo_usuario');
            $t->string('nombre_tipo', 50)->unique();
        });
        Schema::create('tipo_cliente', function (Blueprint $t) {
            $t->increments('id_tipo_cliente');
            $t->string('nombre_tipo', 50)->unique();
        });
        Schema::create('tipo_inmueble', function (Blueprint $t) {
            $t->increments('id_tipo_inmueble');
            $t->string('nombre_tipo', 50)->unique();
        });
        Schema::create('tipo_negocio', function (Blueprint $t) {
            $t->increments('id_tipo_negocio');
            $t->string('nombre_tipo', 50)->unique();
        });

        Schema::create('usuarios', function (Blueprint $t) {
            $t->increments('id_usuario');
            $t->string('usuario', 50)->unique();
            $t->string('correo', 100)->unique();
            $t->string('contrasena', 255);
            $t->unsignedInteger('id_tipo_usuario')->nullable();
            $t->enum('estado', ['activo','inactivo','bloqueado'])->default('activo');
            $t->string('token_recuperacion', 255)->nullable();
            $t->timestamp('ultimo_acceso')->nullable();
            $t->timestamp('fecha_creacion')->useCurrent();
            $t->foreign('id_tipo_usuario')->references('id_tipo_usuario')->on('tipo_usuario')->nullOnDelete()->cascadeOnUpdate();
        });

        Schema::create('clientes', function (Blueprint $t) {
            $t->increments('id_cliente');
            $t->string('p_nombre', 50);
            $t->string('s_nombre', 50)->nullable();
            $t->string('p_apellido', 50);
            $t->string('s_apellido', 50)->nullable();
            $t->string('celular', 20);
            $t->string('correo', 100)->unique();
            $t->string('direccion', 150)->nullable();
            $t->string('ciudad', 100)->nullable();
            $t->string('documento', 30)->unique()->nullable();
            $t->date('fecha_nacimiento')->nullable();
            $t->unsignedInteger('id_usuario')->nullable();
            $t->unsignedInteger('id_tipo_cliente')->nullable();
            $t->timestamp('fecha_registro')->useCurrent();
            $t->foreign('id_usuario')->references('id_usuario')->on('usuarios')->nullOnDelete()->cascadeOnUpdate();
            $t->foreign('id_tipo_cliente')->references('id_tipo_cliente')->on('tipo_cliente')->nullOnDelete()->cascadeOnUpdate();
        });

        Schema::create('inmuebles', function (Blueprint $t) {
            $t->increments('id_inmueble');
            $t->string('titulo', 150);
            $t->unsignedInteger('id_tipo_inmueble')->nullable();
            $t->unsignedInteger('id_tipo_negocio')->nullable();
            $t->string('direccion', 150)->nullable();
            $t->string('ciudad', 100)->nullable()->index();
            $t->string('barrio', 100)->nullable()->index();
            $t->integer('estrato')->nullable();
            $t->decimal('valor', 14, 2)->nullable()->index();
            $t->decimal('administracion', 12, 2)->nullable();
            $t->decimal('area', 10, 2)->nullable();
            $t->integer('habitaciones')->default(0);
            $t->integer('banos')->default(0);
            $t->integer('garajes')->default(0);
            $t->integer('antiguedad')->nullable();
            $t->text('descripcion')->nullable();
            $t->decimal('latitud', 10, 7)->nullable();
            $t->decimal('longitud', 10, 7)->nullable();
            $t->enum('estado', ['disponible','vendido','arrendado','reservado','pausado'])->default('disponible')->index();
            $t->unsignedInteger('id_asesor')->nullable();
            $t->string('video_url', 255)->nullable();
            $t->timestamp('fecha_registro')->useCurrent();
            $t->foreign('id_tipo_inmueble')->references('id_tipo_inmueble')->on('tipo_inmueble');
            $t->foreign('id_tipo_negocio')->references('id_tipo_negocio')->on('tipo_negocio');
            $t->foreign('id_asesor')->references('id_usuario')->on('usuarios');
        });

        Schema::create('imagenes_inmueble', function (Blueprint $t) {
            $t->increments('id_imagen');
            $t->unsignedInteger('id_inmueble');
            $t->text('url_imagen');
            $t->foreign('id_inmueble')->references('id_inmueble')->on('inmuebles')->cascadeOnDelete();
        });

        Schema::create('solicitudes', function (Blueprint $t) {
            $t->increments('id_solicitud');
            $t->unsignedInteger('id_cliente')->nullable();
            $t->unsignedInteger('id_inmueble')->nullable();
            $t->enum('tipo_solicitud', ['compra','arriendo','visita'])->nullable();
            $t->text('mensaje')->nullable();
            $t->enum('estado', ['pendiente','aprobada','rechazada'])->default('pendiente');
            $t->timestamp('fecha')->useCurrent();
            $t->foreign('id_cliente')->references('id_cliente')->on('clientes')->cascadeOnDelete();
            $t->foreign('id_inmueble')->references('id_inmueble')->on('inmuebles')->cascadeOnDelete();
        });

        Schema::create('favoritos', function (Blueprint $t) {
            $t->increments('id_favorito');
            $t->unsignedInteger('id_cliente')->nullable();
            $t->unsignedInteger('id_inmueble')->nullable();
            $t->timestamp('fecha')->useCurrent();
            $t->foreign('id_cliente')->references('id_cliente')->on('clientes')->cascadeOnDelete();
            $t->foreign('id_inmueble')->references('id_inmueble')->on('inmuebles')->cascadeOnDelete();
        });

        Schema::create('visitas', function (Blueprint $t) {
            $t->increments('id_visita');
            $t->unsignedInteger('id_cliente')->nullable();
            $t->unsignedInteger('id_inmueble')->nullable();
            $t->dateTime('fecha_visita')->nullable();
            $t->text('comentarios')->nullable();
            $t->foreign('id_cliente')->references('id_cliente')->on('clientes');
            $t->foreign('id_inmueble')->references('id_inmueble')->on('inmuebles');
        });

        Schema::create('solicitudes_credito', function (Blueprint $t) {
            $t->increments('id_credito');
            $t->unsignedInteger('id_cliente')->nullable();
            $t->decimal('ingresos', 12, 2)->nullable();
            $t->string('empresa', 100)->nullable();
            $t->string('tipo_credito', 100)->nullable();
            $t->enum('estado', ['pendiente','aprobado','rechazado'])->default('pendiente');
            $t->timestamp('fecha')->useCurrent();
            $t->foreign('id_cliente')->references('id_cliente')->on('clientes')->nullOnDelete();
        });

        Schema::create('historial_estado_inmueble', function (Blueprint $t) {
            $t->increments('id_historial');
            $t->unsignedInteger('id_inmueble')->nullable();
            $t->string('estado_anterior', 50)->nullable();
            $t->string('estado_nuevo', 50)->nullable();
            $t->timestamp('fecha')->useCurrent();
            $t->foreign('id_inmueble')->references('id_inmueble')->on('inmuebles')->cascadeOnDelete();
        });

        Schema::create('sessions', function (Blueprint $t) {
            $t->string('id')->primary();
            $t->foreignId('user_id')->nullable()->index();
            $t->string('ip_address', 45)->nullable();
            $t->text('user_agent')->nullable();
            $t->longText('payload');
            $t->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('historial_estado_inmueble');
        Schema::dropIfExists('solicitudes_credito');
        Schema::dropIfExists('visitas');
        Schema::dropIfExists('favoritos');
        Schema::dropIfExists('solicitudes');
        Schema::dropIfExists('imagenes_inmueble');
        Schema::dropIfExists('inmuebles');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('tipo_negocio');
        Schema::dropIfExists('tipo_inmueble');
        Schema::dropIfExists('tipo_cliente');
        Schema::dropIfExists('tipo_usuario');
    }
};
