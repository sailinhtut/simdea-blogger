<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEmail;
use App\Jobs\ProcessWelcomeEmail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signUp(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);



        if ($validation->fails()) {
            return response()->json(['message' => 'Invalid Credentials', 'data' => $validation->errors()], 422);
        }

        try {
            $new_user = User::create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]
            );

            $token = $new_user->createToken($new_user->email)->plainTextToken;
            $response = array_merge($new_user->toArray(), ['token' => $token]);


            // $new_user->sendEmailVerificationNotification();
            // $response = ["message" => "Account Created Successfully. Please check your email to verify and log in again. Thank for using our service."];

            return response()->json(
                $response,
                201
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function signIn(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['message' => 'Invalid Credentials', 'data' => $validation->errors()], 422);
        }

        try {
            $user = User::where('email', $request->email)->firstOrFail();

            if (!$user) {
                return response()->json(['message' => 'User Not Found'], 404);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Incorrect Password',
                    'data' => [
                        'password' => ["Incorrect Password"]
                    ]
                ], 422);
            }

            // if (!$user->hasVerifiedEmail()) {
            //     $user->sendEmailVerificationNotification();
            //     $response = ['message' => 'Email is not verified yet. Please check your email and click verify. Thank for using our service.'];
            //     return response()->json($response, 403);
            // }


            $token = $user->createToken($user->email)->plainTextToken;
            $response = array_merge($user->toArray(), ['token' => $token]);

            return response()->json($response, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No User Found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function signOut(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $user->currentAccessToken()->delete(); // single mode
                // $user->tokens()->delete(); // multi mode

                return response()->json(['message' => 'Logged out successfully'], 200);
            }
            return response()->json(['message' => 'Unauthorized User'], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function updateEmail(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'new_email' => 'required|string|email|max:255|unique:users,email',
            'current_password' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['message' => 'Invalid Credentials', 'data' => $validation->errors()], 422);
        }

        try {
            $user = User::where('email', $request->email)->firstOrFail();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'Incorrect Password',
                    'data' => [
                        'current_password' => ["Incorrect Password"]
                    ]
                ], 422);
            }

            if ($request->new_email === $user->email) {
                return response()->json([
                    'message' => 'Same Email Address',
                    'data' => [
                        'new_email' => ["Update email is same as current email"]
                    ]
                ], 422);
            }

            if ($user) {
                $user->email = $request->new_email;
                $user->save();

                return response()->json(['message' => 'Email updated successfully', 'data' => $user], 200);
            }

            return response()->json(['message' => 'Unauthorized User'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No User Found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validation->fails()) {
            return response()->json(['message' => 'Invalid Credentials', 'data' => $validation->errors()], 422);
        }

        try {
            $user = User::where('email', $request->email)->firstOrFail();

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Incorrect Password',
                    'data' => [
                        'password' => ["Incorrect Password"]
                    ]
                ], 422);
            }

            if ($user) {
                $user->password = Hash::make($request->new_password);
                $user->save();

                return response()->json(['message' => 'Password updated successfully', 'data' => $user], 200);
            }

            return response()->json(['message' => 'Unauthorized User'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No User Found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function getUser(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user) {
                return response()->json(['message' => 'User Profile', 'data' => $user], 200);
            }
            return response()->json(['message' => 'Unauthorized User'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No User Found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUser(Request $request, string $id)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255'
            ]);

            if ($validation->fails()) {
                return response()->json(['message' => 'Invalid Input', 'data' => $validation->errors()], 422);
            }

            $user = User::findOrFail($id);

            if ($user) {
                $user->update($request->only(['name']));
                return response()->json(['message' => 'User Profile Updated', 'data' => $user], 200);
            }

            return response()->json(['message' => 'Unauthorized User'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No User Found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteUser(Request $request, string $user)
    {
        try {
            $user = User::findOrFail($user);

            if ($user) {
                $user->tokens()->delete();
                $user->delete();
                return response()->json(['message' => 'User Deleted Successfully'], 200);
            }

            return response()->json(['message' => 'Unauthorized User'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No User Found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'data' => $e->getMessage()
            ], 500);
        }
    }


    public function sendVerificationEmail(Request $request)
    {
        try {

            $validation  = Validator::make($request->all(), [
                'email' => 'required|string|email|exists:users,email',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Invalid Credentials',
                    'data' => $validation->errors(),
                ]);
            }

            $user = User::where('email', $request->email)->firstOrFail();

            if ($user->hasVerifiedEmail()) {
                return response()->json(['message' => 'Email is already verified. Thank for verification.'], 200);
            }

            $user->sendEmailVerificationNotification();
            return response()->json([
                'message' => 'Verification Email is sent to ' . $request->email . '. Please check your email. Thank for using our service.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No User Found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occured',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyEmail(Request $request)
    {
        try {
            $user = User::findOrFail($request->route('id'));

            if (!$user || !$request->hasValidSignature()) {
                return response()->json(['message' => 'Invalid verification link'], 400);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json(['message' => 'Email is already verified'], 200);
            }

            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            return response()->json(['message' => 'Email verified successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No User Found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'data' => $e->getMessage()
            ], 500);
        }
    }


    public function sendResetPasswordEmail(Request $request)
    {
        try {
            $validation  = Validator::make($request->all(), [
                'email' => 'required|string|email|exists:users,email',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Invalid Credentials',
                    'data' => $validation->errors(),
                ]);
            }

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' => 'Reset Password Email is sent to ' . $request->email . '. Please check your email. Thank for using our service.'
                ]);
            } else {
                return response()->json([
                    'message' => 'Something went wrong'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occured',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'token' => 'required|string',
                'new_password' => 'required|string|min:6',
                'confirm_password' => 'required|string|same:new_password',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Invalid input',
                    'data' => $validation->errors(),
                ]);
            }

            $status = Password::reset(
                [
                    'email' => $request->email,
                    'token' => $request->token,
                    'password' => $request->new_password,
                ],
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->save();

                    $user->tokens()->delete(); // Optional: Revoke existing tokens
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'message' => 'Password has been reset successfully.',
                ]);
            } else {
                return response()->json([
                    'message' => 'Invalid or expired token.',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
}