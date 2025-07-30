<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.auth.login');
    }

    public function register(Request $request)
    {
        return view('pages.auth.register');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
            'password.string' => 'Password harus berupa teks.',
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            if (auth()->user()->role === 'community') {
                return redirect()->route('home')->with('toast', 'Selamat datang di Pekanbaru Trofeo!');
            }

            return redirect()->route('dashboard.index')->with('toast', 'Selamat datang di Pekanbaru Trofeo!');
        }

        return redirect()->back()->withErrors(['email' => 'Email atau password salah!']);
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:255',
            'role' => 'required|in:community,field_manager,referee,photographer',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
        ], [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor telepon harus diisi.',
            'role.required' => 'Peran harus dipilih.',
            'role.in' => 'Peran tidak valid.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'confirm_password.required' => 'Konfirmasi password harus diisi.',
            'confirm_password.same' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = $request->role;
        $user->balance = 0;
        $user->password = bcrypt($request->password);
        $user->save();

        auth()->login($user);

        if (in_array($user->role, ['referee', 'photographer'])) {
            return redirect()->route('complete-profile')->with('toast', 'Registrasi berhasil, silahkan lengkapi data diri anda!');
        }

        return redirect()->route('home')->with('toast', 'Registrasi berhasil, selamat datang di Pekanbaru Trofeo!');
    }

    public function completeProfile()
    {
        return view('pages.auth.complete-profile');
    }

    public function completeProfilePost(Request $request)
    {
        $request->validate([
            'price_per_hour' => 'required|numeric',
            'description' => 'required|string',
            // 'portofolio' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
            'portofolio' => 'required|string',
        ], [
            'price_per_hour.required' => 'Harga per jam harus diisi.',
            'price_per_hour.numeric' => 'Harga per jam harus berupa angka.',
            'description.required' => 'Deskripsi harus diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'portofolio.required' => 'Portofolio harus diisi.',
            'portofolio.string' => 'Portofolio harus berupa URL atau string.',
        ]);

        $service = new Service();

        $service->user_id = auth()->user()->id;
        $service->price_per_hour = $request->price_per_hour;
        $service->description = $request->description;
        // $service->portfolio = store_image($request->file('portofolio'), 'images/portfolios');
        $service->portfolio = $request->portofolio; // Assuming it's a URL or string

        $service->save();

        return redirect()->route('dashboard.index')->with('toast', 'Data diri anda berhasil dilengkapi!');
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('home')->with('toast', 'Anda telah logout!');
    }
}
