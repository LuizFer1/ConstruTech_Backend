<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users, Response::HTTP_OK);
    }

/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'phone' => 'required|string|unique:users|max:255',
            'password' => 'required|string|min:8',
            'type' => 'required|integer|in:1,2',
            'squad_uuid' => 'nullable|string|max:255',
            'company_uuid' => 'nullable|string|max:255',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = User::create($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully!',
            'data' => $user
        ], Response::HTTP_CREATED);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => false,
            'errors' => $e->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'error' => 'An error occurred while creating the user.',
            'message' => $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        if($user){
            return response()->json(['status' => false, "body" => "User not Found", ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($user, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id . '|max:255',
            'phone' => 'string|unique:users,phone,' . $user->id . '|max:255',
            'password' => 'string|min:8',
            'type' => 'integer|in:1,2',
            'squad_uuid' => 'nullable|string|max:255',
            'company_uuid' => 'nullable|string|max:255',
        ]);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully!',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'deleted_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully!'
        ], Response::HTTP_OK);
    }
}
