<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Tarea</title>
</head>

<body>
    <h1>Crear Tarea</h1>
    <form action="{{ route('tasks.create') }}" method="POST">
        @csrf
        <div>
            <label for="title">Título:</label><br>
            <input type="text" id="title" name="title" required>
        </div>
        <div>
            <label for="description">Descripción:</label><br>
            <textarea id="description" name="description" rows="4" required></textarea>
        </div>
        <div>
            <label for="assigned_to">Asignado a:</label><br>
            <select id="assigned_to" name="assigned_to" required>
                <!-- Aquí podrías incluir opciones para seleccionar a quién se asigna la tarea -->
                <option value="1">Usuario 1</option>
                <option value="2">Usuario 2</option>
                <option value="3">Usuario 3</option>
            </select>
        </div>
        <br>
        <button type="submit">Crear Tarea</button>
    </form>
</body>

</html>
