<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'no_hp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Auth::user()->update([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('profile.edit')->with('status', 'Profil berhasil diperbarui.');
    }
}
