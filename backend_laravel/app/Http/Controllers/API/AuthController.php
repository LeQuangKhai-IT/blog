<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Logs in a user.
     *
     * @param \App\Http\Requests\LoginUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        $maxTokens = 5;

        $validated = $request->validated();
        if (!auth()->attempt($validated)) {
            return response()->json([
                'error' => 'Invalid login credentials'
            ], 401);
        }

        $userAuthenticated = auth()->user();

        $user = User::find($userAuthenticated);

        $tokensCount = $user->tokens()->count();

        if ($tokensCount >= $maxTokens) {
            $user->tokens()->oldest()->first()->delete();
        }

        event(new Login('sanctum', $user, true));
        return response()->json([
            'access_token' => $user->createToken(env('SIGNIN_TOKEN'), ['*'], Carbon::now()->addMinute(60))->plainTextToken,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Registers a new account.
     *
     * @param \App\Http\Requests\RegisterUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($validated);

        event(new Registered($user));

        auth()->login($user);

        return response()->json([
            'access_token' => $user->createToken(env('SIGNUP_TOKEN'), ['*'], Carbon::now()->addMinute(60))->plainTextToken,
            'token_type' => 'Bearer',
            'data' => $user,
            'message' => 'User registered successfully. Please check your email to verify your account.'
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @param \App\Http\Requests\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        event(new Logout('sanctum', $request->user()));
        return response()->json([
            'message' => 'Logged out successfully.'
        ]);
    }

    /**
     * Sends a password reset request.
     *
     * @param \App\Http\Requests\ForgotPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $validated = $request->validated();
        $status = Password::sendResetLink($validated);

        return $status = Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }

    /**
     * Resets the password.
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $validated = $request->validated();
        $status = Password::reset(
            $validated,
            function ($user, $password) {
                $user->update([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ]);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)], 200)
            : throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);
    }

    /**
     * Changes the user's password.
     *
     * @param \App\Http\Requests\ChangePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $validated = $request->validated();
        $userAuthenticated = auth()->user();

        if (!Hash::check($validated['current_password'], $userAuthenticated->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }

        $user = User::find($userAuthenticated);
        $user->update(['password' => $request->new_password]);

        return response()->json(['message' => 'Password changed successfully']);
    }

    /**
     * Handle the email verification.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function verifyEmail(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        $request->fulfill();

        event(new Verified($request->user()));

        return response()->json(['message' => 'Email verified successfully.']);
    }

    /**
     * Send verifies email the user's email.
     *
     * @param \App\Http\Requests\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function emailVerifyNotification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent!']);
    }
}
