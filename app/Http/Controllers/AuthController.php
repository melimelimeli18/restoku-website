<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use app\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Http\Request;

class AuthController extends Controller
{
    //sign up
    public function signup(Request $request)
    {
        try{

            // dd($request->all());
    
            \Log::info('Signup request received', $request->all());
            // Validasi form
            $request->validate([
                'restaurant_name' => 'required|string|max:255',
                'restaurant_address' => 'required|string',
                'restaurant_photo' => 'nullable|image|max:2048', // Validasi foto
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:8',
            ]);
    
            // Jika ada foto yang di-upload
            // if ($request->hasFile('restaurant_photo')) {
            //     // Menyimpan file foto ke folder public/restaurant_photos
            //     $photoPath = $request->file('restaurant_photo')->store('restaurant_photos', 'public');
            // } else {
            //     $photoPath = null;  // Jika tidak ada foto, maka set null
            // }
    
            // Simpan data user ke database
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),  // Meng-hash password
                'restaurant_name' => $request->restaurant_name,
                'restaurant_address' => $request->restaurant_address,
                // 'restaurant_photo' => $photoPath,  // Simpan nama file foto di database
            ]);
    
            \Log::info('User created: ', ['user' => $user]);
    
            // Auto-login user setelah signup
            Auth::login($user);
    
            // Redirect ke halaman home setelah berhasil signup
            return redirect()->route('app.home');  
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        
    } 


    // Login pengguna
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            // Setelah login berhasil, arahkan ke halaman home
            return redirect()->route('home');  // Ganti 'home' dengan route yang sesuai
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Logout pengguna
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }


}
