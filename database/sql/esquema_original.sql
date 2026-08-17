-- Esquema original proporcionado como base del sistema.
-- Las migraciones de Laravel (database/migrations) generan estas mismas
-- tablas automáticamente al ejecutar `php artisan migrate`.
-- Este archivo se conserva solo como referencia / para importación manual.

CREATE TABLE contribuyente (
    codigo VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    dni VARCHAR(20),
    direccion VARCHAR(200),
    telefono VARCHAR(30),
    correo VARCHAR(100)
);

CREATE TABLE estadocuenta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_contribuyente VARCHAR(20) NOT NULL,
    anio INT NOT NULL,
    tipo_estado VARCHAR(50),
    monto DECIMAL(12,2),
    importe DECIMAL(12,2),
    codigo_compuesto VARCHAR(255),

    FOREIGN KEY (codigo_contribuyente)
        REFERENCES contribuyente(codigo)
);
