<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = DB::table('students')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->select('students.*', 'classes.name as class_name')
            ->where('students.user_id', $user->id)
            ->first();
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();

        return view('profile.index', compact('user', 'student', 'teacher'));
    }

    public function edit()
    {
        $user = auth()->user();
        $student = DB::table('students')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->select('students.*', 'classes.name as class_name')
            ->where('students.user_id', $user->id)
            ->first();
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();
        $classes = DB::table('classes')->get();
        $majors = DB::table('majors')->orderBy('name')->get();

        return view('profile.edit', compact('user', 'student', 'teacher', 'classes', 'majors'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now(),
        ];

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/avatars');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $updateData['avatar'] = 'uploads/avatars/' . $filename;
        }

        DB::table('users')->where('id', $user->id)->update($updateData);

        if ($request->filled('password')) {
            DB::table('users')->where('id', $user->id)->update([
                'password' => Hash::make($request->password)
            ]);
        }

        if ($user->hasRole('student')) {
            $studentUpdate = [
                'user_id' => $user->id,
                'phone' => $request->phone,
                'updated_at' => now()
            ];
            if ($request->has('class_id')) {
                $studentUpdate['class_id'] = $request->class_id;
            }
            if ($request->has('major')) {
                $studentUpdate['major'] = $request->major;
            }
            
            $existingStudent = DB::table('students')->where('user_id', $user->id)->first();
            if ($existingStudent) {
                DB::table('students')->where('user_id', $user->id)->update($studentUpdate);
            } else {
                $studentUpdate['nis'] = 'NIS-' . $user->id . '-' . rand(1000, 9999);
                $studentUpdate['enrollment_year'] = date('Y');
                $studentUpdate['created_at'] = now();
                DB::table('students')->insert($studentUpdate);
            }
        } elseif ($user->hasRole('teacher')) {
            DB::table('teachers')->where('user_id', $user->id)->update([
                'phone' => $request->phone,
                'updated_at' => now()
            ]);
        }

        return redirect()->route('profile.index')->with('success', 'Profil dan foto Anda berhasil diperbarui!');
    }
}
