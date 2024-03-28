<?php
namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskComment;
use App\Models\TaskAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function delete($taskId)
    {
        $task = Task::findOrFail($taskId);

        // Verifica si el usuario es superadmin antes de eliminar la tarea
        if (!Auth::user()->isSuperAdmin()) {
            return response()->json(['error' => 'Solo los superadmin pueden eliminar tareas'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Tarea eliminada con éxito']);
    }

    public function index()
    {
        $user = Auth::user();
        $tasks = Task::where('assigned_to', $user->id)->get();

        if (!$user->isSuperAdmin()) {
            $tasksFromColleagues = Task::where('assigned_to', '!=', $user->id)->get();
            $tasks = $tasks->merge($tasksFromColleagues);
        }

        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function showCreateForm()
    {
        return view('tasks.create');
    }

    // public function attachFile(Request $request, $taskId)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //     ]);

    //     $task = Task::findOrFail($taskId);

    //     $file = $request->file('file');
    //     $fileName = $file->getClientOriginalName();
    //     $filePath = $file->store('attachments');

    //     $attachment = new TaskAttachment();
    //     $attachment->task_id = $task->id;
    //     $attachment->user_id = auth()->user()->id;
    //     $attachment->file_name = $fileName;
    //     $attachment->file_path = $filePath;
    //     $attachment->save();

    //     return response()->json(['message' => 'Archivo adjunto con éxito', 'attachment' => $attachment]);
    // }

    // public function deleteAttachment($attachmentId)
    // {
    //     $attachment = TaskAttachment::findOrFail($attachmentId);

    //     if (!$attachment->canBeDeletedBy(auth()->user())) {
    //         return response()->json(['error' => 'No tienes permiso para eliminar este archivo adjunto'], 403);
    //     }

    //     Storage::delete($attachment->file_path);

    //     $attachment->delete();

    //     return response()->json(['message' => 'Archivo adjunto eliminado con éxito']);
    // }

    // public function updateTask(Request $request, $taskId)
    // {
    //     $task = Task::findOrFail($taskId);

    //     // Verifica si el usuario tiene permiso para actualizar la tarea
    //     if (!Auth::user()->isSuperAdmin() && $task->assigned_to !== Auth::user()->id) {
    //         return response()->json(['error' => 'No tienes permiso para actualizar esta tarea'], 403);
    //     }

    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'required|string',
    //         'assigned_to' => 'required|exists:users,id',
    //         'status' => 'required|in:pending,in_progress,completed',
    //     ]);

    //     $task->title = $request->input('title');
    //     $task->description = $request->input('description');
    //     $task->assigned_to = $request->input('assigned_to');
    //     $task->status = $request->input('status');

    //     $task->save();

    //     return response()->json(['message' => 'Tarea actualizada con éxito', 'task' => $task]);
    // }
    public function generateReport(Request $request)
    {
        // Obtener las fechas de inicio y fin desde la solicitud
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Obtener las tareas filtradas por las fechas
        $tasks = Task::whereBetween('created_at', [$startDate, $endDate])->get();
    
        // Cargar la vista con las tareas
        $view = view('tasks.report', compact('tasks'));
    
        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
    
        // Crear una instancia de Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($view);
    
        // Renderizar el PDF
        $dompdf->render();
    
        // Devolver el PDF como respuesta
        return $dompdf->stream('task_report.pdf');
    }

    public function getAllTasks()
    {
        $tasks = Task::with(['assignedTo'])->get();

        return response()->json(['tasks' => $tasks]);
    }

    public function getAllUsers()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    public function getTaskById($taskId)
    {
        $task = Task::with('assignedTo')->find($taskId);

        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }

        return response()->json(['task' => $task]);
    }

    public function attachFile(Request $request, $taskId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $task = Task::findOrFail($taskId);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('attachments');

        $attachment = new TaskAttachment();
        $attachment->task_id = $task->id;
        $attachment->user_id = auth()->user()->id;
        $attachment->file_name = $fileName;
        $attachment->file_path = $filePath;
        $attachment->save();

        return response()->json(['message' => 'Archivo adjunto con éxito', 'attachment' => $attachment]);
    }

    public function deleteAttachment($attachmentId)
    {
        $attachment = TaskAttachment::findOrFail($attachmentId);

        if (!$attachment->canBeDeletedBy(auth()->user())) {
            return response()->json(['error' => 'No tienes permiso para eliminar este archivo adjunto'], 403);
        }

        Storage::delete($attachment->file_path);

        $attachment->delete();

        return response()->json(['message' => 'Archivo adjunto eliminado con éxito']);
    }

    public function createComment(Request $request, $taskId)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        // Obtener el usuario autenticado
        $user = Auth::user();

        $comment = new TaskComment();
        $comment->task_id = $taskId;
        $comment->user_id = $user->id; // Acceder al ID del usuario
        $comment->comment = $request->input('comment');
        $comment->save();

        return response()->json(['message' => 'Comentario creado con éxito', 'comment' => $comment]);
    }
}
