<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'user';
        $user->save();

        Mail::to($user->email)->send(new WelcomeMail($user));

        Auth::login($user);
    }

    public function registerSuperAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'superadmin';
        $user->save();

        Auth::login($user);

        return redirect(route('secret'));
    }

    public function showSuperAdminRegistrationForm()
    {
        return view('register-superadmin');
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $remember = $request->has('remember') ? true : false;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (empty(Auth::user()->password)) {
                return redirect()->route('password.reset', ['token' => 'token_de_reinicio_aqui']);
            } else {
                $userRole = Auth::user()->role;
                $id = Auth::user()->id;
                exit();
            }
        } else {
            return redirect('login');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }

    public function showForgotPasswordForm()
    {
        return view('forgot-password');
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT ? back()->with(['status' => __($status)]) : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm($token)
    {
        return view('reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user
                ->forceFill([
                    'password' => Hash::make($password),
                ])
                ->save();
        });

        return $status == Password::PASSWORD_RESET ? redirect(route('login'))->with('status', __($status)) : back()->withErrors(['email' => [__($status)]]);
    }
}
