## Guía paso a paso: Chuck Norris Jokes en CakePHP 5

### 1) Crear nuevo proyecto

```bash
cd /home/maximo/repos
composer create-project cakephp/app:^5.0 chuck-jokes
```

### 2) Configurar SQLite

- Edita `config/app_local.php` y en `Datasources.default` usa Sqlite:

```php
'driver' => Cake\Database\Driver\Sqlite::class,
'database' => '/home/maximo/repos/chuck-jokes/tmp/database.sqlite',
'url' => env('DATABASE_URL', null),
```

- Crea el fichero de base de datos:

```bash
mkdir -p /home/maximo/repos/chuck-jokes/tmp
touch /home/maximo/repos/chuck-jokes/tmp/database.sqlite
```

### 3) Migración de tabla `jokes`

```bash
php bin/cake.php bake migration CreateJokes setup:string[255] punchline:string[255] created modified
php bin/cake.php migrations migrate
```

Estructura resultante (SQLite):

```sql
CREATE TABLE jokes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  setup VARCHAR(255) NOT NULL,
  punchline VARCHAR(255) NOT NULL,
  created DATETIME,
  modified DATETIME
);
```

### 4) Modelo y entidad

```bash
php bin/cake.php bake model Jokes --no-test
```

Se crean `src/Model/Table/JokesTable.php` y `src/Model/Entity/Joke.php`.

### 5) Controlador y vista

- Controlador `src/Controller/JokesController.php` con acción `random`:
  - GET: Llama `https://api.chucknorris.io/jokes/random` y muestra el chiste.
  - POST: Guarda `setup` (texto del chiste) y `punchline` vacío.

- Vista `templates/Jokes/random.php` muestra el chiste y un botón para guardar.

### 6) Rutas

En `config/routes.php` añade:

```php
$builder->connect('/jokes/random', ['controller' => 'Jokes', 'action' => 'random']);
```

### 7) Probar la app

```bash
php -S 0.0.0.0:8765 -t webroot
```

Navega a `http://localhost:8765/jokes/random`, pulsa “Guardar en la base de datos”.

### 8) Verificar datos

```bash
sqlite3 /home/maximo/repos/chuck-jokes/tmp/database.sqlite "SELECT id, substr(setup,1,60)||'…' AS setup, created FROM jokes ORDER BY id DESC LIMIT 5;"
```

Listo.


