<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskComment;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller
{
    public function index($taskId)
    {
        $comments = TaskComment::where('task_id', $taskId)->get();
        return response()->json(['comments' => $comments]);
    }

    public function create(Request $request, $taskId)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $comment = new TaskComment();
        $comment->task_id = $taskId;
        $comment->user_id = Auth::id();
        $comment->comment = $request->input('comment');
        $comment->save();

        return response()->json(['message' => 'Comentario creado con éxito', 'comment' => $comment]);
    }

    public function destroy($commentId)
    {
        $comment = TaskComment::findOrFail($commentId);

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'No tienes permiso para eliminar este comentario'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comentario eliminado con éxito']);
    }
}
