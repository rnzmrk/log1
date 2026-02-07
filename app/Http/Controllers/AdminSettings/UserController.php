<?php

namespace App\Http\Controllers\AdminSettings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
                // Only search user_number if column exists
                if (Schema::hasColumn('users', 'user_number')) {
                    $q->orWhere('user_number', 'like', "%{$search}%");
                }
            });
        }

        // Filter by status (only if column exists)
        if ($request->filled('status') && Schema::hasColumn('users', 'status')) {
            $query->where('status', $request->status);
        }

        // Filter by role (only if column exists)
        if ($request->filled('role') && Schema::hasColumn('users', 'role')) {
            $query->where('role', $request->role);
        }

        // Filter by department (only if column exists)
        if ($request->filled('department') && Schema::hasColumn('users', 'department')) {
            $query->where('department', $request->department);
        }

        // Filter by date joined
        if ($request->filled('date_joined')) {
            $days = (int) $request->date_joined;
            $query->where('created_at', '>=', now()->subDays($days));
        }

        // Filter by last login (only if column exists)
        if ($request->filled('last_login') && Schema::hasColumn('users', 'last_login_at')) {
            if ($request->last_login === 'never') {
                $query->whereNull('last_login_at');
            } else {
                $days = (int) $request->last_login;
                $query->where('last_login_at', '>=', now()->subDays($days));
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get statistics (only count columns that exist)
        $stats = [
            'total_users' => User::count(),
            'active_users' => Schema::hasColumn('users', 'status') ? User::where('status', 'Active')->count() : User::count(),
            'admin_users' => Schema::hasColumn('users', 'role') ? User::where('role', 'Administrator')->count() : 0,
            'total_roles' => Schema::hasColumn('users', 'role') ? User::distinct('role')->count() : 1,
        ];

        return view('admin.adminsettings.users-roles', compact('users', 'stats'));
    }

    public function create()
    {
        return view('admin.adminsettings.users-create');
    }

    public function store(Request $request)
    {
        // Build validation rules dynamically based on available columns
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];

        // Add role validation if column exists
        if (Schema::hasColumn('users', 'role')) {
            $rules['role'] = 'required|in:Administrator,Manager,User,Viewer';
        }

        // Add department validation if column exists
        if (Schema::hasColumn('users', 'department')) {
            $rules['department'] = 'nullable|string|max:255';
        }

        // Add status validation if column exists
        if (Schema::hasColumn('users', 'status')) {
            $rules['status'] = 'required|in:Active,Inactive,Pending';
        }

        // Add phone validation if column exists
        if (Schema::hasColumn('users', 'phone')) {
            $rules['phone'] = 'nullable|string|max:20';
        }

        $validated = $request->validate($rules);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.adminsettings.users-roles')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('admin.adminsettings.users-show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.adminsettings.users-edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Build validation rules dynamically based on available columns
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ];

        // Add role validation if column exists
        if (Schema::hasColumn('users', 'role')) {
            $rules['role'] = 'required|in:Administrator,Manager,User,Viewer';
        }

        // Add department validation if column exists
        if (Schema::hasColumn('users', 'department')) {
            $rules['department'] = 'nullable|string|max:255';
        }

        // Add status validation if column exists
        if (Schema::hasColumn('users', 'status')) {
            $rules['status'] = 'required|in:Active,Inactive,Suspended,Pending';
        }

        // Add phone validation if column exists
        if (Schema::hasColumn('users', 'phone')) {
            $rules['phone'] = 'nullable|string|max:20';
        }

        $validated = $request->validate($rules);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.adminsettings.users-roles')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.adminsettings.users-roles')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        // Only toggle status if column exists
        if (!Schema::hasColumn('users', 'status')) {
            return redirect()->route('admin.adminsettings.users-roles')
                ->with('error', 'Status management is not available.');
        }

        if ($user->status === 'Active') {
            $user->status = 'Inactive';
        } elseif ($user->status === 'Inactive') {
            $user->status = 'Active';
        } elseif ($user->status === 'Suspended') {
            $user->status = 'Active';
        } else {
            $user->status = 'Active';
        }

        $user->save();

        return redirect()->route('admin.adminsettings.users-roles')
            ->with('success', 'User status updated successfully.');
    }
}
