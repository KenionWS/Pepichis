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
IMPORT_TOKEN=un_token_largo_y_privado

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tu_base
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

## Si venis desde SQLite y no tenes SSH

El repo no sube `admin/database/database.sqlite`, asi que si queres migrar datos:

1. Sube temporalmente tu archivo local `admin/database/database.sqlite` al mismo path en el servidor.
2. Configura `admin/.env` con MySQL y define `IMPORT_TOKEN`.
3. Abre en el navegador:

```text
https://tu-dominio.com/sqlite-to-mysql.php?token=TU_IMPORT_TOKEN
```

Ese script crea las tablas principales en MySQL y copia:

- `admin_users`
- `producers`
- `attributes`
- `attribute_values`
- `wines`
- `producer_attribute_value`
- `wine_attribute_value`
- `notes`

Cuando termine, elimina `sqlite-to-mysql.php` y el `database.sqlite` del servidor.

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

Si no tenes SSH, probablemente vas a tener que subir manualmente la carpeta local `admin/vendor/` al servidor despues del clone, porque Laravel la necesita para arrancar y el repo no la versiona.

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
