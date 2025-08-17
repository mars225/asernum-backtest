<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    // CRUD
    public function listUsers($perPage = 15, $filters = [])
    {
        return $this->repository->getAll($perPage, $filters);
    }

    public function getUser($id)
    {
        $user = $this->repository->findById($id);
        if (!$user) {
            throw new ModelNotFoundException("Utilisateur non trouvé");
        }
        return $user;
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->repository->create($data);
    }

    public function updateUser($id, array $data)
    {
        $user = $this->repository->findById($id);
        if (!$user) {
            throw new ModelNotFoundException("Utilisateur non trouvé");
        }
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->repository->update($user, $data);
    }

    public function deleteUser($id)
    {
        $user = $this->repository->findById($id);
        if (!$user) {
            throw new ModelNotFoundException("Utilisateur non trouvé");
        }
        $this->repository->delete($user);
    }

    // Auth
    public function login($login, $password)
    {
        $user = $this->repository->findByLogin($login);
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Identifiants incorrects'],
            ]);
        }
        return $user;
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'customer';
        return $this->repository->create($data);
    }
}
