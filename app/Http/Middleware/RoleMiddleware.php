<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // 1. Ensure roles table exists and is populated
        try {
            $roleCount = DB::table('roles')->count();
            if ($roleCount === 0) {
                DB::table('roles')->insertOrIgnore([
                    ['id' => 1, 'name' => 'admin', 'display_name' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
                    ['id' => 2, 'name' => 'librarian', 'display_name' => 'Petugas Perpustakaan', 'created_at' => now(), 'updated_at' => now()],
                    ['id' => 3, 'name' => 'student', 'display_name' => 'Siswa', 'created_at' => now(), 'updated_at' => now()],
                    ['id' => 4, 'name' => 'teacher', 'display_name' => 'Guru', 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
        } catch (\Throwable $e) {
            // Silently catch if table not yet migrated
        }

        // 2. Auto-heal missing role_id on the user
        if (!$user->role_id || !$user->role) {
            try {
                $targetRoleName = 'student';
                $email = strtolower($user->email ?? '');

                if ($user->id == 1 || str_contains($email, 'admin')) {
                    $targetRoleName = 'admin';
                } elseif (str_contains($email, 'librarian') || str_contains($email, 'petugas')) {
                    $targetRoleName = 'librarian';
                } elseif (str_contains($email, 'teacher') || str_contains($email, 'guru')) {
                    $targetRoleName = 'teacher';
                }

                $roleId = DB::table('roles')->where('name', $targetRoleName)->value('id');
                if ($roleId) {
                    DB::table('users')->where('id', $user->id)->update(['role_id' => $roleId]);
                    $user->role_id = $roleId;
                    $user->load('role');
                }
            } catch (\Throwable $e) {
                // Silently catch
            }
        }

        // Flatten roles if passed as comma-separated or array
        $flatRoles = [];
        foreach ($roles as $r) {
            if (is_string($r) && str_contains($r, ',')) {
                $flatRoles = array_merge($flatRoles, explode(',', $r));
            } else {
                $flatRoles[] = $r;
            }
        }
        $flatRoles = array_map('trim', $flatRoles);

        // 3. Super-Admin bypass: Admin can access any role-protected page
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // 4. If user matches any required role, allow access
        if ($user->hasAnyRole($flatRoles)) {
            return $next($request);
        }

        // 5. Gracefully redirect unauthorized users to their appropriate dashboard instead of 403
        if ($user->hasRole('librarian')) {
            return redirect()->route('librarian.dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        } elseif ($user->hasRole('teacher')) {
            return redirect()->route('teacher.dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('student.dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        }

        // Fallback 403 if role is unrecognized
        abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}
