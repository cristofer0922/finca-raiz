<?php

/*
|--------------------------------------------------------------------------
| IMPORTACIÓN DE CONTROLADORES
|--------------------------------------------------------------------------
| se cargan todos los controladores que utilizarán las rutas.
| Cada controlador contiene la lógica de una sección del sistema.
|
*/

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\CreditoController;
use App\Http\Controllers\InformacionController;
use App\Http\Controllers\ChatIaController;

/*
|--------------------------------------------------------------------------
| CONTROLADORES DEL PANEL ADMINISTRADOR
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\RequestController as AdminRequestController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

/*
|--------------------------------------------------------------------------
| CONTROLADORES DEL PANEL DE AGENTES
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\CreditoController as AgentCreditoController;
use App\Http\Controllers\Agent\InformacionController as AgentInformacionController;
use App\Http\Controllers\Agent\PropiedadController as AgentPropiedadController;

/*
|--------------------------------------------------------------------------
| PÁGINAS PÚBLICAS
|--------------------------------------------------------------------------
| Son las páginas que cualquier visitante puede ver sin iniciar sesión.
|--------------------------------------------------------------------------
*/

// Página principal
Route::get('/', [HomeController::class, 'index'])
->name('home');

// Listado de propiedades
Route::get('/propiedades', [PropertyController::class, 'index'])
->name('propiedades.index');

// Ver una propiedad específica
Route::get('/propiedad/{id}', [PropertyController::class, 'show'])
->name('propiedades.show');

// Propiedades en venta
Route::get('/comprar', [PropertyController::class, 'comprar'])
->name('comprar');

// Propiedades en arriendo
Route::get('/arrendar', [PropertyController::class, 'arrendar'])
->name('arrendar');

// Página Nosotros
Route::get('/nosotros', fn() => view('pages.nosotros'))
->name('nosotros');

// Página Contacto
Route::get('/contacto', fn() => view('pages.contacto'))
->name('contacto');


/*
|--------------------------------------------------------------------------
| APIs JSON
|--------------------------------------------------------------------------
| ACA devuelven información en formato JSON.
| Son utilizadas por JavaScript, aplicaciones móviles o AJAX.
|--------------------------------------------------------------------------
*/

// API para listar propiedades
Route::get('/api/propiedades', [PropertyController::class, 'apiList'])
->name('api.propiedades');

// API para cargar más propiedades
Route::get('/api/propiedades/ver-mas', [PropertyController::class, 'verMas'])
->name('api.propiedades.vermas');

use App\Http\Controllers\AuthApiController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthApiController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| FORMULARIOS DEL SITIO
|--------------------------------------------------------------------------
*/

// Guardar solicitud de compra, arriendo o visita
Route::post('/solicitud', [RequestController::class, 'store'])
    ->name('solicitud.store');

// Guardar solicitud de información
Route::post('/informacion', [InformacionController::class, 'store'])
    ->name('informacion.store');


/*
|--------------------------------------------------------------------------
| CRÉDITOS HIPOTECARIOS
|--------------------------------------------------------------------------
*/

// Mostrar formulario de crédito
Route::get('/credito/{id}', [CreditoController::class, 'form'])
    ->name('credito.form');

// Guardar solicitud de crédito
Route::post('/credito', [CreditoController::class, 'store'])
    ->name('credito.store');


/*
|--------------------------------------------------------------------------
| CHAT IA
|--------------------------------------------------------------------------
| Permite enviar mensajes al asistente de inteligencia artificial.
|--------------------------------------------------------------------------
*/

Route::post('/chat-ia', [ChatIaController::class, 'send'])
    ->name('chat.send');


/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
| Login, registro, recuperación de contraseña y cierre de sesión.
|--------------------------------------------------------------------------
*/

// Mostrar formulario login
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

// Procesar login
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');

// Mostrar registro
Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

// Guardar nuevo usuario
Route::post('/register', [AuthController::class, 'register'])
    ->name('register.post');

// Cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// Recuperar contraseña
Route::get('/forgot', [AuthController::class, 'showForgot'])
    ->name('password.forgot');

// Enviar recuperación
Route::post('/forgot', [AuthController::class, 'forgot'])
    ->name('password.forgot.post');


/*
|--------------------------------------------------------------------------
| PANEL ADMINISTRADOR
|--------------------------------------------------------------------------
| Solo pueden acceder usuarios con rol administrador.
|--------------------------------------------------------------------------
*/

