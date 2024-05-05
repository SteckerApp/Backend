<?php

namespace App\Services\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Invite;
use App\Enums\UserType;
use App\Models\Affiliate;
use Illuminate\Support\Str;
use App\Trait\HandleResponse;
use App\Mail\VerificationMail;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Jobs\sendVerificationMailSync;
use App\Jobs\SendPasswordResetMailSync;
use App\Http\Resources\RegisterdResource;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Resources\PermissionsResource;

class AuthService
{
    use HandleResponse;

    public function createUser(RegisterRequest $request)
    {

        try {
            DB::beginTransaction();

            //generate verification code
            $verificationCode = config('keys.verification.code');

            switch ($request->usertype) {
                case 'client':
                  $usertype = UserType::CLIENT;
                    break;
                case 'affiliate':
                    $usertype = UserType::AFFLIATE;

                    break;
                case 'admin':
                    $usertype = UserType::ADMIN;

                    break;
            }

            $request->merge([
                'password' => Hash::make($request->password),
                'verification_token' => $verificationCode,
                'user_type' => $usertype
            ]);

            // create user
            $user = User::create($request->all());

            //take of invited
            if ($request->has('invitation')) {

                if ($request->invitation == 'client' && !empty($request->invitation_id)) {
                    $invite = Invite::where('id', $request->invitation_id)->first();

                    // $user->syncPermissions($invite->preset);

                    DB::table('company_user')->insert([
                        'user_id' => $user->id,
                        'company_id' => $invite->company_id,
                        'role' => $invite->role,
                    ]);

                    $invite->update([
                        'status' => 'joined'
                    ]);
                }



                if ($request->invitation == 'affiliate' && !empty($request->refferal_code)) {

                    $referral = User::where('referral_code', $request->invitation_code)->first();

                    if ($referral) {
                        Affiliate::create([
                            'referral_id' => $referral->id,
                            'user_id' => $user->id
                        ]);
                    }
                    // $user->assignRole('client');
                }

                if ($request->invitation == 'admin' && !empty($request->invitation_id)) {
                    $invite = Invite::where('id', $request->invitation_id)->first();

                    $user->assignRole($invite->role);

                    $invite->update([
                        'status' => 'joined'
                    ]);
                }

                
            }
            // else if($request->has('role')){
            //     $user->assignRole($request->role);
            // }
            // else {
            //     $user->assignRole('client');
            // }

            //set referral code

            $code = Str::random(4) . time();

            tap($user, function ($collection) use ($code) {
                return $collection->update([
                    'referral_code' => $code
                ]);
            });

            //login
            $token = $user->createToken($user->id . '-' . $user->email)->plainTextToken;
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorResponse($th->getMessage(), 'Internal Server Error', 500);
        }

        // send mail of verification code
        $this->sendVerificationMail($request->email, $verificationCode, $request->firstName);

        return $this->successResponse(
            [
                'token' => $token,
                'user' =>  new RegisterdResource($user)
            ],
            'Account Registration successful'
        );
    }

    protected function sendVerificationMail($email, $verificationCode, $firstName)
    {
        // send mail of verification code
        if (env('APP_SYSTEM_STACK') == 'queue') {
            sendVerificationMailSync::dispatch($email, $verificationCode, $firstName);
        } else {
            $this->sendVerificationCode($email, $verificationCode, $firstName);
        }
    }

    public function sendVerificationCode($email, $verificationCode, $firstName)
    {
        try {
            Mail::to($email)->send(new VerificationMail($verificationCode, $firstName));
        } catch (\Throwable $th) {
        }

    }

    public function resendVerification($request)
    {
        if ($request->user()->email_verified_at == null) {
            $verificationCode = config('keys.verification.code');
            $request->user()->verification_token = $verificationCode;
            $request->user()->save();

            $this->sendVerificationMail($request->user()->email, $verificationCode, $request->user()->first_name);

            return $this->successResponse(null, 'Verification mail resend successfully');
        }

        return $this->successResponse(null, 'Account verified already');
    }


    public function verifyEmail($request)
    {
        if ($request->user()->email_verified_at == null) {
            if ($request->user()->verification_token == $request->code) {
                $request->user()->email_verified_at = Carbon::now();
                $request->user()->verification_token = null;
                $request->user()->save();
                return $this->successResponse(null, 'Email verification successfully');
            }

            return $this->errorResponse(null, 'Invalid email verification code', 400);
        }

        return $this->successResponse(null, 'Account verified already');
    }

    public function passwordReset($request)
    {
        $email = $request->email;
        $verificationCode = config('keys.password.reset');
        $firstName = User::whereEmail($email)->first()->first_name;

        DB::table('password_resets')->where('email', $email)->delete();
        $this->sendPasswordRestMail($email, $verificationCode, $firstName);

        DB::table('password_resets')->insert(
            [
                'email' => $email,
                'token' => $verificationCode,
                'created_at' => Carbon::now()
            ]
        );
        return $this->successResponse(null, 'Password reset mail successfully');
    }

    protected function sendPasswordRestMail($email, $verificationCode, $firstName)
    {
        // send mail of verification code
        if (env('APP_SYSTEM_STACK') == 'queue') {
            SendPasswordResetMailSync::dispatch($email, $verificationCode, $firstName);
        } else {
            $this->sendPasswordRest($email, $verificationCode, $firstName);
        }
    }

    public function sendPasswordRest($email, $verificationCode, $firstName)
    {
        Mail::to($email)->send(new PasswordResetMail($email, $verificationCode, $firstName));
    }

    public function resendPasswordReset($request)
    {
        $email = $request->email;
        $firstName = User::whereEmail($email)->first()->first_name;
        $check = DB::table('password_resets')->where('email', $email)->whereDate('created_at', '>=', Carbon::now()->subMinutes(10))->latest()->first();

        if ($check) {
            $verificationCode = $check->token;
            DB::table('password_resets')
                ->where('email', $email)
                ->update([
                    'created_at' => Carbon::now()
                ]);
        } else {
            $verificationCode = config('keys.password.reset');
            DB::table('password_resets')->where('email', $email)->delete();
            DB::table('password_resets')->insert(
                [
                    'email' => $email,
                    'token' => $verificationCode,
                    'created_at' => Carbon::now()
                ]
            );
        }

        $this->sendPasswordRestMail($email, $verificationCode, $firstName);

        return $this->successResponse(null, 'Password reset mail resend successfully');
    }

    public function login($request)
    {
        $user = User::whereEmail($request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken($user->id . '-' . $user->email)->plainTextToken;
                return $this->successResponse(
                    [
                        'token' => $token,
                        'user' =>  new RegisterdResource($user),
                        'permissions'=> PermissionsResource::collection($user->getAllPermissions()),
                        'roles'=>$user->getRoleNames()

                    ],
                    'Login successful'
                );
            } else {
                return $this->errorResponse(null, 'Invalid login details', 400);
            }
        } else {
            return $this->errorResponse(null, 'Invalid login details', 400);
        }
    }

    public function logout($request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(null, 'Logout successful');
    }

    public function changePassword($request)
    {
        $email = $request->email;
        $check = DB::table('password_resets')->where('email', $email)->latest()->first();

        if ($check && $check->token == $request->token && $check->email == $email) {
            DB::table('password_resets')->where('email', $email)->delete();
            User::where('email', $email)->update(['password' => Hash::make($request->password)]);
            return $this->successResponse(null, 'Password change successfully');
        }

        return $this->errorResponse(null, 'Invalid password reset code', 400);
    }
}
