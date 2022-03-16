<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class UserController extends Controller
{
    public function store(Request $request)
    {$validator = Validator::make($request->all(),[
        'nama' => 'required',
        'username' => 'required',
        'password' => 'required|string|min:6',
        'role' => 'required',
        'id_outlet' => 'required'
    ]);
    if ($validator->fails()){
        return response()->json($validator->errors());
    }
        $user = new User();
        $user->nama     = $request->nama;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role     = $request->role;
        $user->id_outlet = $request->id_outlet;

    $user->save();
    
    return response()->json(['message'=>'berhasil di input']);
    }

    
    public function login(Request $request)
        {
            $credentials = $request->only('username', 'password');
             try {
                if(! $token = JWTAuth::attempt($credentials))
         {
                    return response()->json(['error' => 'invalid_credentials'], 400);
        }
            } catch (JWTException $e) {

                    return response()->json(['error' => 'could_not_create_token', 500]);
        }
 
        //     $data = [
        //     'token' => $token,
        //     'user' => JWTAuth::user()
        // ];

        $user = JWTAuth::user();
		
		return response()->json([
			'success' => true,
			'message' => 'login berhasil',
			'token' => $token,
			'user'  => $user
		]);

 }


    public function getAuthenticatedUser()
 {
    try
 {
    if (! $user = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['user_not_found'], 404);
    }
     }
    catch (TokenExpiredException $e)
    {
        return response()->json(['message' => 'token_expired']);
    }
    catch (TokenInvalidException $e)
     {
        return response()->json(['message' => 'token_invalid']);
     }
    catch (JWTException $e)
     {
        return response()->json(['message'=>'token_absent']);
     }
        return response()->json(compact('user'));
    }

 public function logout(Request $request)
 {
     if(JWTAuth::invalidate(JWTAuth::getToken())){
         return response()->json(['message'=>'anda sudah logout']);
     } else {
         return response()->json(['message'=>'gagal logout']);

     }
 }
}
