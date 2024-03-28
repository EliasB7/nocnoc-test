<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail; 
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Validation\Rule;
use Dompdf\Dompdf;
use Dompdf\Options;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api')->except('login');
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
    
        if (!($token = auth()->attempt($credentials))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $user = auth()->user();
    
        if ($user->first_login) {
            return response()->json(['message' => 'First login, please change your password'], 200);
        }
    
        $userType = $user->role;
        $userName = $user->name;
    
        return $this->respondWithToken($token, $userType, $userName);
    }
        

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     * @param  string $userType
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $userType, $userName)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user_type' => $userType,
            'userName' => $userName
        ]);
    }
    /**
     * Forgot Password: Send reset password link to the user's email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT ? response()->json(['message' => 'Reset password link sent to your email'], 200) : response()->json(['message' => 'Unable to send reset password link'], 400);
    }

    /**
     * Reset Password: Handle reset password request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user
                ->forceFill([
                    'password' => bcrypt($password),
                ])
                ->save();

            event(new PasswordReset($user));
        });

        return $status == Password::PASSWORD_RESET ? response()->json(['message' => 'Password reset successfully'], 200) : response()->json(['message' => 'Unable to reset password'], 400);
    }

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse


     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Mail::to($user->email)->send(new WelcomeEmail($user));

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    /**
     * Register a new admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerSuperAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'superadmin', 
        ]);
    
        $token = auth()->login($user);
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        return $this->respondWithToken($token, 'superadmin', $request->name);
    }
    
    public function createTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'required|exists:users,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $defaultStatus = 'Pendiente';
    
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'status' => $defaultStatus, 
        ]);
    
        return response()->json(['message' => 'Tarea creada con éxito', 'task' => $task], 201);
    }
    
    public function createComment(Request $request, $taskId)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string',
        ]);

        $task = Task::findOrFail($taskId);
        if (!$task->assigned_to == Auth::id() && !$task->isCreatedBy(Auth::id()) && !Auth::user()->isSuperAdmin()) {
            return response()->json(['error' => 'No tienes permiso para comentar esta tarea'], 403);
        }

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $task = TaskComment::create([
            'comment' => $request->comment,
            'task_id' => $taskId,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Comentario creado con éxito', 'comment' => $comment], 201);
    }

    public function delete($taskId)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $task = Task::find($taskId);

        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }

        if (!$user->isSuperAdmin()) {
            return response()->json(['error' => 'Solo los superadmin pueden eliminar tareas'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Tarea eliminada con éxito']);
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();

        if (!$user->first_login) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }

        $user->password = bcrypt($request->new_password);
        $user->first_login = false;
        $user->save();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }

    public function updateTask(Request $request, $taskId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:todo,in_progress,completed', 
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $task = Task::find($taskId);
    
        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }
    
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status;
        $task->save();
    
        return response()->json(['message' => 'Tarea actualizada exitosamente', 'task' => $task], 200);
    }

    public function generateReport(Request $request)
    {
            $tasks = Task::all();
    
            $view = view('tasks.report', compact('tasks'))->render();
    
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
    
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($view);
    
            $dompdf->render();
    
            return $dompdf->stream('task_report.pdf');
    }
 }