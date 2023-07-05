<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);

    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 200,
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 200,
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function userProfile() {
        return response()->json(auth()->user());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
    public function index()
    {
        $users = User::withTrashed()->get(['id', 'name', 'email', 'password', 'deleted_at']);
        if($users->count()>0){
            $data = [
                'status' => 200,
                'users' => $users
            ];
            return response()->json($data, 200);
        }
        else{
            $data = [
                'status' => 404,
                'users' => 'No Users Found'
            ];
            return response()->json($data, 404);
        }
    }
    public function destroy(string $id)
    {
        $user = User::find($id);
        if($user){
            $user->delete();
            return response()->json([
                'status' => 200,
                'message' => "User Was Deleted Succesfully"
            ], 200);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => "No Such User Found!"
            ], 404);
        }
    }
    public function restore(string $id)
    {
        $user = User::withTrashed()->find($id);
        if($user){
            $user->restore();
            return response()->json([
                'status' => 200,
                'message' => "User Was Restored Succesfully"
            ], 200);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => "No Such User Found!"
            ], 404);
        }
    }
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|exists:users,email',
            'password' => 'required|string|min:6'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
        else{
            $product = User::find($id);
            if($product){
                $product->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => "User Updated Succesfully"
                ], 200);
            }
            else{
                return response()->json([
                    'status' => 404,
                    'message' => "No Such User Found!"
                ], 404);
            }
        }
    }
}
