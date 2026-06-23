<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $t) {
            $t->string('clave', 80)->primary();
            $t->text('valor')->nullable();
            $t->timestamp('updated_at')->useCurrent();
        });

        Schema::create('planes', function (Blueprint $t) {
            $t->increments('id_plan');
            $t->string('nombre', 80);
            $t->text('descripcion')->nullable();
            $t->decimal('precio', 12, 2);
            $t->string('moneda', 6)->default('USD');
            $t->integer('duracion_dias')->default(30);
            $t->string('stripe_price_id', 120)->nullable();
            $t->boolean('activo')->default(true);
        });

        Schema::create('suscripciones', function (Blueprint $t) {
            $t->increments('id_suscripcion');
            $t->unsignedInteger('id_usuario');
            $t->unsignedInteger('id_plan');
            $t->string('proveedor', 20)->default('stripe'); // stripe | mercadopago
            $t->string('referencia', 160)->nullable();
            $t->enum('estado', ['Activa','Cancelada','Vencida','Pendiente'])->default('Pendiente');
            $t->timestamp('inicio')->nullable();
            $t->timestamp('fin')->nullable();
        });

        // Ampliamos transacciones para soportar pasarelas
        Schema::table('transacciones', function (Blueprint $t) {
            if (!Schema::hasColumn('transacciones','proveedor')) $t->string('proveedor', 20)->nullable()->after('tipo');
            if (!Schema::hasColumn('transacciones','id_usuario')) $t->unsignedInteger('id_usuario')->nullable()->after('proveedor');
            if (!Schema::hasColumn('transacciones','meta'))      $t->text('meta')->nullable();
        });

        // Bandera de propiedad destacada vía pago
        if (Schema::hasTable('inmuebles') && !Schema::hasColumn('inmuebles','destacado_hasta')) {
            Schema::table('inmuebles', function (Blueprint $t) {
                $t->timestamp('destacado_hasta')->nullable();
            });
        }

        // Seeds básicos
        DB::table('settings')->insert([
            ['clave'=>'whatsapp_number','valor'=>env('WHATSAPP_NUMBER','573000000000')],
            ['clave'=>'whatsapp_mensaje','valor'=>'Hola, estoy interesado en una propiedad de FincaRaízPro.'],
        ]);
        DB::table('planes')->insert([
            ['nombre'=>'Destacar Propiedad 7 días','descripcion'=>'Aparece arriba en listados durante 7 días','precio'=>50000,'moneda'=>'COP','duracion_dias'=>7,'stripe_price_id'=>null,'activo'=>true],
            ['nombre'=>'Plan Agente Premium Mensual','descripcion'=>'Suscripción mensual para agentes','precio'=>29.99,'moneda'=>'USD','duracion_dias'=>30,'stripe_price_id'=>null,'activo'=>true],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
        Schema::dropIfExists('planes');
        Schema::dropIfExists('settings');
        if (Schema::hasColumn('inmuebles','destacado_hasta')) {
            Schema::table('inmuebles', fn(Blueprint $t)=>$t->dropColumn('destacado_hasta'));
        }
        Schema::table('transacciones', function (Blueprint $t) {
            foreach (['proveedor','id_usuario','meta'] as $c) if (Schema::hasColumn('transacciones',$c)) $t->dropColumn($c);
        });
    }
};
