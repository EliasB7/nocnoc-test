<h1>Lista de Tareas</h1>

@foreach ($tasks as $task)
    <div>
        <h2>{{ $task->title }}</h2>
        <p>{{ $task->description }}</p>
        <!-- Agrega aquí la lógica para mostrar comentarios y adjuntos -->
    </div>
@endforeach
