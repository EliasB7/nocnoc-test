<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backend de la Aplicación</title>
</head>

<body>

    <h1>Backend de la Aplicación</h1>

    <p>Este es el backend de la aplicación, diseñado para proporcionar una API RESTful para la gestión de tareas y
        usuarios.</p>

    <h2>Instalación</h2>

    <ol>
        <li>Clona este repositorio en tu máquina local:</li>
        <pre><code>git clone &lt;url-del-repositorio&gt;</code></pre>

        <li>Instala las dependencias utilizando Composer:</li>
        <pre><code>composer install</code></pre>

        <li>Copia el archivo <code>.env.example</code> y renómbralo a <code>.env</code>. Luego, configura tus variables
            de entorno según sea necesario, especialmente para la conexión a la base de datos.</li>

        <li>Genera una clave de aplicación única utilizando el comando:</li>
        <pre><code>php artisan key:generate</code></pre>

        <li>Ejecuta las migraciones de la base de datos para crear las tablas necesarias:</li>
        <pre><code>php artisan migrate</code></pre>

        <li>Es escencial que ejecutes este comando para generar un usuario superadmin, que utilizaras para ingresar a la
            pagina por primera vez, luego dentro podras crear otros usuarios superadmin:</li>
        <pre><code>php artisan db:seed</code></pre>

        <h2>Credenciales de Usuario Superadmin</h2>

        <p>A continuación se muestran las credenciales de inicio de sesión para el usuario superadmin:</p>

        <ul>
            <li><strong>Nombre de usuario:</strong> admin</li>
            <li><strong>Correo electrónico:</strong> admin@example.com</li>
            <li><strong>Contraseña:</strong> admin (Asegúrate de cambiarla después del primer inicio de sesión)</li>
        </ul>

        <p>Estas credenciales te permitirán acceder a todas las funcionalidades de superadmin en la aplicación.</p>

    </ol>

</body>

</html>
