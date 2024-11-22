<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $Users = User::where('id', '>', '1')->get();
        foreach ($Users as $userItem) {
            // Ambil semua role
            $roles = $userItem->getRoleNames(); // Mengembalikan koleksi nama role
            // Array untuk menyimpan role yang diformat
            $formattedRoles = [];

            // Iterasi setiap role untuk format ulang
            foreach ($roles as $role) {
                switch ($role) {
                    case 'superadmin':
                        $formattedRoles[] = 'Super Admin';
                        break;
                    case 'admin':
                        $formattedRoles[] = 'Admin';
                        break;
                    case 'penanggungjawab':
                        $formattedRoles[] = 'Penanggung Jawab';
                        break;
                    case 'ppk':
                        $formattedRoles[] = 'Pejabat Pembuat Komitmen (PPK)';
                        break;
                    case 'pptk':
                        $formattedRoles[] = 'Pejabat Pelaksana Teknis Kegiatan (PPTK)';
                        break;
                    default:
                        $formattedRoles[] = 'Tidak tersedia';
                }
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
