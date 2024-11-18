<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); // Mendapatkan data pengguna yang login
        $Users = User::where('id', '>', '1')->get();
        foreach ($Users as $userItem) {
            // Ambil role pertama
            $role = $userItem->getRoleNames()->first() ?? 'Tidak tersedia';

            // Ganti nama role menggunakan switch
            switch ($role) {
                case 'superadmin':
                    $formattedRole = 'Super Admin';
                    break;
                case 'admin':
                    $formattedRole = 'Admin';
                    break;
                case 'penanggungjawab':
                    $formattedRole = 'Penanggung Jawab';
                    break;
                case 'ppk':
                    $formattedRole = 'Pejabat Pembuat Komitmen (PPK)';
                    break;
                case 'pptk':
                    $formattedRole = 'Pejabat Pelaksana Teknis Kegiatan (PPTK)';
                    break;
                default:
                    $formattedRole = 'Tidak tersedia';
            }

            // Tambahkan atribut 'formatted_role' ke setiap user
            $userItem->formatted_role = $formattedRole;
        }

        return view('profil.index', compact('user', 'Users')); // Mengirim data pengguna ke view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        $user = User::findOrFail($id); // Ambil data user berdasarkan ID
        return view('profil.edit', compact('user'));
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
