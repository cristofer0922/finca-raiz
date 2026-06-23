<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipo_usuario')->insert([
            ['nombre_tipo' => 'Administrador'],
            ['nombre_tipo' => 'Agente'],
            ['nombre_tipo' => 'Asesor'],
            ['nombre_tipo' => 'Cliente'],
        ]);
        DB::table('tipo_cliente')->insert([
            ['nombre_tipo' => 'Compra'],['nombre_tipo' => 'Arriendo'],
            ['nombre_tipo' => 'Venta'],['nombre_tipo' => 'Inversionista'],
        ]);
        DB::table('tipo_inmueble')->insert([
            ['nombre_tipo' => 'Apartamento'],['nombre_tipo' => 'Casa'],
            ['nombre_tipo' => 'Oficina'],['nombre_tipo' => 'Local'],['nombre_tipo' => 'Bodega'],
        ]);
        DB::table('tipo_negocio')->insert([
            ['nombre_tipo' => 'Venta'],['nombre_tipo' => 'Arriendo'],
        ]);

        DB::table('usuarios')->insert([
            ['usuario' => 'admin',   'correo' => 'admin@fincaraiz.com',   'contrasena' => Hash::make('admin123'),   'id_tipo_usuario' => 1, 'estado' => 'activo'],
            ['usuario' => 'agente',  'correo' => 'agente@fincaraiz.com',  'contrasena' => Hash::make('112233'),     'id_tipo_usuario' => 2, 'estado' => 'activo'],
            ['usuario' => 'asesor1', 'correo' => 'asesor@fincaraiz.com',  'contrasena' => Hash::make('asesor123'),  'id_tipo_usuario' => 3, 'estado' => 'activo'],
            ['usuario' => 'cliente1','correo' => 'cliente@fincaraiz.com', 'contrasena' => Hash::make('cliente123'), 'id_tipo_usuario' => 4, 'estado' => 'activo'],
        ]);

        DB::table('agentes')->insert([
            ['id_usuario' => 2, 'nombre' => 'Agente Principal', 'correo' => 'agente@fincaraiz.com', 'telefono' => '3001234567', 'zona' => 'Bogotá', 'activo' => true],
        ]);

        DB::table('clientes')->insert([
            ['p_nombre' => 'Juan', 'p_apellido' => 'Gomez', 'celular' => '3101234567', 'correo' => 'juan@gmail.com', 'ciudad' => 'Bogota', 'documento' => '10101010', 'id_usuario' => 4, 'id_tipo_cliente' => 1],
            ['p_nombre' => 'Maria', 'p_apellido' => 'Rodriguez', 'celular' => '3209876543', 'correo' => 'maria@gmail.com', 'ciudad' => 'Bogota', 'documento' => '20202020', 'id_usuario' => null, 'id_tipo_cliente' => 2],
        ]);

        // Pool de imágenes HD
        $imgs = [
            'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=1600',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1600',
            'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=1600',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1600',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1600',
            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1600',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=1600',
            'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=1600',
            'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=1600',
            'https://images.unsplash.com/photo-1505691938895-1758d7feb511?w=1600',
            'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=1600',
            'https://images.unsplash.com/photo-1583608205776-bfd35f0d9f83?w=1600',
        ];

        $ciudades = [
            ['Bogotá','Chapinero'],['Bogotá','Rosales'],['Bogotá','Cedritos'],['Bogotá','Salitre'],
            ['Bogotá','Usaquén'],['Bogotá','Suba'],['Medellín','El Poblado'],['Medellín','Laureles'],
            ['Cali','Granada'],['Cali','Ciudad Jardín'],['Barranquilla','Riomar'],['Cartagena','Bocagrande'],
        ];

        $createInmueble = function(int $idx, int $tipo, int $negocio) use ($imgs, $ciudades) {
            $cb = $ciudades[$idx % count($ciudades)];
            $isArriendo = $negocio === 2;
            $titulo = ($tipo===2?'Casa':'Apartamento')." ".($isArriendo?'en arriendo':'en venta')." #".($idx+1)." en {$cb[1]}";
            $valor  = $isArriendo ? rand(1500, 9500) * 1000 : rand(280, 2400) * 1000000;
            $id = DB::table('inmuebles')->insertGetId([
                'titulo' => $titulo,
                'id_tipo_inmueble' => $tipo,
                'id_tipo_negocio'  => $negocio,
                'direccion' => 'Cra '.rand(1,80).' #'.rand(1,180).'-'.rand(1,99),
                'ciudad' => $cb[0], 'barrio' => $cb[1],
                'estrato' => rand(3,6),
                'valor' => $valor,
                'administracion' => $isArriendo ? rand(150,650) * 1000 : 0,
                'area' => rand(60, 380),
                'habitaciones' => rand(1,5),
                'banos' => rand(1,4),
                'garajes' => rand(0,3),
                'antiguedad' => rand(0,15),
                'descripcion' => 'Excelente propiedad con acabados de lujo, ubicación privilegiada y todas las comodidades para tu familia.',
                'latitud'  => 4.65 + ($idx * 0.005),
                'longitud' => -74.05 + ($idx * 0.005),
                'estado' => 'disponible',
                'estado_propiedad' => 'Disponible',
                'disponible' => true,
                'fecha_publicacion' => now()->subDays(rand(0,60)),
                'visitas' => rand(0, 250),
                'destacado' => $idx < 4,
                'tour_virtual' => 'https://my.matterport.com/show/?m=zEWsxhZpGba',
                'video_url' => 'https://www.youtube.com/embed/3kjEGr0VWBg',
                'id_asesor' => 2,
            ]);
            // 3 imágenes en tabla legacy + 10 imágenes HD en tabla nueva
            for ($k=0; $k<3; $k++) {
                DB::table('imagenes_inmueble')->insert([
                    'id_inmueble' => $id,
                    'url_imagen'  => $imgs[($idx + $k) % count($imgs)],
                ]);
            }
            for ($k=0; $k<10; $k++) {
                DB::table('imagenes_propiedad')->insert([
                    'id_inmueble' => $id,
                    'url' => $imgs[($idx + $k) % count($imgs)],
                    'titulo' => 'Foto '.($k+1),
                    'orden' => $k,
                    'principal' => $k===0,
                ]);
            }
            return $id;
        };

        // 30 Casas en venta + 30 Apartamentos en arriendo (suficiente para los catálogos)
        for ($i=0; $i<30; $i++) $createInmueble($i, 2, 1); // Casa, Venta
        for ($i=0; $i<30; $i++) $createInmueble($i, 1, 2); // Apto, Arriendo

        DB::table('solicitudes')->insert([
            ['id_cliente' => 1, 'id_inmueble' => 1, 'tipo_solicitud' => 'compra',  'mensaje' => 'Quiero visitarla.', 'estado' => 'pendiente'],
            ['id_cliente' => 2, 'id_inmueble' => 31,'tipo_solicitud' => 'arriendo','mensaje' => 'Necesito moverme pronto.', 'estado' => 'aprobada'],
        ]);

        // Datos de ejemplo para créditos
        DB::table('creditos')->insert([
            ['id_inmueble'=>1,'nombre_completo'=>'Carlos Pérez','documento'=>'1010101010','correo'=>'carlos@example.com','telefono'=>'3001112233','ingresos_mensuales'=>6500000,'tipo_contrato'=>'Indefinido','empresa'=>'ACME S.A.','banco'=>'Bancolombia','valor_propiedad'=>320000000,'cuota_inicial'=>80000000,'comentarios'=>'Solicito tasa preferencial.','estado'=>'Pendiente','fecha_solicitud'=>now()->subDays(2)],
            ['id_inmueble'=>2,'nombre_completo'=>'Laura Ruiz','documento'=>'2020202020','correo'=>'laura@example.com','telefono'=>'3104445566','ingresos_mensuales'=>8500000,'tipo_contrato'=>'Indefinido','empresa'=>'Banca Corp','banco'=>'Davivienda','valor_propiedad'=>500000000,'cuota_inicial'=>120000000,'estado'=>'Aprobado','fecha_solicitud'=>now()->subDays(5),'fecha_decision'=>now()->subDays(2)],
            ['id_inmueble'=>3,'nombre_completo'=>'Andrés Soto','documento'=>'3030303030','correo'=>'andres@example.com','telefono'=>'3209998877','ingresos_mensuales'=>4200000,'tipo_contrato'=>'Prestación de servicios','empresa'=>'Freelance','banco'=>'BBVA','valor_propiedad'=>280000000,'cuota_inicial'=>30000000,'estado'=>'Rechazado','fecha_solicitud'=>now()->subDays(8),'fecha_decision'=>now()->subDays(6)],
        ]);

        DB::table('solicitudes_informacion')->insert([
            ['id_inmueble'=>1,'nombre'=>'Sandra López','correo'=>'sandra@example.com','telefono'=>'3151234567','mensaje'=>'Quisiera más fotos y agendar visita.','estado'=>'Pendiente'],
        ]);
    }
}
