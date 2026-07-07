@php($role = $role ?? null)
@php($rolePermissions = $role?->permissions?->pluck('name')->all() ?? [])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ($permissionGroups as $group => $permissions)
        <div class="rounded-lg border border-slate-200 p-4">
            <p class="text-sm font-semibold text-slate-800 mb-2 capitalize">{{ $group }}</p>
            <div class="space-y-1.5">
                @foreach ($permissions as $permission)
                    @php($isChecked = in_array($permission->name, old('permissions', $rolePermissions), true))
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" @checked($isChecked)
                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                        {{ str($permission->name)->after('.') }}
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
