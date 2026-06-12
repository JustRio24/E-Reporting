<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Enums\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['role', 'is_active', 'search']);
        $users = $this->userService->getPaginated($filters);
        $roles = UserRole::cases();

        return view('users.index', compact('users', 'roles', 'filters'));
    }

    public function create(): View
    {
        $roles = UserRole::cases();
        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->create($request->validated());

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $userModel = \App\Models\User::findOrFail($id);
        $roles = UserRole::cases();
        return view('users.edit', compact('userModel', 'roles'));
    }

    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        $this->userService->update($id, $request->validated());

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function toggleActive(int $id): RedirectResponse
    {
        // Prevent users from toggling their own active state
        if (auth()->id() === $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $this->userService->toggleActive($id);

        return redirect()->back()->with('success', 'Status user berhasil diubah.');
    }

    public function destroy(int $id): RedirectResponse
    {
        if (auth()->id() === $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $userModel = \App\Models\User::findOrFail($id);

        // Check policy
        $this->authorize('delete', $userModel);

        $userModel->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
