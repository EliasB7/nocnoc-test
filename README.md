# Backend de la Aplicación

Este repositorio contiene el backend de la aplicación, diseñado para proporcionar una API RESTful para la gestión de tareas y usuarios.

## Instalación

1. Clona este repositorio en tu pc:
    ```sh
    git clone https://github.com/EliasB7/nocnoc-test.git
    ```

2. Instala las dependencias utilizando Composer:
    ```sh
    composer install
    ```

3. Genera una clave de aplicación única utilizando el comando:
    ```sh
    php artisan key:generate
    ```

4. Ejecuta las migraciones de la base de datos para crear las tablas necesarias:
    ```sh
    php artisan migrate
    ```

5. Es esencial que ejecutes este comando para generar un usuario superadmin, que utilizarás para ingresar a la página por primera vez, luego dentro podrás crear otros usuarios superadmin:
    ```sh
    php artisan db:seed
    ```

## Credenciales de Usuario Superadmin

A continuación se muestran las credenciales de inicio de sesión para el usuario superadmin:

- **Nombre de usuario:** admin
- **Correo electrónico:** admin@example.com
- **Contraseña:** admin (Asegúrate de cambiarla después del primer inicio de sesión)

Estas credenciales te permitirán acceder a todas las funcionalidades de superadmin en la aplicación.

# Variables de entorno IMPORTANTES

## Configuración de la base de datos

-DB_CONNECTION=mysql
-DB_HOST=127.0.0.1
-DB_PORT=3306
-DB_DATABASE=mi_base_de_datos
-DB_USERNAME=usuario_db
-DB_PASSWORD=TU PASSWROD DE MYSQL

#Configuración de sendgrid (No te olvides de rellenarlo con tu informacion de sendgrid)

-MAIL_MAILER=smtp
-MAIL_HOST=smtp.mailtrap.io
-MAIL_PORT=2525
-MAIL_USERNAME=null
-MAIL_PASSWORD=null
-MAIL_ENCRYPTION=null
-MAIL_FROM_ADDRESS=null
-MAIL_FROM_NAME="${APP_NAME}"

# Frontend de la aplicacion

Este es un proyecto básico de Vue.js.

## Instalación

1. Colocate sobre la ruta principal

2. Instala las dependencias utilizando npm:
    ```sh
    npm install
    ```

3. Ejecuta el proyecto localmente:
    ```sh
    npm run serve
    ```

4. Abre tu navegador web y ve a `http://localhost:8000` para ver la aplicación en funcionamiento.




