<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Repository\UserRepositoryInterface;

use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{   
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
       $this->userRepository = $userRepository;
    }

    public function show(Request $request)
    {
        try
        {
            $id = $request->route('id');
            return (new UserResource($this->userRepository->getById($id)))
            ->response()
            ->setStatusCode(OK);
        }
        
        catch(Exception $ex)
        {
            abort(SERVER_ERROR, 'Server error');
        }     
    }

    public function update(int $id, Request $request)
    {
        try {
            $user = User::findOrFail($id);
    
            $validated = $request->validate([
                'current_password' => ['required'],
                'new_password' => ['required', 'string', 'confirmed', 'max:255',
                ],
            ]);
    
            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'message' => 'Mot de passe actuel incorrect.'
                ], 403);
            }
    
            $validated['password'] = bcrypt($validated['new_password']);
    
            return (new UserResource($this->userRepository->update($id, $validated)))
                ->response()
                ->setStatusCode(OK);
    
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'server error');
        }
    }
}