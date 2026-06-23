CREATE DATABASE IF NOT EXISTS Finca_Raiz_Pro;
USE Finca_Raiz_Pro;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS historial_estado_inmueble;
DROP TABLE IF EXISTS favoritos;
DROP TABLE IF EXISTS visitas;
DROP TABLE IF EXISTS imagenes_inmueble;
DROP TABLE IF EXISTS solicitudes;
DROP TABLE IF EXISTS solicitudes_credito;
DROP TABLE IF EXISTS inmuebles;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS tipo_cliente;
DROP TABLE IF EXISTS tipo_usuario;
DROP TABLE IF EXISTS tipo_inmueble;
DROP TABLE IF EXISTS tipo_negocio;

SET FOREIGN_KEY_CHECKS = 1;





CREATE TABLE tipo_usuario (
    id_tipo_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE tipo_cliente (
    id_tipo_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE tipo_inmueble (
    id_tipo_inmueble INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE tipo_negocio (
    id_tipo_negocio INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo VARCHAR(50) NOT NULL UNIQUE
);





CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,

    usuario VARCHAR(50) NOT NULL UNIQUE,

    correo VARCHAR(100) NOT NULL UNIQUE,

    contrasena VARCHAR(255) NOT NULL,

    id_tipo_usuario INT,

    estado ENUM('activo','inactivo','bloqueado') DEFAULT 'activo',

    token_recuperacion VARCHAR(255) NULL,

    ultimo_acceso TIMESTAMP NULL,

    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_usuario_tipo
    FOREIGN KEY (id_tipo_usuario)
    REFERENCES tipo_usuario(id_tipo_usuario)
    ON DELETE SET NULL
    ON UPDATE CASCADE
);





CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,

    p_nombre VARCHAR(50) NOT NULL,

    s_nombre VARCHAR(50),

    p_apellido VARCHAR(50) NOT NULL,

    s_apellido VARCHAR(50),

    celular VARCHAR(20) NOT NULL,

    correo VARCHAR(100) UNIQUE NOT NULL,

    direccion VARCHAR(150),

    ciudad VARCHAR(100),

    documento VARCHAR(30) UNIQUE,

    fecha_nacimiento DATE,

    id_usuario INT,

    id_tipo_cliente INT,

    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_cliente_usuario
    FOREIGN KEY (id_usuario)
    REFERENCES usuarios(id_usuario)
    ON DELETE SET NULL
    ON UPDATE CASCADE,

    CONSTRAINT fk_cliente_tipo
    FOREIGN KEY (id_tipo_cliente)
    REFERENCES tipo_cliente(id_tipo_cliente)
    ON DELETE SET NULL
    ON UPDATE CASCADE
);





CREATE TABLE inmuebles (
    id_inmueble INT AUTO_INCREMENT PRIMARY KEY,

    titulo VARCHAR(150) NOT NULL,

    id_tipo_inmueble INT,

    id_tipo_negocio INT,

    direccion VARCHAR(150),

    ciudad VARCHAR(100),

    barrio VARCHAR(100),

    estrato INT,

    valor DECIMAL(14,2),

    administracion DECIMAL(12,2),

    area DECIMAL(10,2),

    habitaciones INT DEFAULT 0,

    banos INT DEFAULT 0,

    garajes INT DEFAULT 0,

    antiguedad INT,

    descripcion TEXT,

    latitud DECIMAL(10,7),

    longitud DECIMAL(10,7),

    estado ENUM(
        'disponible',
        'vendido',
        'arrendado',
        'reservado',
        'pausado'
    ) DEFAULT 'disponible',

    id_asesor INT,

    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_inmueble_tipo
    FOREIGN KEY (id_tipo_inmueble)
    REFERENCES tipo_inmueble(id_tipo_inmueble),

    CONSTRAINT fk_negocio_tipo
    FOREIGN KEY (id_tipo_negocio)
    REFERENCES tipo_negocio(id_tipo_negocio),

    CONSTRAINT fk_asesor
    FOREIGN KEY (id_asesor)
    REFERENCES usuarios(id_usuario)
);





CREATE TABLE imagenes_inmueble (
    id_imagen INT AUTO_INCREMENT PRIMARY KEY,

    id_inmueble INT,

    url_imagen TEXT NOT NULL,

    CONSTRAINT fk_imagen_inmueble
    FOREIGN KEY (id_inmueble)
    REFERENCES inmuebles(id_inmueble)
    ON DELETE CASCADE
);





CREATE TABLE solicitudes (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,

    id_cliente INT,

    id_inmueble INT,

    tipo_solicitud ENUM(
        'compra',
        'arriendo',
        'visita'
    ),

    mensaje TEXT,

    estado ENUM(
        'pendiente',
        'aprobada',
        'rechazada'
    ) DEFAULT 'pendiente',

    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_solicitud_cliente
    FOREIGN KEY (id_cliente)
    REFERENCES clientes(id_cliente)
    ON DELETE CASCADE,

    CONSTRAINT fk_solicitud_inmueble
    FOREIGN KEY (id_inmueble)
    REFERENCES inmuebles(id_inmueble)
    ON DELETE CASCADE
);





CREATE TABLE solicitudes_credito (
    id_credito INT AUTO_INCREMENT PRIMARY KEY,

    id_cliente INT,

    ingresos DECIMAL(12,2),

    empresa VARCHAR(100),

    tipo_credito VARCHAR(100),

    estado ENUM(
        'pendiente',
        'aprobado',
        'rechazado'
    ) DEFAULT 'pendiente',

    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_credito_cliente
    FOREIGN KEY (id_cliente)
    REFERENCES clientes(id_cliente)
    ON DELETE SET NULL
);





CREATE TABLE favoritos (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,

    id_cliente INT,

    id_inmueble INT,

    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_favorito_cliente
    FOREIGN KEY (id_cliente)
    REFERENCES clientes(id_cliente)
    ON DELETE CASCADE,

    CONSTRAINT fk_favorito_inmueble
    FOREIGN KEY (id_inmueble)
    REFERENCES inmuebles(id_inmueble)
    ON DELETE CASCADE
);





CREATE TABLE visitas (
    id_visita INT AUTO_INCREMENT PRIMARY KEY,

    id_cliente INT,

    id_inmueble INT,

    fecha_visita DATETIME,

    comentarios TEXT,

    CONSTRAINT fk_visita_cliente
    FOREIGN KEY (id_cliente)
    REFERENCES clientes(id_cliente),

    CONSTRAINT fk_visita_inmueble
    FOREIGN KEY (id_inmueble)
    REFERENCES inmuebles(id_inmueble)
);





CREATE TABLE historial_estado_inmueble (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,

    id_inmueble INT,

    estado_anterior VARCHAR(50),

    estado_nuevo VARCHAR(50),

    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_historial_inmueble
    FOREIGN KEY (id_inmueble)
    REFERENCES inmuebles(id_inmueble)
    ON DELETE CASCADE
);





CREATE INDEX idx_barrio
ON inmuebles(barrio);

CREATE INDEX idx_ciudad
ON inmuebles(ciudad);

CREATE INDEX idx_valor
ON inmuebles(valor);

CREATE INDEX idx_estado
ON inmuebles(estado);





INSERT INTO tipo_usuario (nombre_tipo)
VALUES
('Administrador'),
('Asesor'),
('Cliente');

INSERT INTO tipo_cliente (nombre_tipo)
VALUES
('Compra'),
('Arriendo'),
('Venta'),
('Inversionista');

INSERT INTO tipo_inmueble (nombre_tipo)
VALUES
('Apartamento'),
('Casa'),
('Oficina'),
('Local'),
('Bodega');

INSERT INTO tipo_negocio (nombre_tipo)
VALUES
('Venta'),
('Arriendo');





INSERT INTO usuarios
(
usuario,
correo,
contrasena,
id_tipo_usuario,
estado
)
VALUES
(
'admin',
'admin@fincaraiz.com',
'1234',
1,
'activo'
),
(
'asesor1',
'asesor1@fincaraiz.com',
'1234',
2,
'activo'
),
(
'cliente1',
'cliente1@gmail.com',
'1234',
3,
'activo'
);





INSERT INTO clientes
(
p_nombre,
s_nombre,
p_apellido,
s_apellido,
celular,
correo,
direccion,
ciudad,
documento,
fecha_nacimiento,
id_usuario,
id_tipo_cliente
)
VALUES
(
'Juan',
'Carlos',
'Gomez',
'Perez',
'3101234567',
'juan@gmail.com',
'Calle 10 #20-30',
'Bogota',
'10101010',
'1998-05-10',
3,
1
),
(
'Maria',
'Camila',
'Rodriguez',
'Lopez',
'3209876543',
'maria@gmail.com',
'Carrera 50 #40-20',
'Bogota',
'20202020',
'1995-08-15',
NULL,
2
);





INSERT INTO inmuebles
(
titulo,
id_tipo_inmueble,
id_tipo_negocio,
direccion,
ciudad,
barrio,
estrato,
valor,
administracion,
area,
habitaciones,
banos,
garajes,
antiguedad,
descripcion,
latitud,
longitud,
estado,
id_asesor
)
VALUES
(
'Apartamento moderno en Chapinero',
1,
1,
'Calle 80 #15-20',
'Bogota',
'Chapinero',
5,
450000000,
350000,
95,
3,
2,
1,
5,
'Apartamento moderno cerca a universidades',
4.6486259,
-74.0651042,
'disponible',
2
),
(
'Casa familiar en Suba',
2,
2,
'Carrera 90#120-40',
'Bogota',
'Suba',
4,
2800000,
0,
140,
4,
3,
2,
10,
'Casa amplia ideal para familias',
4.7420430,
-74.0837520,
'disponible',
2
);





INSERT INTO imagenes_inmueble
(id_inmueble, url_imagen)
VALUES
(1, 'img/apartamento1.jpg'),
(1, 'img/apartamento2.jpg'),
(2, 'img/casa1.jpg');





INSERT INTO solicitudes
(
id_cliente,
id_inmueble,
tipo_solicitud,
mensaje
)
VALUES
(
1,
1,
'compra',
'Estoy interesado en este apartamento'
),
(
2,
2,
'arriendo',
'Quiero agendar una visita'
);





INSERT INTO solicitudes_credito
(
id_cliente,
ingresos,
empresa,
tipo_credito,
estado
)
VALUES
(
1,
4500000,
'Bancolombia',
'Hipotecario',
'pendiente'
);





INSERT INTO favoritos
(
id_cliente,
id_inmueble
)
VALUES
(1,1),
(1,2);





INSERT INTO visitas
(
id_cliente,
id_inmueble,
fecha_visita,
comentarios
)
VALUES
(
1,
1,
'2026-05-15 10:00:00',
'Cliente interesado'
);





INSERT INTO historial_estado_inmueble
(
id_inmueble,
estado_anterior,
estado_nuevo
)
VALUES
(
1,
'disponible',
'reservado'
);