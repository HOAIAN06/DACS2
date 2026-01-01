<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu là bắt buộc',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            // Nếu là admin, chuyển hướng đến admin dashboard
            if (Auth::user()->is_admin) {
                return redirect('/admin/dashboard')->with('success', 'Đăng nhập thành công!');
            }
            
            // Ngược lại, chuyển hướng đến user dashboard
            return redirect('/user/dashboard')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    /**
     * Hiển thị form đăng ký
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed'],
        ], [
            'name.required' => 'Tên là bắt buộc',
            'name.max' => 'Tên không được vượt quá 255 ký tự',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã được sử dụng',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        return redirect('/user/dashboard')->with('success', 'Đăng ký thành công! Chào mừng đến HANZO');
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Đăng xuất thành công!');
    }

    /**
     * Hiển thị form quên mật khẩu
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Gửi OTP đến email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.exists' => 'Email này chưa được đăng ký',
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Tạo OTP 6 chữ số
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Lưu OTP vào database với thời gian hết hạn 10 phút
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
            'otp_attempts' => 0,
            'otp_verified' => false,
        ]);

        // Gửi OTP qua email
        try {
            Mail::to($user->email)->send(new SendOtpMail($user, $otp));
            return redirect()->route('verify.otp.form', ['email' => $request->email])
                           ->with('success', 'OTP đã được gửi đến email của bạn. Vui lòng kiểm tra (hết hạn sau 10 phút)');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể gửi email. Vui lòng thử lại sau.');
        }
    }

    /**
     * Hiển thị form xác minh OTP
     */
    public function showVerifyOtpForm(Request $request)
    {
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    /**
     * Xác minh OTP
     */
    public function verifyOtp(Request $request)
    {
        \Log::info('OTP Verify Request:', [
            'email' => $request->email,
            'otp' => $request->otp,
            'all_data' => $request->all()
        ]);

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ], [
            'email.required' => 'Email là bắt buộc',
            'otp.required' => 'OTP là bắt buộc',
            'otp.digits' => 'OTP phải là 6 chữ số',
        ]);

        $user = User::where('email', $request->email)->first();
        
        \Log::info('User OTP Data:', [
            'user_id' => $user->id,
            'stored_otp' => $user->otp,
            'received_otp' => $request->otp,
            'otp_expires_at' => $user->otp_expires_at,
            'otp_verified' => $user->otp_verified,
        ]);

        // Kiểm tra OTP hết hạn
        if ($user->otp_expires_at < Carbon::now()) {
            return back()->with('error', 'OTP đã hết hạn. Vui lòng yêu cầu OTP mới.')->onlyInput('email');
        }

        // Kiểm tra số lần nhập sai (giới hạn 5 lần)
        if ($user->otp_attempts >= 5) {
            return back()->with('error', 'Bạn đã nhập sai OTP quá nhiều lần. Vui lòng yêu cầu OTP mới.')->onlyInput('email');
        }

        // Kiểm tra OTP
        if ($user->otp !== $request->otp) {
            $user->increment('otp_attempts');
            $remainingAttempts = 5 - $user->otp_attempts;
            return back()->with('error', "OTP không chính xác. Bạn còn $remainingAttempts lần thử.")->onlyInput('email');
        }

        // OTP chính xác
        $user->update(['otp_verified' => true]);
        
        return redirect()->route('reset.password.form', ['email' => $request->email])
                       ->with('success', 'OTP xác minh thành công. Vui lòng đặt lại mật khẩu.');
    }

    /**
     * Hiển thị form reset mật khẩu
     */
    public function showResetPasswordForm(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !$user->otp_verified) {
            return redirect()->route('login')->with('error', 'Phiên hết hạn. Vui lòng thử lại.');
        }

        return view('auth.reset-password', ['email' => $request->email]);
    }

    /**
     * Xử lý reset mật khẩu
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => ['required', 'confirmed'],
        ], [
            'email.required' => 'Email là bắt buộc',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user->otp_verified) {
            return redirect()->route('login')->with('error', 'Phiên không hợp lệ. Vui lòng thử lại.');
        }

        // Cập nhật mật khẩu và xóa OTP
        $user->update([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
            'otp_verified' => false,
        ]);

        return redirect()->route('login')->with('success', 'Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập.');
    }
}

