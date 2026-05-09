# Sistema Administrativo de Ventas y Cotizaciones

Proyecto Laravel para gestionar usuarios, roles, clientes, productos, cotizaciones, ventas y reportes.

## Publicarlo en internet

Como te pidieron entregar con MySQL, la forma mas sencilla para esta entrega es desplegarlo en Railway con una base de datos MySQL separada y persistente.

### Opcion recomendada: Railway + MySQL

1. Sube estos cambios a tu repositorio de GitHub.
2. En Railway crea un proyecto nuevo y elige **Deploy from GitHub repo**.
3. Conecta este repositorio y despliega el servicio web.
4. En el mismo proyecto agrega una base de datos **MySQL** desde **+ New**.
5. En el servicio web configura las variables de entorno indicadas abajo.
6. Railway ejecutara automaticamente el script de pre-deploy para correr migraciones y seeders.
7. En **Settings > Networking** genera el dominio publico y abre la URL.

### Variables para Railway

Agrega estas variables en el servicio web:

```env
APP_NAME="Sistema Administrativo de Ventas y Cotizaciones"
APP_ENV=production
APP_DEBUG=false
APP_KEY=pega_aqui_el_resultado_de_php_artisan_key_generate_show
APP_URL=https://tu-dominio-publico.up.railway.app
LOG_CHANNEL=stderr
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
QUEUE_CONNECTION=sync
CACHE_STORE=file
SESSION_DRIVER=file
MAIL_MAILER=log
```

### Como queda la demo

- La aplicacion usa MySQL real, no SQLite.
- En el primer despliegue se ejecutan migraciones y seeders automaticamente.
- La base queda persistente dentro del servicio MySQL de Railway.
- La app queda lista con usuarios de prueba para que tu maestro pueda iniciar sesion.

### Credenciales de acceso

Todos usan la contrasena `password`.

- `admin@ventas.local` / Administrador
- `comercial@ventas.local` / Usuario Comercial
- `consulta@ventas.local` / Usuario de Consulta

### Nota importante

Esta configuracion esta pensada para una demo escolar. El seeder usa `updateOrCreate`, asi que al volver a desplegar se mantienen los registros existentes y se reponen los datos base si faltan.

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

## Verificacion

```bash
php artisan route:list
php artisan test
```
