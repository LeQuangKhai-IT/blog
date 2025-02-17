<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
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
     *
     * @param \App\Http\Requests\StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
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
     *
     * @param \App\Http\Requests\Request $request
     * @return \Illuminate\Http\JsonResponse
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
     *
     * @param \App\Http\Requests\ForgotPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
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
     *
     * @param \App\Http\Requests\ChangePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
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
     *
     * @param \App\Http\Requests\Request $request
     * @return \Illuminate\Http\JsonResponse
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

    /**
     * Redirects the user to the OAuth provider for authentication (e.g., Google, Facebook, GitHub).
     *
     * @param string $provider The name of the OAuth provider (e.g., 'google', 'facebook', 'github').
     * @return \Illuminate\Http\JsonResponse The JSON response containing the redirect URL to the provider.
     */
    public function redirectToProvider($provider)
    {
        $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
        return response()->json(['redirect_url' => $url]);
    }

    /**
     * Handle the callback after authentication from the provider in an API context.
     *
     * @param  string  $provider  The OAuth provider (e.g., 'google', 'facebook', 'github').
     * @return \Illuminate\Http\JsonResponse  JSON response containing user information or token after successful login.
     */
    public function handleProviderCallback($provider)
    {

        $socialUser = Socialite::driver($provider)->stateless()->user();

        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'username' => $this->generateUsername($socialUser->getName()),
                'fullname' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'provider_id' => $socialUser->getId(), // Save provider_id to identify the user
            ]);
        }
        Auth::login($user, true);

        $token = $user->createToken(env('SIGNUP_TOKEN'), ['*'], Carbon::now()->addMinute(60))->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Generate a unique username from the user's full name.
     *
     * @param string $fullname The full name of the user.
     * @return string The generated unique username.
     */
    private function generateUsername($fullname)
    {
        $username = strtolower(str_replace(' ', '.', $fullname));
        $existingUsernameCount = User::where('username', 'like', $username . '%')->count();

        return $existingUsernameCount ? $username . $existingUsernameCount : $username;
    }
}
