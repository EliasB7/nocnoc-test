<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Task;

class TaskAttachmentController extends Controller
{
    public function attach(Request $request, $taskId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $task = Task::findOrFail($taskId);
        $user = Auth::user();

        // Verifica si el usuario está autenticado antes de acceder a su ID
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        // Lógica para guardar el archivo adjunto...
        $attachment = new TaskAttachment();
        $attachment->task_id = $taskId;
        $attachment->file_name = $request->file('file')->getClientOriginalName();
        $attachment->file_path = $request->file('file')->store('attachments', 'public'); // Guarda el archivo y devuelve el path
        $attachment->user_id = $user->id; // Asigna el ID del usuario autenticado

        // Guarda el archivo adjunto en la base de datos
        $attachment->save();

        return response()->json(['message' => 'Archivo adjunto con éxito', 'attachment' => $attachment]);
    }

    public function index($taskId)
    {
        // Obtener todos los archivos adjuntos para la tarea especificada
        $attachments = TaskAttachment::where('task_id', $taskId)->get();

        // Devolver los archivos adjuntos como respuesta
        return response()->json(['attachments' => $attachments], 200);
    }

    public function destroy($attachmentId)
    {
        $user = Auth::user(); // Obtener el usuario autenticado
    
        if ($user) {
            $attachment = TaskAttachment::findOrFail($attachmentId);
    
            if ($user->isSuperAdmin() || $user->id == $attachment->user_id) {
    
                Storage::delete($attachment->file_path);
    
                $attachment->delete();
    
                return response()->json(['message' => 'Archivo adjunto eliminado con éxito']);
            } else {
                return response()->json(['error' => 'No tienes permiso para eliminar este archivo adjunto'], 403);
            }
        } else {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
    }
    
    public function download($attachmentId)
    {
        $attachment = TaskAttachment::findOrFail($attachmentId);

        $storagePath = Storage::disk('local')->path('public/attachments/' . $attachment->file_name);

        if (!file_exists($storagePath)) {
            return response()->json(['error' => 'El archivo adjunto no existe en el servidor'], 404);
        }

        return response()->download($storagePath, $attachment->file_name);
    }

    public function findById($attachmentId)
    {
        $attachment = TaskAttachment::findOrFail($attachmentId);

        return response()->json(['attachment' => $attachment]);
    }
}
