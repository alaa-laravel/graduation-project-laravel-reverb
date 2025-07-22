<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class AuthController extends Controller
{

    public function index()
    {
        $users = User::with(['gradelevel:id,grade_level'])
            ->select('id', 'name', 'grade_level_id')
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'Users fetched successfully',
            'data' => $users
        ]);
    }


    public function register(StoreUserRequest $request)
    {
        $user = $request->validated();
        $user['password'] = bcrypt($user['password']);
        $user = User::create($user);
        $user->load('gradelevel:id,grade_level');

        return response()->json([
            'status' => 200,
            'message' => 'user registerd successfully',
            'data' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => "required|email|exists:users,email",
            'password' => "required"
        ]);

        if (!Auth::attempt($data)) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid email or password',
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => "User logged in successfully",
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => 400,
                'message' => 'Old password is incorrect'
            ]);
        }

        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Password changed successfully'
        ]);
    }


    public function profile()
    {
        $user = Auth::user();
        $user->load('gradelevel:id,grade_level');


        return response()->json([
            'status' => 200,
            'message' => "your profile data",
            'data' => $user,
        ]);
    }


    public function show(string $id)
    {
        $one_user = User::select('id', 'name', 'gender', 'tybe', 'grade_level_id')
            ->with('gradelevel:id,grade_level')
            ->find($id);

        if (!$one_user) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => "Here you  are the one user u want to  show",
            'data' => $one_user,
        ]);
    }




    public function update(UpdateUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = auth()->user(); // بدل من User::findOrFail($id)

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthenticated',
            ], 401);
        }

        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $user->update($validatedData);

        $user->load('gradelevel:id,grade_level');

        return response()->json([
            'status' => 200,
            'message' => 'User updated successfully',
            'data' => $user,
        ]);
    }

    public function destroy()
    {
        $user = auth()->user();
        $user->delete();
        return response()->json([
            'status' => 200,
            'message' => 'User deleted successfully',
        ]);
    }

    public function search(string $id)
    {
        $value = User::select('id', 'name', 'gender', 'tybe', 'grade_level_id')
            ->with('gradelevel:id,grade_level')
            ->find($id);

        if (!$value) {
            return response()->json([
                'status' => 404,
                'message' => 'there is no  result',
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => "Here you  are the user u search  about",
            'data' => $value,
        ]);
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'User logged out successfully',
        ]);
    }
}