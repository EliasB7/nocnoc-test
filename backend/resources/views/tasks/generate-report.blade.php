<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Reporte de Tareas</title>
</head>

<body>
    <h1>Generar Reporte de Tareas</h1>
    <form action="{{ route('tasks.generateReport') }}" method="GET">
        <label for="start_date">Fecha de Inicio:</label>
        <input type="date" name="start_date" id="start_date" required>
        <label for="end_date">Fecha de Fin:</label>
        <input type="date" name="end_date" id="end_date" required>
        <button type="submit">Generar Reporte</button>
    </form>
</body>

</html>
