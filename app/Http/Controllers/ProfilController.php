<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user(); // Mendapatkan data pengguna yang login
        // Ambil semua role pengguna yang sedang login
        $roles = $user->getRoleNames(); // Mengembalikan koleksi nama role
        $formattedRoles = [];

        // Format setiap role
        foreach ($roles as $role) {
            $formattedRoles[] = formatRole($role);
        }

        // Gabungkan role yang sudah diformat menjadi string
        $user->formatted_roles = implode(', ', $formattedRoles);

        // Ambil unit_id user yang sedang login
        // $userUnitId = Auth::user()->unit_id;

        // // Cari unit berdasarkan unit_id user
        // $unit = UnitKerja::find($userUnitId);

        // // Tentukan parentUnitId
        // $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        // // Ambil parameter pencarian
        // $search = $request->input('search');

        // // Membuat query berdasarkan kondisi apakah yang login adalah superadmin
        // $Users = User::query()
        //     ->whereNotIn('id', [1, $user->id]);

        // // Jika yang login bukan superadmin (id != 1), tambahkan kondisi `whereHas` untuk filter unit kerja
        // if ($user->id != 1) {
        //     $Users->whereHas('unitKerja', function ($unitQuery) use ($parentUnitId) {
        //         $unitQuery->where('parent_id', $parentUnitId)
        //             ->orWhere('id', $parentUnitId);
        //     });
        // }

        // // Jika ada input pencarian, tambahkan filter berdasarkan kolom
        // if (!empty($search)) {
        //     $Users->where(function ($query) use ($search) {
        //         $query->where('name', 'LIKE', "%$search%")
        //             ->orWhere('nip', 'LIKE', "%$search%")
        //             ->orWhere('email', 'LIKE', "%$search%")
        //             ->orWhere('username', 'LIKE', "%$search%")
        //             ->orWhereHas('unitKerja', function ($subQuery) use ($search) {
        //                 $subQuery->where('nama', 'LIKE', "%$search%");
        //             })
        //             ->orWhereHas('lokasiStok', function ($subQuery) use ($search) {
        //                 $subQuery->where('nama', 'LIKE', "%$search%");
        //             })
        //             ->orWhereHas('roles', function ($roleQuery) use ($search) {
        //                 $roleQuery->where('name', 'LIKE', "%$search%");
        //             });;
        //     });
        // }

        // // Ambil semua pengguna yang sesuai dengan kondisi
        // $Users = $Users->get()->filter(function ($user) {
        //     return !$user->hasRole('guest');
        // });

        // // Format role untuk setiap pengguna
        // foreach ($Users as $userItem) {
        //     $roles = $userItem->getRoleNames(); // Mengembalikan koleksi nama role
        //     $formattedRoles = [];
        //     foreach ($roles as $role) {
        //         $formattedRoles[] = formatRole($role);
        //     }
        //     $userItem->formatted_roles = implode(', ', $formattedRoles);
        // }

        return view('profil.index', compact('user')); // Mengirim data pengguna ke view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe, $id = null)
    {
        return view('profil.create', compact('tipe', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
