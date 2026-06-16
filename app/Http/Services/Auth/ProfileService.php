<?php

namespace App\Http\Services\Auth;

use App\Http\Repositories\Auth\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileService
{
    public function __construct(
        protected UserRepository $repository
    ) {}


    public function updateProfile(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            if (($data['remove_profile_photo'] ?? false) && $user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
                $data['profile_photo'] = null;
            }

            if (isset($data['profile_photo'])) {
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                $data['profile_photo'] = $data['profile_photo']->store('users/profile', 'public');
            }

            unset($data['remove_profile_photo']);

            return $this->repository->update($user, $data);
        });
    }

    public function updatePassword(User $user, array $data): void
    {
        if (!Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'A senha atual está incorreta.',
            ]);
        }

        $this->repository->update($user, [
            'password' => $data['password'],
        ]);
    }
}