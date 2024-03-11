<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    /**
     * create
     *
     * @param  array $attributes
     * @return void
     */
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $created = User::query()->create([
                'name' => data_get($attributes, 'name'),
                'email' => data_get($attributes, 'email'),
                'password' => Hash::make(data_get($attributes, 'password')),
            ]);

            return $created;
        });
    }

    /**
     * update
     *
     * @param  User $model
     * @param  array $attributes
     * @return void
     */
    public function update($model, array $attributes)
    {
        $updated = $model->update([
            'name' => data_get($attributes, 'name', $model->name),
            'email' => data_get($attributes, 'email', $model->email),
        ]);

        if (!$updated) {
            return new \Exception('Failed to update user');
        }

        return $model;
    }

    /**
     * forceDelete
     *
     * @param  User $model
     * @return void
     */
    public function forceDelete($model)
    {
        $deleted = $model->forceDelete();

        if (!$deleted) {
            throw new \Exception('Cannot delete user.');
        }

        return $deleted;
    }
}
