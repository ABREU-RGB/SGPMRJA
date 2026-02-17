<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function getUsers()
    {
        $users = User::all();
        return DataTables::of($users)->make(true);
    }

    private function handleFileUpload($file, $oldPath, $directory)
    {
        // Delete old file if exists
        if ($oldPath && \Storage::disk('public')->exists($oldPath)) {
            \Storage::disk('public')->delete($oldPath);
        }

        // Upload new file via Storage (stored in storage/app/public/)
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, 'public');
        return $path;
    }

    public function store(StoreUserRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->estado = $request->estado;

        // Manejar la subida del avatar
        if ($request->hasFile('avatar')) {
            $user->avatar = $this->handleFileUpload(
                $request->file('avatar'),
                null,
                'avatars'
            );
        }



        $user->save();

        return response()->json(['success' => 'User created successfully.']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->estado = $request->estado;

        // Manejar la subida del avatar
        if ($request->hasFile('avatar')) {
            $user->avatar = $this->handleFileUpload(
                $request->file('avatar'),
                $user->avatar,
                'avatars'
            );
        }



        $user->save();

        return response()->json(['success' => 'User updated successfully.']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
            \Storage::disk('public')->delete($user->avatar);
        }
        $user->delete();
        return response()->json(['success' => 'User deleted successfully.']);
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'avatar' => $user->avatar ? asset($user->avatar) : null,

            'created_at' => $user->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $user->updated_at->format('d/m/Y H:i:s')
        ]);
    }

    public function reportePdf()
    {
        $users = User::all();
        $pdf = \PDF::loadView('admin.users.reporte_pdf', compact('users'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('usuarios_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Verificar email (AJAX)
     */
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        if (!$email)
            return response()->json(['exists' => false]);
        $exists = User::where('email', $email)->exists();
        return response()->json(['exists' => $exists]);
    }
}

