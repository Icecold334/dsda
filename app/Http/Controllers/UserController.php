<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UnitKerja;
use App\Models\LokasiStok;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unitKerjas = UnitKerja::orderBy('nama')->get();
        $lokasiStoks = LokasiStok::with('unitKerja')->orderBy('nama')->get();
        $kecamatans = Kecamatan::orderBy('kecamatan')->get();
        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('unitKerjas', 'lokasiStoks', 'kecamatans', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nip' => 'nullable|integer|unique:users',
            'unit_id' => 'nullable|exists:unit_kerja,id',
            'lokasi_id' => 'nullable|exists:lokasi_stok,id',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'alamat' => 'nullable|string',
            'perusahaan' => 'nullable|string|max:255',
            'no_wa' => 'nullable|string|max:20',
            'provinsi' => 'nullable|integer',
            'kota' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'hak' => 'nullable|string',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'ttd' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userData = $request->except(['password', 'password_confirmation', 'roles', 'ttd', 'foto']);
        $userData['password'] = Hash::make($request->password);

        // Handle file uploads
        if ($request->hasFile('ttd')) {
            $userData['ttd'] = $request->file('ttd')->store('ttd', 'public');
        }

        if ($request->hasFile('foto')) {
            $userData['foto'] = $request->file('foto')->store('foto', 'public');
        }

        $user = User::create($userData);

        // Assign roles
        if ($request->has('roles')) {
            $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
            $user->assignRole($roleNames);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['unitKerja', 'lokasiStok', 'kecamatan', 'roles']);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $unitKerjas = UnitKerja::orderBy('nama')->get();
        $lokasiStoks = LokasiStok::with('unitKerja')->orderBy('nama')->get();
        $kecamatans = Kecamatan::orderBy('kecamatan')->get();
        $roles = Role::orderBy('name')->get();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('users.edit', compact('user', 'unitKerjas', 'lokasiStoks', 'kecamatans', 'roles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'nip' => [
                'nullable',
                'integer',
                Rule::unique('users')->ignore($user->id)
            ],
            'unit_id' => 'nullable|exists:unit_kerja,id',
            'lokasi_id' => 'nullable|exists:lokasi_stok,id',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'alamat' => 'nullable|string',
            'perusahaan' => 'nullable|string|max:255',
            'no_wa' => 'nullable|string|max:20',
            'provinsi' => 'nullable|integer',
            'kota' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'hak' => 'nullable|string',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'ttd' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userData = $request->except(['password', 'password_confirmation', 'roles', 'ttd', 'foto']);

        // Update password only if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Handle file uploads
        if ($request->hasFile('ttd')) {
            // Delete old file if exists
            if ($user->ttd) {
                Storage::disk('public')->delete($user->ttd);
            }
            $userData['ttd'] = $request->file('ttd')->store('ttd', 'public');
        }

        if ($request->hasFile('foto')) {
            // Delete old file if exists
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $userData['foto'] = $request->file('foto')->store('foto', 'public');
        }

        $user->update($userData);

        // Update roles
        if ($request->has('roles')) {
            $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Delete associated files
        if ($user->ttd) {
            Storage::disk('public')->delete($user->ttd);
        }
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Toggle user email verification status
     */
    public function toggleEmailVerification(User $user)
    {
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $message = 'Email verification berhasil dibatalkan.';
        } else {
            $user->update(['email_verified_at' => now()]);
            $message = 'Email berhasil diverifikasi.';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Bulk actions for users
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:verify,unverify,delete,assign_role',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
            'role_id' => 'required_if:action,assign_role|exists:roles,id'
        ]);

        $userIds = $request->users;
        $action = $request->action;
        $processed = 0;

        switch ($action) {
            case 'verify':
                User::whereIn('id', $userIds)->update(['email_verified_at' => now()]);
                $processed = count($userIds);
                $message = "{$processed} user berhasil diverifikasi.";
                break;

            case 'unverify':
                User::whereIn('id', $userIds)->update(['email_verified_at' => null]);
                $processed = count($userIds);
                $message = "Verifikasi {$processed} user berhasil dibatalkan.";
                break;

            case 'delete':
                $users = User::whereIn('id', $userIds)->get();
                foreach ($users as $user) {
                    // Delete associated files
                    if ($user->ttd) {
                        Storage::disk('public')->delete($user->ttd);
                    }
                    if ($user->foto) {
                        Storage::disk('public')->delete($user->foto);
                    }
                    $user->delete();
                    $processed++;
                }
                $message = "{$processed} user berhasil dihapus.";
                break;

            case 'assign_role':
                $role = Role::find($request->role_id);
                $users = User::whereIn('id', $userIds)->get();
                foreach ($users as $user) {
                    $user->assignRole($role->name);
                    $processed++;
                }
                $message = "Role '{$role->name}' berhasil diberikan kepada {$processed} user.";
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Export users to Excel
     */
    public function export(Request $request)
    {
        // This would require PhpSpreadsheet package
        // Implementation depends on your Excel export requirements
        return redirect()->back()->with('info', 'Fitur export akan segera tersedia.');
    }
}
