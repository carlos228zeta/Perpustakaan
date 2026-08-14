<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_type' => ['required', 'in:student,teacher,librarian'],
            'number_id' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);
    }

    protected function create(array $data)
    {
        $roleName = $data['role_type'] ?? 'student';
        $roleId = DB::table('roles')->where('name', $roleName)->value('id');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $roleId,
            'status' => 'active',
        ]);

        if ($roleName === 'student') {
            DB::table('students')->insert([
                'user_id' => $user->id,
                'nis' => $data['number_id'] ?? rand(100000, 999999),
                'phone' => $data['phone'] ?? null,
                'major' => 'PPLG',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($roleName === 'teacher') {
            DB::table('teachers')->insert([
                'user_id' => $user->id,
                'nip' => $data['number_id'] ?? rand(100000000, 999999999),
                'phone' => $data['phone'] ?? null,
                'subject' => 'Umum',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $user;
    }
}
