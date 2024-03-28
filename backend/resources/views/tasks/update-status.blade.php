<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Estado de Tarea</title>
</head>

<body>
    <h1>Actualizar Estado de Tarea</h1>
    <form action="{{ route('tasks.updateStatus', ['taskId' => $taskId]) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="status">Nuevo Estado:</label>
        <select name="status" id="status" required>
            {{-- <option value="Pendiente">Pendiente</option>
            <option value="En progreso">En progreso</option>
            <option value="Completada">Completada</option> --}}
        </select>
        <button type="submit">Actualizar Estado</button>
    </form>
</body>

</html>