Route::middleware(['admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard principal
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Estadísticas en tiempo real
    Route::get('/dashboard/live', [DashboardController::class, 'live'])
        ->name('dashboard.live');

    /*
    |--------------------------------------------------------------------------
    | CRUD COMPLETOS
    |--------------------------------------------------------------------------
    | Laravel crea automáticamente:
    |
    | index   -> listar
    | create  -> formulario crear
    | store   -> guardar
    | show    -> ver
    | edit    -> editar
    | update  -> actualizar
    | destroy -> eliminar
    |
    */

    Route::resource('propiedades', AdminPropertyController::class);
    Route::resource('clientes', AdminClientController::class);
    Route::resource('usuarios', AdminUserController::class);

    // Gestión de solicitudes
    Route::get('solicitudes', [AdminRequestController::class, 'index'])
        ->name('solicitudes.index');

    Route::post('solicitudes/{id}/aprobar', [AdminRequestController::class, 'aprobar'])
        ->name('solicitudes.aprobar');

    Route::post('solicitudes/{id}/rechazar', [AdminRequestController::class, 'rechazar'])
        ->name('solicitudes.rechazar');

    Route::delete('solicitudes/{id}', [AdminRequestController::class, 'destroy'])
        ->name('solicitudes.destroy');
});


/*
|--------------------------------------------------------------------------
| PANEL DEL AGENTE
|--------------------------------------------------------------------------
| Solo pueden ingresar usuarios con rol agente.
|--------------------------------------------------------------------------
*/

Route::middleware(['agente'])
    ->prefix('agente')
    ->name('agente.')
    ->group(function () {

    // Dashboard agente
    Route::get('/', [AgentDashboardController::class, 'index'])
        ->name('dashboard');

    // Estadísticas en vivo
    Route::get('/live', [AgentDashboardController::class, 'live'])
        ->name('dashboard.live');

    // Créditos
    Route::get('/creditos', [AgentCreditoController::class, 'index']);
    Route::get('/creditos/{id}', [AgentCreditoController::class, 'show']);
    Route::post('/creditos/{id}/aprobar', [AgentCreditoController::class, 'aprobar']);
    Route::post('/creditos/{id}/rechazar', [AgentCreditoController::class, 'rechazar']);

    // Exportaciones
    Route::get('/creditos-reporte/pdf', [AgentCreditoController::class, 'reportePdf']);
    Route::get('/creditos-reporte/excel', [AgentCreditoController::class, 'exportExcel']);

    // Solicitudes de información
    Route::get('/solicitudes-informacion', [AgentInformacionController::class, 'index']);
    Route::post('/solicitudes-informacion/{id}/atender', [AgentInformacionController::class, 'atender']);
    Route::post('/solicitudes-informacion/{id}/cerrar', [AgentInformacionController::class, 'cerrar']);

    // Propiedades asignadas al agente
    Route::get('/propiedades', [AgentPropiedadController::class, 'index']);
});


/*
|--------------------------------------------------------------------------
| INTEGRACIONES EXTERNAS
|--------------------------------------------------------------------------
| Google Maps
| OpenAI
| MercadoPago
| Stripe
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\IntegrationsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;

// Lugares cercanos usando coordenadas
Route::get('/api/cercanas', [IntegrationsController::class, 'cercanas'])
    ->name('api.cercanas');


/*
|--------------------------------------------------------------------------
| PAGOS
|--------------------------------------------------------------------------
*/

// Planes disponibles
Route::get('/planes', [PaymentController::class, 'planes']);

// Mercado Pago
Route::post('/pagos/mp/destacar', [PaymentController::class, 'destacarMP']);

// Stripe
Route::post('/pagos/stripe/suscribir', [PaymentController::class, 'suscribirStripe']);

// Estados del pago
Route::get('/pagos/success', [PaymentController::class, 'success']);
Route::get('/pagos/failure', [PaymentController::class, 'failure']);
Route::get('/pagos/pending', [PaymentController::class, 'pending']);

// Webhooks automáticos
Route::post('/pagos/webhook/mercadopago', [PaymentController::class, 'webhookMercadoPago']);
Route::post('/pagos/webhook/stripe', [PaymentController::class, 'webhookStripe']);

// Historial de pagos
Route::get('/mis-pagos', [PaymentController::class, 'historial']);


/*
|--------------------------------------------------------------------------
| CONFIGURACIÓN ADMINISTRATIVA
|--------------------------------------------------------------------------
*/

Route::middleware(['admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Configuración general
    Route::get('/settings', [AdminSettingsController::class, 'index']);
    Route::put('/settings', [AdminSettingsController::class, 'update']);

    // Generar descripción con IA
    Route::post('/propiedades/{id}/ia-descripcion',
        [IntegrationsController::class, 'generarDescripcion']);

    // Obtener coordenadas automáticamente
    Route::post('/propiedades/{id}/geocode',
        [IntegrationsController::class, 'geocodificar']);
});