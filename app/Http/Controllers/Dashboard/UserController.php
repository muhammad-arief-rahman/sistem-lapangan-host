<?php

namespace App\Http\Controllers\dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Retrieve all users except those with the 'super_admin' role, ordered by role
        $users = User::where("role", "!=", "super_admin")
            ->orderBy("role")
            ->get();

        // Define available roles for the dropdown in the modal
        $roles = [
            "customer" => "Pelanggan",
            "referee" => "Wasit",
            "photographer" => "Fotografer",
            "field_manager" => "Pengelola Lapangan",
            "community" => "Komunitas", // Ensure 'community' is included if it's a valid role
        ];

        // Prepare data for the dashboard cards
        $cardData = (object) [
            "totalUsers" => $users->count(),
            "totalReferees" => $users->where("role", "referee")->count(),
            "totalPhotographers" => $users
                ->where("role", "photographer")
                ->count(),
            "totalFieldManagers" => $users
                ->where("role", "field_manager")
                ->count(),
        ];

        // Return the view with users data, card statistics, and roles for the modal
        return view(
            "pages.dashboard.users.index",
            compact("users", "cardData", "roles")
        );
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users,email",
            "phone" => "nullable|string|max:20",
            "role" =>
                "required|string|in:referee,photographer,field_manager,community",
            "password" => "required|string|min:8|confirmed",
            "photo" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
        ]);

        $userData = $request->except("password_confirmation");
        $userData["password"] = Hash::make($request->password);

        if ($request->hasFile("photo")) {
            $imageName = time() . "." . $request->photo->extension();
            $request->photo->move(public_path("uploads/users"), $imageName); // Store in public/uploads/users
            $userData["photo"] = $imageName;
        }

        User::create($userData);

        return redirect()
            ->route("dashboard.user.index")
            ->with("toast", "Pengguna berhasil ditambahkan.");
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            "name" => "required|string|max:255",
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                Rule::unique("users")->ignore($user->id),
            ],
            "phone" => "nullable|string|max:20",
            "role" =>
                "required|string|in:referee,photographer,field_manager,community", // Adjust roles as needed
            "password" => "nullable|string|min:8|confirmed", // Password is optional for update
            "photo" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
        ]);

        $userData = $request->except("password_confirmation");

        if ($request->filled("password")) {
            $userData["password"] = Hash::make($request->password);
        } else {
            unset($userData["password"]); // Don't update password if not provided
        }

        if ($request->hasFile("photo")) {
            // Delete old photo if exists
            if ($user->photo) {
                $oldImagePath = public_path("uploads/users/" . $user->photo);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $imageName = time() . "." . $request->photo->extension();
            $request->photo->move(public_path("uploads/users"), $imageName);
            $userData["photo"] = $imageName;
        }

        $user->update($userData);

        return redirect()
            ->route("dashboard.user.index")
            ->with("toast", "Pengguna berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        // Delete if avatar exists
        if ($user->avatar) {
            delete_image($user->avatar);
        }

        return redirect()
            ->route("dashboard.user.index")
            ->with("toast", "Pengguna berhasil dihapus.");
    }
}
