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
    public function index()
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
        $userUnitId = Auth::user()->unit_id;

        // Cari unit berdasarkan unit_id user
        $unit = UnitKerja::find($userUnitId);

        // Tentukan parentUnitId
        // Jika unit memiliki parent_id (child), gunakan parent_id-nya
        // Jika unit tidak memiliki parent_id (parent), gunakan unit_id itu sendiri
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        // $Users = User::whereNotIn('id', [1, 3])->get();
        // Membuat query berdasarkan kondisi apakah yang login adalah superadmin
        $Users = User::whereNotIn('id', [1, $user->id]);

        // Jika yang login bukan superadmin (id != 1), tambahkan kondisi `whereHas` untuk filter unit kerja
        if ($user->id != 1) {
            $Users->whereHas('unitKerja', function ($unitQuery) use ($parentUnitId) {
                // Memfilter unit kerja berdasarkan parent_id atau id yang sama
                $unitQuery->where('parent_id', $parentUnitId)
                    ->orWhere('id', $parentUnitId);
            });
        }
        // Ambil semua pengguna yang sesuai dengan kondisi
        $Users = $Users->get()->filter(function ($user) {
            return !$user->hasRole('guest');
        });

        foreach ($Users as $userItem) {
            // Ambil semua role
            $roles = $userItem->getRoleNames(); // Mengembalikan koleksi nama role
            // Array untuk menyimpan role yang diformat
            $formattedRoles = [];

            // Iterasi setiap role untuk format ulang
            foreach ($roles as $role) {
                $formattedRoles[] = formatRole($role);
            }
            // Gabungkan semua role yang diformat ke dalam string
            $userItem->formatted_roles = implode(', ', $formattedRoles);
            // dd($userItem->formatted_roles);
        }

        return view('profil.index', compact('user', 'Users')); // Mengirim data pengguna ke view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe, $id = 0)
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
