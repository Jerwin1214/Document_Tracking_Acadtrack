<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordManagementController extends Controller
{
    /**
     * Show the password management page with search & list
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::with(['student', 'teacher'])
            ->when($search, function ($query, $search) {
                $query->where('user_id', 'like', "%{$search}%")
                      ->orWhereHas('student', fn($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"))
                      ->orWhereHas('teacher', fn($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
            })
            ->orderBy('role_id')
            ->paginate(500);

        return view('pages.admin.password-management.index', compact('users', 'search'));
    }

    /**
     * Show dedicated form for resetting password
     */
    public function showPasswordForm(User $user)
{
    $userName = $user->role_id == 1 ? $user->user_id
                : ($user->role_id == 2 && $user->student ? $user->student->first_name.' '.$user->student->last_name
                : ($user->role_id == 3 && $user->teacher ? $user->teacher->salutation.' '.$user->teacher->first_name.' '.$user->teacher->last_name
                : $user->user_id));

    return view('pages.admin.password-management.change-password', compact('user', 'userName'));
}


    /**
     * Update password for a user
     */
    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $userName = $this->getUserDisplayName($user);

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('swal_error', $validator->errors()->first('password'));
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.password.manage')
            ->with('success', 'Password updated successfully for ' . $userName);
    }

    /**
     * Get the display name of the user
     */
    private function getUserDisplayName(User $user)
    {
        return match ($user->role_id) {
            1 => $user->user_id,
            2 => $user->student ? $user->student->first_name.' '.$user->student->last_name : $user->user_id,
            3 => $user->teacher ? trim($user->teacher->salutation.' '.$user->teacher->first_name.' '.$user->teacher->last_name) : $user->user_id,
            default => $user->user_id,
        };
    }
}
