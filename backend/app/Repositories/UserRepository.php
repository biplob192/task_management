<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface {
    public function __construct(User $user) {
        parent::__construct($user);
    }

    // You can add additional methods specific to the User model here
}
