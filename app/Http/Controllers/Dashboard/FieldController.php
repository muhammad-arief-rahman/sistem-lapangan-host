<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\User;
use App\Services\RegionService;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index()
    {
        $fields = Field::getFieldData();

        $cardData = (object) [
            'totalFields' => $fields->count(),
            'totalManagers' => User::where('role', 'field_manager')->count(),
        ];

        return view('pages.dashboard.field.index', compact('fields', 'cardData'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Validate the request
        $rules = [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'village_id' => 'required|exists:villages,id',
        ];

        if ($user->role === "super_admin") {
            $rules['manager_id'] = 'required|exists:users,id';
        }

        $request->validate($rules, [
            'name.required' => 'Nama lapangan harus diisi.',
            'location.required' => 'Lokasi lapangan harus diisi.',
            'price_per_hour.required' => 'Harga per jam harus diisi.',
            'village_id.required' => 'Kecamatan harus dipilih.',
            'village_id.exists' => 'Kecamatan yang dipilih tidak valid.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Gambar harus dalam format jpeg, png, jpg, atau gif.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        // Handle the image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = store_image($request->file('image'), 'images/fields');
        }

        // Create the field
        $field = Field::create([
            'name' => $request->name,
            'location' => $request->location,
            'price_per_hour' => $request->price_per_hour,
            'description' => $request->description,
            'image' => $imagePath ?? null,
            'village_id' => $request->village_id,
            'manager_id' => $user->role === "super_admin" ? $request->manager_id : $user->id,
        ]);

        // Attach facilities if provided
        if ($request->has('facilities')) {
            $field->facilities()->sync($request->facilities);
        } else {
            $field->facilities()->detach();
        }

        return redirect()->route('dashboard.field.index')->with('toast', 'Lapangan berhasil ditambahkan!');
    }

    public function update(Request $request, $field_id)
    {
        if (!$field = Field::find($field_id)) {
            return redirect()->route('dashboard.field.index')->with('toast', 'Lapangan tidak ditemukan!');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'village_id' => 'required|exists:villages,id',
            'facilities' => 'array|nullable|exists:facilities,id',
        ];

        $user = auth()->user();

        if ($user->role === "super_admin") {
            $rules['manager_id'] = 'required|exists:users,id';
        }

        $request->validate($rules, [
            'name.required' => 'Nama lapangan harus diisi.',
            'location.required' => 'Lokasi lapangan harus diisi.',
            'price_per_hour.required' => 'Harga per jam harus diisi.',
            'village_id.required' => 'Kecamatan harus dipilih.',
            'village_id.exists' => 'Kecamatan yang dipilih tidak valid.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Gambar harus dalam format jpeg, png, jpg, atau gif.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        // Handle the image upload if provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($field->image) {
                \Storage::disk('public')->delete($field->image);
            }

            $imagePath = store_image($request->file('image'), 'images/fields');
        }

        // Update the field
        $field->update([
            'name' => $request->name,
            'location' => $request->location,
            'price_per_hour' => $request->price_per_hour,
            'description' => $request->description,
            'image' => $imagePath ?? $field->image,
            'village_id' => $request->village_id,
            'manager_id' => $user->role === "super_admin" ? $request->manager_id : $user->id,
        ]);

        // Sync facilities if provided
        if ($request->has('facilities')) {
            $field->facilities()->sync($request->facilities);
        } else {
            $field->facilities()->detach();
        }

        return redirect()->route('dashboard.field.index')->with('toast', 'Lapangan berhasil diperbarui!');
    }

    public function destroy($field_id)
    {
        if (!$field = Field::find($field_id)) {
            return redirect()->route('dashboard.field.index')->with('toast', 'Lapangan tidak ditemukan!');
        }

        // Delete the image if it exists
        if ($field->image) {
            \Storage::disk('public')->delete($field->image);
        }

        $field->delete();

        return redirect()->route('dashboard.field.index')->with('toast', 'Lapangan berhasil dihapus!');
    }
}
