<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Logs in a user.
     */
    public function login(LoginUserRequest $request)
    {
        $maxTokens = 5;

        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'error' => 'Invalid login credentials'
            ], 401);
        }

        $userAuthenticated = Auth::user();
        $user = User::all()->find($userAuthenticated);

        $tokensCount = $user->tokens()->count();

        if ($tokensCount >= $maxTokens) {
            $user->tokens()->oldest()->first()->delete();
        }

        $token = $user->createToken(env('SIGNIN_TOKEN'), ['*'], Carbon::now()->addMinute(60))->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Registers a new account.
     */
    public function register(StoreUserRequest $request)
    {
        $user = User::create([
            'username' => $request->username,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        $token = $user->createToken(env('SIGNUP_TOKEN'), ['*'], Carbon::now()->addMinute(60))->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Logs out the current user.
     */
    public function logout(Request $request)
    {
        $abc = $request->user();
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'user' => $abc
        ]);
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Sends a password reset request.
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        return $status = Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }

    /**
     * Resets the password.
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('username', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->fill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)], 200)
            : throw ValidationException::withMessages([
                'username' => [trans($status)],
            ]);
    }

    /**
     * Changes the user's password.
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $userAuthenticated = Auth::user();

        if (!Hash::check($request->current_password, $userAuthenticated->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }

        $user = User::all()->find($userAuthenticated);
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }

    /**
     * Send verifies email the user's email.
     */
    public function emailVerifyNotification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent!']);
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

        return response()->json(['message' => 'Email verified!']);
    }
}
