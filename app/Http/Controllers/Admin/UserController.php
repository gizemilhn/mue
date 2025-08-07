<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
    public function createUser()
    {
        return view('admin.users.create');
    }
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => true
        ]);

        toastr()->closeButton()->addSuccess('Kullanıcı başarıyla eklendi');
        return redirect()->route('admin.users.index');
    }
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'is_active' => 'required|boolean'
        ]);

        $updateData = [
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $validated['is_active']
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);
        toastr()->closeButton()->addSuccess('Kullanıcı başarıyla güncellendi');
        return redirect()->route('admin.users.index');
    }


    public function deactivate(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $user->is_active = 0;
        $user->save();
        toastr()->closeButton()->addSuccess('Kullanıcı hesabı donduruldu.');
        return redirect()->back();
    }

    public function activate(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $user->is_active = 1;
        $user->save();
        toastr()->closeButton()->addSuccess('Kullanıcı hesabı aktif hale getirildi.');
        return redirect()->back();
    }

    public function delete(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();
        toastr()->closeButton()->addSuccess('Kullanıcı silindi.');
        return redirect()->back();
    }


}
