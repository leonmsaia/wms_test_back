<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Class AdminUserSeeder
 *
 * Seeds the database with an initial administrator account for testing and development purposes.
 *
 * @author  Leon. M. Saia <leonmsaia@gmail.com>
 * @since   2025-08-12
 * @package Database\Seeders
 */
class AdminUserSeeder extends Seeder
{
    /**
     * Executes the database seeding logic.
     *
     * @return void
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'nombre'   => 'Admin',
                'apellido' => 'Root',
                'rol'      => 'Admin',
                'password' => 'secret1234',
            ]
        );
    }
}
