<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/signup",
     *     summary="Enregistre un nouvel utilisateur",
     *     description="Permet d’enregistrer un nouvel utilisateur. Limite : 5 tentatives par minute (throttling).",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"login","password","email","first_name","last_name"},
     *             @OA\Property(property="login", type="string", example="Masmissien"),
     *             @OA\Property(property="password", type="string", example="securepassword"),
     *             @OA\Property(property="email", type="string", format="email", example="masmis@example.com"),
     *             @OA\Property(property="first_name", type="string", example="Masmis"),
     *             @OA\Property(property="last_name", type="string", example="Lemasmis")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur enregistré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Utilisateur enregistré avec succès"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function register(Request $request){
        $validate = $request->validate([
            'login' => ['required', 'unique:users,login'],
            'password' => ['required'],
            'email' => ['required', 'email'],
            'last_name' => ['required'],
            'first_name' => ['required'],
        ]);

        $validate['password'] = bcrypt($validate['password']);
        

        $user = User::create($validate);

        return response()->json([
            'message' => 'Utilisateur enregistré avec succès',
            'user' => $user,
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/signin",
     *     summary="Connecte un utilisateur et retourne un token",
     *     description="Permet à un utilisateur de se connecter. Limite : 5 tentatives par minute (throttling).",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"login","password"},
     *             @OA\Property(property="login", type="string", example="Masmissien"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentification réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Authentification réussie"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Identifiants invalides",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login ou mot de passe incorrect")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Login ou mot de passe incorrect',
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Authentification réussie',
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Déconnecte l'utilisateur authentifié",
     *     description="Déconnecte l’utilisateur actuellement authentifié. Requiert un token d’authentification. Limite : 5 tentatives par minute (throttling).",
     *     tags={"Authentification"},
     *     @OA\Response(
     *         response=204,
     *         description="Déconnexion réussie"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->noContent();
    }
}
