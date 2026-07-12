<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:users.view', only: ['index', 'show']),
            new Middleware('permission:users.create', only: ['create', 'store']),
            new Middleware('permission:users.update', only: ['edit', 'update']),
            new Middleware('permission:users.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $users = User::query()
            ->with('district')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = "%{$request->string('q')}%";
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('email', 'like', $term);
                });
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->string('role')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'districts' => District::orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
            'freeInstitutions' => Institution::whereNull('owner_user_id')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'role' => $data['role'],
            'age' => $data['age'] ?? null,
            'district_id' => $data['district_id'] ?? null,
            'password' => Hash::make($data['password']),
            'phone_verified_at' => now(),
        ]);

        $user->syncRoles($data['roles'] ?? []);

        if (! empty($data['institution_id'])) {
            Institution::whereKey($data['institution_id'])->update(['owner_user_id' => $user->id]);
        }

        return redirect()->route('admin.users.index')->with('status', 'Foydalanuvchi yaratildi.');
    }

    public function edit(User $user): View
    {
        $user->load('roles', 'institution');

        return view('admin.users.edit', [
            'user' => $user,
            'districts' => District::orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
            'freeInstitutions' => Institution::whereNull('owner_user_id')
                ->orWhere('owner_user_id', $user->id)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validateData($request, $user);

        $user->fill([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'role' => $data['role'],
            'age' => $data['age'] ?? null,
            'district_id' => $data['district_id'] ?? null,
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $user->syncRoles($data['roles'] ?? []);

        // Avvalgi muassasadan bog'lanishni uzib, tanlangan muassasaga biriktiramiz.
        Institution::where('owner_user_id', $user->id)->update(['owner_user_id' => null]);
        if (! empty($data['institution_id'])) {
            Institution::whereKey($data['institution_id'])->update(['owner_user_id' => $user->id]);
        }

        return redirect()->route('admin.users.index')->with('status', 'Foydalanuvchi yangilandi.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'O\'zingizni o\'chira olmaysiz.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'Foydalanuvchi o\'chirildi.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32', Rule::unique('users', 'phone')->ignore($user?->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
            'role' => ['required', Rule::in([User::ROLE_PARENT, User::ROLE_INSTITUTION, User::ROLE_TEACHER, User::ROLE_ADMIN])],
            'age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
            'institution_id' => ['nullable', 'exists:institutions,id'],
        ]);
    }
}
