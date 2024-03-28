<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adjuntar Archivo</title>
</head>

<body>
    <h1>Adjuntar Archivo a Tarea</h1>
    <form action="{{ route('tasks.attachments.attach', ['taskId' => $taskId]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
        <button type="submit">Adjuntar Archivo</button>
    </form>
</body>

</html>
