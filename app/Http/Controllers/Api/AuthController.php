<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(UserStoreRequest $request){
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role_id' => 2
        ]);

        $defaultMeals = [
            ['name' => 'breakfast', 'entries_limit' => 3],
            ['name' => 'lunch', 'entries_limit' => 3],
            ['name' => 'snack', 'entries_limit' => 5],
            ['name' => 'dinner', 'entries_limit' => 3],
        ];

        try {
            DB::beginTransaction();

            $user->save();

            foreach ($defaultMeals as $meal) {
                $user->meals()->create($meal);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
        } finally {
            DB::commit();
        }

        return response()->json([
            'message' => 'User created',
            'user' => new UserResource($user)
        ], 201);
    }
}
