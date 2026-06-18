<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function loginSales(Request $request)
    {
        // Validasi PIN Hardcode sesuai tahun perusahaan
        if ($request->kode_akses === '140618') {
            session(['role' => 'sales']);
            session(['nama_user' => 'Tim Sales']); // <-- ganti dari 'name' jadi 'nama_user'
            return redirect('/')->with('success', 'Login berhasil! Selamat datang, Tim Sales.');
        }

        return back()->with('error_sales', 'Kode Akses Salah!');
    }

    public function loginAdmin(Request $request)
    {
        // Asumsi pakai tabel 'users' bawaan Laravel. 
        // 'username' di form = kolom 'email' di database
        $credentials = [
            'email' => $request->username, 
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            session(['role' => 'admin']);
            session(['nama_user' => 'Administrator']); // <-- ganti dari 'name' jadi 'nama_user'
            return redirect('/')->with('success', 'Login berhasil! Selamat datang, Admin.');
        }

        return back()->with('error_admin', 'Username atau Password salah!');
    }

    public function logout()
    {
        Auth::logout(); // Logout dari auth bawaan (Admin)
        session()->flush(); // Hapus semua session (Sales & Admin)
        return redirect('/login')->with('success', 'Berhasil logout!');
    }
}