<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->orderByDesc('created_at');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('management')) {
            $query->where('management', $request->input('management'));
        }

        $users = $query->paginate(10)->appends($request->query());

        return view('admin.users.index', [
            'users' => $users,
            'roles' => ['admin', 'user'],
            'managements' => ['aset', 'risiko', 'layanan'],
            'filters' => $request->only(['search', 'role', 'management']),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => ['admin', 'user'],
            'managements' => ['aset', 'risiko', 'layanan'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateUserData($request);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $user): View
    {
        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => ['admin', 'user'],
            'managements' => ['aset', 'risiko', 'layanan'],
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $this->validateUserData($request, $user);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.pengguna.show', $user)->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->id === $user->id) {
            return redirect()->route('admin.pengguna.index')->with('error', 'Admin tidak dapat menghapus akun yang sedang digunakan.');
        }

        $user->delete();

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    protected function validateUserData(Request $request, ?User $user = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $user ? Rule::unique('users', 'email')->ignore($user->id) : 'unique:users,email'],
            'role' => ['required', 'in:admin,user'],
        ];

        if ($request->input('role') === 'admin') {
            $rules['management'] = ['nullable', 'prohibited'];
            $rules['password'] = $user ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'];
        } else {
            $rules['management'] = ['required', 'string', 'in:aset,risiko,layanan'];
            $rules['password'] = $user ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'];
        }

        $validated = $request->validate($rules);

        if ($validated['role'] === 'admin') {
            $validated['management'] = null;
        }

        return $validated;
    }
}
