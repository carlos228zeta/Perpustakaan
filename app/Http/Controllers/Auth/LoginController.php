<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $demoEmails = [
            'admin@library.test' => 'admin',
            'librarian@library.test' => 'librarian',
            'teacher@library.test' => 'teacher',
            'student@library.test' => 'student',
        ];

        // Demo Account Fallback Handling
        if (array_key_exists($request->email, $demoEmails) && $request->password === 'password') {
            $roleName = $demoEmails[$request->email];
            $roleId = DB::table('roles')->where('name', $roleName)->value('id');

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $user = User::create([
                    'name' => ucfirst($roleName) . ' Demo',
                    'email' => $request->email,
                    'password' => Hash::make('password'),
                    'role_id' => $roleId,
                    'status' => 'active',
                ]);
            } else {
                $user->password = Hash::make('password');
                $user->status = 'active';
                if ($roleId) {
                    $user->role_id = $roleId;
                }
                $user->save();
            }

            Auth::login($user, $request->has('remember'));
            return $this->authenticated($request, $user);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('admin')) {
            return redirect('/admin/dashboard');
        } elseif ($user->hasRole('librarian')) {
            return redirect('/librarian/dashboard');
        } elseif ($user->hasRole('teacher')) {
            return redirect('/teacher/dashboard');
        } elseif ($user->hasRole('student')) {
            return redirect('/student/dashboard');
        }

        return redirect('/dashboard');
    }
}
