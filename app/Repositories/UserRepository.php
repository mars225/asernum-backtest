<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAll($perPage = 15, $filters = [])
    {
        $query = User::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }
        if (!empty($filters['email'])) {
            $query->where('email', 'like', "%{$filters['email']}%");
        }
        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function findById($id)
    {
        return User::find($id);
    }

    public function findByLogin($login)
    {
        return User::where('email', $login)
                    ->orWhere('username', $login)
                    ->first();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user)
    {
        $user->delete();
    }
}
