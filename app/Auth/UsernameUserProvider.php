<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class UsernameUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (
            empty($credentials) ||
            (count($credentials) === 1 &&
                array_key_exists('password', $credentials))
        ) {
            return;
        }

        // For the given user credentials, find the user
        $query = $this->createModel()->newQuery();
        foreach ($credentials as $key => $value) {
            if ($key === 'username') {
                $query->where($key, $value);
            } elseif (!str_contains($key, 'password')) {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }
}
