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
            session(['name' => 'Tim Sales']);
            return redirect('/');
        }

        return back()->with('error_sales', 'Kode Akses Salah!');
    }

    public function loginAdmin(Request $request)
    {
        // Asumsi pakai tabel 'users' bawaan Laravel. 
        // Biar nggak ribet rombak database, kita anggap 'username' itu ngecek ke kolom 'email'.
        $credentials = [
            'email' => $request->username, 
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            session(['role' => 'admin']);
            session(['name' => 'Administrator']);
            return redirect('/');
        }

        return back()->with('error_admin', 'Username atau Password salah!');
    }

    public function logout()
    {
        Auth::logout(); // Logout dari auth bawaan (Admin)
        session()->flush(); // Hapus semua session (Sales & Admin)
        return redirect('/login');
    }
}