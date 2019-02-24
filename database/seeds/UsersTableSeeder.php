<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Super Admin
        $admin = factory(App\User::class)->create([
            'name' => 'VuTrongDong',
            'email' => 'dong.vu@pirago.vn',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm']);
        // mk la secret

        $adminRole = factory(App\Repositories\Roles\Role::class)->create([
            'name' => 'Super admin',
            'slug' => 'superadmin',
            'permissions' => [
                'admin.super-admin' => true
            ]
        ]);

        \DB::statement("INSERT INTO `role_users` (`user_id`, `role_id`) VALUES
            ({$admin->id}, {$adminRole->id})
        ");

        // Admin
        factory(App\Repositories\Roles\Role::class)->create([
            'name' => 'Admin',
            'slug' => 'admin',
            'permissions' => [
                'role.view' => true,
                'role.create' => true,
                'role.update' => true,
                'role.delete' => true,
            ]
        ]);

        factory(App\User::class, 3)->create()->each(function($u) {
            $u->questions()
              ->saveMany(
                  factory(App\Repositories\Questions\Question::class, rand(1, 5))->make()
              )
              ->each(function ($q) {
                $q->answer()->saveMany(factory(App\Repositories\Answers\Answer::class, rand(1, 5))->make());
              });
        });

    }
}
