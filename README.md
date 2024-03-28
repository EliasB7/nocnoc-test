# Backend de la Aplicación

Este repositorio contiene el backend de la aplicación, diseñado para proporcionar una API RESTful para la gestión de tareas y usuarios.

## Instalación

1. Clona este repositorio en tu máquina local:
    ```sh
    git clone <url-del-repositorio>
    ```

2. Instala las dependencias utilizando Composer:
    ```sh
    composer install
    ```

3. Copia el archivo `.env.example` y renómbralo a `.env`. Luego, configura tus variables de entorno según sea necesario, especialmente para la conexión a la base de datos.

4. Genera una clave de aplicación única utilizando el comando:
    ```sh
    php artisan key:generate
    ```

5. Ejecuta las migraciones de la base de datos para crear las tablas necesarias:
    ```sh
    php artisan migrate
    ```

6. Es esencial que ejecutes este comando para generar un usuario superadmin, que utilizarás para ingresar a la página por primera vez, luego dentro podrás crear otros usuarios superadmin:
    ```sh
    php artisan db:seed
    ```

## Credenciales de Usuario Superadmin

A continuación se muestran las credenciales de inicio de sesión para el usuario superadmin:

- **Nombre de usuario:** admin
- **Correo electrónico:** admin@example.com
- **Contraseña:** admin (Asegúrate de cambiarla después del primer inicio de sesión)

Estas credenciales te permitirán acceder a todas las funcionalidades de superadmin en la aplicación.
