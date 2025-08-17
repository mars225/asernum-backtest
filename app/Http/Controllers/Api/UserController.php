<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $users = $this->service->listUsers($request->get('per_page', 15), $request->only(['name', 'email', 'role']));
        return response()->json($users);
    }

    // Création utilisateur (admin)
    public function store(UserRequest $request)
    {
        $user = $this->service->createUser($request->validated());
        return response()->json(['message' => 'Utilisateur créé', 'data' => $user], 201);
    }

    // Afficher utilisateur
    public function show($id)
    {
        try {
            $user = $this->service->getUser($id);
            return response()->json(['message' => 'Succès', 'data' => $user]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    // Mise à jour utilisateur
    public function update(UserRequest $request, $id)
    {
        try {
            $user = $this->service->updateUser($id, $request->validated());
            return response()->json(['message' => 'Utilisateur mis à jour', 'data' => $user]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    // Suppression utilisateur
    public function destroy($id)
    {
        try {
            $this->service->deleteUser($id);
            return response()->json(['message' => 'Utilisateur supprimé']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    // Register client
    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request->validated());
        $token = $user->createToken('auth_token')->plainTextToken;
        Log::channel('user')->info('Inscription réussie pour : '. $request->login);
        return response()->json([
            'message' => 'Inscription réussie',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    // Login (clients et admins)
    public function login(LoginRequest $request)
    {
        try {
            $user = $this->service->login($request->login, $request->password);
            $tokenName = $user->role === 'customer' ? 'customer_auth_token' : 'admin_auth_token';
            $token = $user->createToken($tokenName)->plainTextToken;
            Log::channel('user')->info('Connexion réussie pour : '. $request->login);
            return response()->json([
                'message' => 'Connexion réussie',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => $user->role
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        Log::channel('user')->info('Déconnexion réussie pour : '. $request->login);
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    // Profil connecté
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
