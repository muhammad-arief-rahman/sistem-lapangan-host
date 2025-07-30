<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $service = $user->service ?? null;

        return view('pages.dashboard.profile.index', compact('user', 'service'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|max:255|unique:users,email,$user->id",
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'nullable|string|min:8',
            'password_confirmation' => 'same:password',
        ], [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, atau svg.',
            'photo.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            'password.min' => 'Password minimal 8 karakter.',
            'password_confirmation.same' => 'Konfirmasi password tidak cocok.',
        ]);

        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                delete_image($user->photo);
            }

            $user->photo = store_image($request->file('photo'), 'images/profiles');
        }

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('dashboard.profile.index')->with('toast', 'Profil berhasil diperbarui.');
    }

    public function updateService(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'description' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            // 'portfolio' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'portfolio' => 'required|string',
        ], [
            'description.required' => 'Deskripsi harus diisi.',
            'price_per_hour.required' => 'Harga per jam harus diisi.',
            'price_per_hour.numeric' => 'Harga per jam harus berupa angka.',
            // 'portfolio.image' => 'Portfolio harus berupa gambar.',
            // 'portfolio.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, atau svg.',
            // 'portfolio.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            'portfolio.required' => 'Portfolio harus diisi.',
            'portfolio.string' => 'Portfolio harus berupa URL atau string.',
        ]);

        $service = $user->service;

        $service->description = $request->description;
        $service->price_per_hour = $request->price_per_hour;
        $service->portfolio = $request->portfolio;

        // if ($request->hasFile('portfolio')) {
        //     if ($service->portfolio) {
        //         delete_image($service->portfolio);
        //     }

        //     $service->portfolio = store_image($request->file('portfolio'), 'images/portfolios');
        // }

        $service->save();

        return redirect()->route('dashboard.profile.index')->with('toast', 'Layanan berhasil diperbarui.');
    }
}
