# Sistema Administrativo de Ventas y Cotizaciones

Proyecto Laravel para gestionar usuarios, roles, clientes, productos, cotizaciones, ventas y reportes.

## Version instalada

Composer intento instalar `laravel/laravel` 13.2.0, pero esa version requiere PHP `^8.3`. Este equipo tiene PHP 8.2.12, por lo que se instalo Laravel 12.12.2 con `laravel/framework` 12.56.0, la version estable mas reciente compatible con el entorno actual.

## Modulos incluidos en esta base

- Autenticacion con login/logout.
- Roles: Administrador, Usuario Comercial y Usuario de Consulta.
- Migraciones MySQL para `roles`, `usuarios`, `clientes`, `categorias`, `productos`, `cotizaciones`, `detalle_cotizaciones`, `ventas` y `detalle_ventas`.
- Modelos Eloquent con relaciones.
- Controladores iniciales por modulo.
- Dashboard responsivo con menu lateral.
- Vista base de listados con busqueda.
- Seeder con datos de prueba.

## Configuracion

1. Crea la base de datos MySQL:

```sql
CREATE DATABASE ventas_cotizaciones CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Ajusta las credenciales en `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ventas_cotizaciones
DB_USERNAME=root
DB_PASSWORD=tu_password
```

3. Instala dependencias y compila assets:

```bash
composer install
npm install
npm run build
```

4. Ejecuta migraciones y datos de prueba:

```bash
php artisan migrate:fresh --seed
```

5. Inicia el servidor:

```bash
php artisan serve
```

## Usuarios de prueba

Todos usan la contrasena `password`.

- `admin@ventas.local` / Administrador
- `comercial@ventas.local` / Usuario Comercial
- `consulta@ventas.local` / Usuario de Consulta

## Verificacion

```bash
php artisan route:list
php artisan test
```
