<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ActiveAccountNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'socialSignIn', 'socialSignInCallback', 'signInSocial', 'dashboardLogin']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            if (!$this->guard()->user()->social && $this->guard()->user()->platform === null) {
                return $this->respondWithToken($token);
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function dashboardLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            if ($this->guard()->user()->role_id === 1) {
                return $this->respondWithToken($token);
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function user()
    {
        return response()->json($this->guard()->user());
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function guard()
    {
        return Auth::guard();
    }

    public function register(Request $request)
    {
        return redirect('google.com');
        $request->validate([
            'name' => 'required|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6|max:20',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->social = false;
        $user->remember_token = Str::random(20);
        $user->save();

        Notification::route('mail', $user->email)
            ->notify(new ActiveAccountNotification($user));
    }

    public function socialSignIn($social)
    {
        return Socialite::driver($social)->stateless()->redirect();
    }

    public function socialSignInCallback($social)
    {
        $user = Socialite::driver($social)->stateless()->user();
        if ($social === 'google') {
            $data = ['id' => $user->user['id'], 'name' => $user->user['name'], 'email' => $user->user['email'], 'platform' => $social];
        } else if ($social === 'facebook') {
            $data = ['platform' => $social];
        }
        return redirect(env('CLIENT_URL') . 'social/' . base64_encode(json_encode($data)));
    }

    public function signInSocial(Request $request)
    {
        $data = json_decode(base64_decode($request->data));
        $exist = User::where("email", $data->email)->first();

        if ($exist) {
            if ($exist->social && $exist->platform === $data->platform) {
                $credentials = ['email' => $data->email, 'password' => $data->id];

                if ($token = $this->guard()->attempt($credentials)) {
                    return $this->respondWithToken($token);
                }
            } else {
                return response()->json(["message" => "This email already have an account"], 422);
            }
        } else {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->email_verified_at = Carbon::now();
            $user->password = Hash::make($data->id);
            $user->social = true;
            $user->platform = $data->platform;
            $user->save();

            $credentials = ['email' => $data->email, 'password' => $data->id];

            if ($token = $this->guard()->attempt($credentials)) {
                return $this->respondWithToken($token);
            }
        }
    }
}
