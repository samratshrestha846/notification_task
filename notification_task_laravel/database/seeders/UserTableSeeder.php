<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * @return User|Collection|Model
     */
    public function run(): User|Collection|Model
    {
        return User::factory(10)->create();
    }
}
