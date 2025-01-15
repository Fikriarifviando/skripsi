<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    // public function update(Request $request)
    // {
    //     $user = Auth::user();
    //     $user->name = $request->input('name');
    //     $user->email = $request->input('email');
    //     $user->save();

    //     return redirect()->route('profile.edit')->with('status', 'Profil Berhasil Di Update!');
    // }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
        ]);

        $user = User::find(auth()->id());
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return redirect()
        ->route('profile.edit')
        ->with('success', 'Profil Berhasil Di Update!');
    }

    public function changepassword()
    {
        return view('profile.changepassword', ['user' => Auth::user()]);
    }

    public function password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::find(auth()->id());
      

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password Sebelumnya Salah!']);
        }

        $user->fill([
            'password' => Hash::make($request->new_password)
        ])->save();

        return back()->with('success', 'Password berhasil Diubah!');
    }
}