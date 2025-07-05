<?php

namespace App\Http\Controllers;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Admin;
use App\Http\Resources\RegisterResource;
use App\Http\Resources\LoginResource;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\ProfileingResourse;
use Illuminate\Support\Facades\Auth;
use App\Models\ResetCodePassword;
use App\Mail\SendCodeResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Traits\Uploadfile;
class AuthController extends BaseController
{ 
    use Uploadfile;
    public function user_register(UserRegisterRequest $request)
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['activation_token'] = str::random(60);
        $user = user::query()->create($input);
        $user->assignRole('developer');
        $accessToken = $user->createToken('MyApp', ['user'])->plainTextToken;
        $data['token'] = $accessToken;
        $data['user'] = new RegisterResource($user);
        return $this->sendResponse($data, "Registration Done");


    }
    public function user_login(UserLoginRequest $request)
    {
        $log = request(['email', 'password']);
        if (auth()->guard('user')->attempt($request->only('email', 'password'))) {
            config(['auth.guards.api.provider' => 'user']);
            $user = user::query()->select('users.*')->find(auth()->guard('user')->user()['id']);
            $success = $user;
            $success['token'] = $user->createToken('MyApp', ['user'])->plainTextToken;
           // $success['user'] = new LoginResource($user);
            return $this->sendResponse($success, "Logining Done");
        } else {
            return $this->sendError('User not authenticated', [], 401);
        }
    }

    public function user_logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
        $request->user()->currentAccessToken();
        return $this->sendResponse(null, "Logged out successfully");
                    }
     return $this->sendError('User not authenticated', [], 401);
    }
    public function delete_account(Request $request)
    {
    $user = $request->user();
    if (!$user) {
        return $this->sendError('User not authenticated', [], 401);
    }
    if ($request->user()->currentAccessToken()) {
        $request->user()->currentAccessToken()->delete();
    }
    $user->delete();
    return $this->sendResponse(null, 'Account deleted successfully');
    }

    
    public function admin_login(AdminLoginRequest $request)
    {
        if (auth()->guard('admin')->attempt($request->only('email', 'password'))) {
            config(['auth.guards.api.provider' => 'admin']);
            $admin = Admin::query()->select('admins.*')->find(auth()->guard('admin')->user()['id']);
            $success = $admin;
            $success['token'] = $admin->createToken('MyApp', ['admin'])->plainTextToken;
           // $success['admin'] = new LoginResource($admin);
            return $this->sendResponse($success, "Logining Done");
        } else {
            return $this->sendError('Admin not authenticated', [], 401);
        }
    }
    public function admin_logout(Request $request)
    {
        $admin = Auth::user();
        if ($admin) {
            $request->user()->currentAccessToken()->delete();
            return $this->sendResponse(null, "Logged out successfully");
        }
    
        return $this->sendError('User not authenticated', [], 401);
    }
    public function ForgetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:admins',
        ]);

        ResetCodePassword::where('email', $request->email)->delete();
        $data['code'] = mt_rand(100000, 999999);
        $codeData = ResetCodePassword::create($data);
        Mail::to($request->email)->send(new SendCodeResetPassword($codeData['code']));
        return response(['message' => trans('code.sent')], 200);

    }
    public function CodeCheck(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
        ]);
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        return response([
            'code' => $passwordReset->code,
            'message' => trans('passwords.code_is_valid')
        ], 200);
    }
    public function ResetPassword(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);
        if ($passwordReset->created_at < now()->subHour()) {  
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }
        $user = Admin::firstWhere('email', $passwordReset->email);
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        $passwordReset->delete();

        return response(['message' => 'Password has been successfully reset'], 200);
    }

    public function show(Request $request)
{   
         $data=$request->user()->profile;
         return $this->sendResponse(new ProfileingResourse($data), "Successfully retrieved all CV.");
}

public function update(Profile $request)
{
    $data = $request->validated();
    if ($request->hasFile('avatar'))
    {
     $path = $this->storeFile($request->file('avatar'),'projects', null,'public_uploads');
     $data['avatar'] = asset('uploads/' . $path);
    }
    $profile = $request->user()->profile()->updateOrCreate([], $data);

    return $this->sendResponse(new ProfileingResourse($profile), 'profile created successfully', 200);
    
}

}
