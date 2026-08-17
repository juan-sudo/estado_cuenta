# Sistema de Consulta de Estado de Cuenta

Proyecto Laravel 11 + Tailwind CSS para que un contribuyente se identifique con su
**DNI y nombre completo**, y luego consulte su **estado de cuenta** (deuda / por pagar / pagado)
buscando por DNI, código o nombre.

## Requisitos

- PHP >= 8.2 con extensiones: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- Composer 2
- Node.js >= 18 y npm
- MySQL 8 (o MariaDB) — también funciona con SQLite si prefieres algo más simple

> **Nota:** este proyecto fue generado con todo el código fuente (modelos, controladores,
> migraciones, vistas, rutas, configuración) ya escrito. Como este entorno de generación no
> tiene acceso a Packagist, **no incluye la carpeta `vendor/`**. Debes instalar las dependencias
> localmente con los pasos de abajo.

## Instalación

```bash
# 1. Entra a la carpeta del proyecto
cd proyecto-estado-cuenta

# 2. Instala dependencias PHP
composer install

# 3. Instala dependencias de frontend
npm install

# 4. Copia el archivo de entorno (ya viene creado un .env, pero verifica los datos de tu BD)
cp .env.example .env   # solo si no existe ya un .env

# 5. Genera la clave de la aplicación
php artisan key:generate

# 6. Configura tu base de datos en el archivo .env
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=estado_cuenta
#    DB_USERNAME=root
#    DB_PASSWORD=

# 7. Crea la base de datos vacía en MySQL, por ejemplo:
mysql -u root -p -e "CREATE DATABASE estado_cuenta CHARACTER SET utf8mb4;"

# 8. Ejecuta las migraciones + datos de ejemplo
php artisan migrate --seed

# 9. Compila los assets de Tailwind
npm run build
# (o npm run dev si vas a desarrollar con hot-reload)

# 10. Levanta el servidor
php artisan serve
```

Abre `http://127.0.0.1:8000` en el navegador.

### Usar SQLite en vez de MySQL (más rápido para probar)

```bash
touch database/database.sqlite
```

Y en `.env` cambia:

```
DB_CONNECTION=sqlite
# comenta o borra las demás variables DB_*
```

## Datos de prueba (seeders)

El seeder `database/seeders/ContribuyenteSeeder.php` crea 3 contribuyentes y
`EstadoCuentaSeeder.php` les asigna varios años de estado de cuenta. Puedes usar estos
datos para probar el login:

| DNI       | Nombre completo               | Código  |
|-----------|--------------------------------|---------|
| 45678912  | Juan Carlos Pérez Rodríguez    | C-0001  |
| 41234567  | María Fernanda Torres Quispe   | C-0002  |
| 48912345  | Luis Alberto Gómez Salazar     | C-0003  |

Al ingresar el DNI y el nombre (basta con una coincidencia parcial del nombre), el sistema
te redirige a `/consulta`, donde puedes buscar por DNI, código o nombre completo y ver:

- Datos del contribuyente.
- Tarjetas resumen: total deuda/por pagar, total pagado, cantidad de registros.
- Tabla detallada por año con badges de color (rojo = deuda/por pagar, verde = pagado).

## Estructura relevante

```
app/
  Http/
    Controllers/
      AuthController.php       -> identificación por DNI + nombre
      ConsultaController.php   -> búsqueda y cálculo de resumen de deuda
    Middleware/
      EnsureContribuyenteIdentificado.php
  Models/
    Contribuyente.php
    EstadoCuenta.php
database/
  migrations/                  -> crea contribuyente y estadocuenta (con FK)
  seeders/                     -> datos de ejemplo
  sql/esquema_original.sql     -> tu script SQL original, como referencia
resources/
  views/
    layouts/app.blade.php
    auth/login.blade.php
    consulta/index.blade.php
routes/web.php
```

## Notas de arquitectura / buenas prácticas aplicadas

- **Separación de responsabilidades**: controladores delgados, lógica de negocio (cálculo de
  resumen de deuda) encapsulada en el controlador y en el modelo (`EstadoCuenta::estaPendiente()`).
- **Eloquent con claves primarias no estándar**: `Contribuyente` usa `codigo` (string) como PK,
  configurado explícitamente (`$incrementing = false`, `$keyType = 'string'`).
- **Scope de búsqueda reutilizable** (`scopeBuscar`) para no repetir lógica de consulta.
- **Middleware dedicado** para proteger la sección de consulta sin depender del sistema de auth
  completo de Laravel (ya que el "login" es una identificación simple por DNI + nombre, no
  autenticación con contraseña).
- **Validación de formularios** con `Validator` y mensajes en español.
- **Vistas Blade + Tailwind** con componentes de utilidades reutilizables (`.btn-primary`,
  `.card`, `.input-field`, etc. en `resources/css/app.css`).
- **Seeders idempotentes** con `updateOrCreate` para poder re-ejecutar `php artisan migrate --seed`
  sin duplicar datos.

## Posibles mejoras futuras

- Exportar el estado de cuenta a PDF.
- Paginación de resultados si un contribuyente tiene muchos años de historial.
- Panel administrativo para dar de alta contribuyentes y estados de cuenta desde la web
  (actualmente se gestionan por seeders / acceso directo a BD).
- Autenticación real (Laravel Breeze/Fortify) si en el futuro se requiere un rol de administrador
  distinto al contribuyente.
