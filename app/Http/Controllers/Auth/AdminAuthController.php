<?php
// app/Http/Controllers/Auth/AdminAuthController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        // Redirect to dashboard if already logged in
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.admin-login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Check if admin exists and is active
        $admin = Admin::where('email', $credentials['email'])->first();
        
        if (!$admin) {
            throw ValidationException::withMessages([
                'email' => ['Email tidak terdaftar sebagai admin.'],
            ]);
        }

        if (!$admin->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Akun admin Anda telah dinonaktifkan.'],
            ]);
        }

        // Attempt to login
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Update last login
            $admin->updateLastLogin();

            return redirect()->intended(route('dashboard'))
                           ->with('success', 'Selamat datang, ' . $admin->name . '!');
        }

        throw ValidationException::withMessages([
            'email' => ['Kredensial yang diberikan tidak cocok dengan data kami.'],
        ]);
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
                        ->with('success', 'Anda telah berhasil logout.');
    }
}