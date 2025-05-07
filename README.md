# Proyecto Backend de Posts con Laravel

## Descripción
Este es un proyecto backend desarrollado con **Laravel**, el framework de PHP más popular y robusto.
Incluye controladores, modelos, migraciones y configuración de base de datos.

---

## Requisitos

Antes de comenzar, asegúrate de tener instalados los siguientes requisitos:

- **PHP** >= 8.1
- **Composer** (https://getcomposer.org/)
- **Laravel** (se instalará con Composer)
- **Mysql** Servidor de bases de datos
- **Node.js y npm** (opcional, si se usa Laravel Mix para assets)

---

## Instalación

1. Clonar el repositorio:

   ```sh
   git clone https://github.com/sebas124/Registro_Mascotas.git
   cd tuproyecto
   ```

2. Instalar dependencias de PHP con Composer:

   ```sh
   composer install
   ```

3. Copiar el archivo de configuración:

   ```sh
   cp .env.example .env
   ```

4. Configurar la base de datos en el archivo `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=registro_mascotas
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

5. Ejecutar migraciones para crear las tablas:

   ```sh
   php artisan migrate
   ```

6. Ejecutar seeders, Esto ejecuta todas las migraciones y luego carga los seeders para las tablas people, pets, y otros datos necesarios.

   ```sh
      artisan migrate:fresh --seed
   ```
---

7. Opcional: clave para firmar tokens JWT:

   ```sh
      KEY_ACCESS=tu_clave_secreta
   ```
   Puedes usar una cadena generada como:
   php -r "echo base64_encode(random_bytes(32));"


8. Autenticación: 

   Endpoint: POST /api/register

   BODY:
   {
      "name": "Sebastián",
      "email": "sebastian@example.com",
      "password": "12345678"
   }


   Endpoint: POST /api/login

   BODY:
   {
      "email": "sebastian@example.com",
      "password": "12345678"
   }


   RESPUESTA:
   {
      "success": true,
      "token": "eyJ0eXAiOiJKV1QiLCJh..."
   }

   Usa este token en Postman para llamadas protegidas con el encabezado:
   Authorization: Bearer TU_TOKEN

## Ejecucción de peticiones

 Postman
   Importa la colección incluida en /prueba_registro_mascotas.postman_collection.json.

   Ajusta el entorno si es necesario para apuntar a http://127.0.0.1:8000.

   Ejecuta las peticiones de register, login, CRUD personas y CRUD mascotas.


## Ejecución del Servidor

Para iniciar el servidor de desarrollo de Laravel, ejecuta:

```sh
php artisan serve
```

El backend estará disponible en: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Comandos útiles

- **Ver todas las rutas registradas:**
  ```sh
  php artisan route:list
  ```
- **Verificar el estado de las migraciones:**
  ```sh
  php artisan migrate:status
  ```
- **Revertir todas las migraciones y volver a correrlas:**
  ```sh
  php artisan migrate:fresh --seed
  ```



