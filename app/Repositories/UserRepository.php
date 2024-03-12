<?php

namespace App\Repositories;

use App\Events\Models\User\UserCreated;
use App\Events\Models\User\UserDeleted;
use App\Events\Models\User\UserUpdated;
use App\Exceptions\GeneralJsonException;
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
            throw_if(!$created, GeneralJsonException::class, 'Failed to create user!');
            event(new UserCreated($created));
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
        return DB::transaction(function () use ($model, $attributes) {
            $updated = $model->update([
                'name' => data_get($attributes, 'name', $model->name),
                'email' => data_get($attributes, 'email', $model->email),
            ]);

            throw_if(!$updated, GeneralJsonException::class, 'Failed to update user!');
            event(new UserUpdated($model));
            return $model;
        });
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

        throw_if(!$deleted, GeneralJsonException::class, 'Cannot delete user!');
        event(new UserDeleted($model));
        return $deleted;
    }
}
