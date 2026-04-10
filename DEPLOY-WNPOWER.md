# Deploy en WNPower

Este proyecto se despliega como un solo repositorio Git con dos partes:

- La raiz publica del sitio.
- La app Laravel dentro de `admin/`.

## Estructura esperada en WNPower

Subi el repositorio dentro de la carpeta del sitio, por ejemplo:

- `~/repositories/Pepichis` o la ruta que te cree cPanel al clonar.

El document root del dominio debe apuntar a la carpeta donde quede publicada la raiz de este repo, porque el `index.php` de la raiz carga Laravel desde `admin/`.

## Variables de entorno

Crear `admin/.env` en el servidor tomando como base `admin/.env.example`.

Valores minimos para produccion:

```env
APP_NAME=PepichisAdmin
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tu_base
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

## Primer deploy

Desde la terminal de cPanel o SSH, dentro de `admin/`:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Si WNPower no permite `storage:link`, el sitio puede funcionar igual siempre que no dependas del enlace publico para archivos subidos.

## Deploys siguientes

Cada vez que actualices el repo:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Git en cPanel

1. Crear o elegir el repositorio remoto.
2. En cPanel usar `Git Version Control`.
3. Clonar `https://github.com/KenionWS/Pepichis.git` en una carpeta vacia del hosting.
4. Entrar a la carpeta clonada y correr los comandos del bloque anterior.
5. En cada cambio, hacer `Pull` o `Update from Remote` desde cPanel y volver a ejecutar los comandos de Laravel.
