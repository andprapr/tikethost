<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        $username = $this->input('username');
        $password = $this->input('password');

        // Debug logging
        \Log::info('Login attempt', ['username' => $username]);

        // Find user by username
        $user = User::where('username', $username)->first();
        
        if (!$user) {
            \Log::info('User not found', ['username' => $username]);
            RateLimiter::hit($this->throttleKey());
            
            throw ValidationException::withMessages([
                'username' => 'Username tidak ditemukan.',
            ]);
        }

        // Check password
        if (!Hash::check($password, $user->password)) {
            \Log::info('Password mismatch', ['username' => $username]);
            RateLimiter::hit($this->throttleKey());
            
            throw ValidationException::withMessages([
                'password' => 'Password salah.',
            ]);
        }

        // Login the user
        Auth::login($user, $this->boolean('remember'));
        
        \Log::info('Login successful', ['username' => $username, 'user_id' => $user->id]);
        
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => 'Terlalu banyak percobaan login. Coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('username')).'|'.$this->ip();
    }
}