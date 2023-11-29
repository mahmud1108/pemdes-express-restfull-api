<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Database\Seeders\AdminSeeder;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testLoginSuccess()
    {
        $this->seed(AdminSeeder::class);
        $this->post(
            '/api/admin/login',
            [
                'email' => 'test@gmail.com',
                'password' => 'admin'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'test',
                    'email' => 'test@gmail.com',
                ]
            ]);
    }

    public function testLoginNotFound()
    {
        $this->seed(AdminSeeder::class);
        $this->post(
            '/api/admin/login',
            [
                'email' => 'salahemail@gmail.com',
                'password' => 'admin'
            ]
        )->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'email or password wrong'
                    ]
                ]
            ]);
    }

    public function testLoginErrorValidation()
    {
        $this->seed(AdminSeeder::class);
        $this->post(
            '/api/admin/login',
            [
                'email' => 'asdfasdf',
                'password' => 'admin'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email field must be a valid email address.'
                    ]
                ]
            ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed(AdminSeeder::class);

        $old = Admin::where('name', 'test')->first();
        $this->patch(
            '/api/admin/update',
            [
                'password' => 'ganti'
            ],
            [
                'Authorization' => 'admin'
            ]
        )->assertStatus(200)
            ->assertJson(
                [
                    'data' => [
                        'name' => 'test',
                        'email' => 'test@gmail.com'
                    ]
                ]
            );
        $new = Admin::where('name', 'test')->first();

        self::assertNotEquals($new->password, $old->password);
    }

    public function testUpdatePhoto()
    {
        $this->seed(AdminSeeder::class);
        $old = Admin::where('name', 'test')->first();

        $this->patch(
            '/api/admin/update',
            [
                'photo' => UploadedFile::fake()->create('file.jpg', 1024)
            ],
            [
                'Authorization' => 'admin'
            ]
        )->assertStatus(200)->json();
        $new = Admin::where('name', 'test')->first();

        self::assertNotEquals($new->photo, $old->photo);
    }


    public function testUpdateErrorValidationi()
    {
        $this->seed(AdminSeeder::class);

        $this->patch('/api/admin/update', [
            'email' => 'asdasddddddddf'
        ], [
            'Authorization' => 'admin'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email field must be a valid email address.'
                    ]
                ]
            ]);
    }

    public function testLogoutSuccess()
    {
        $this->seed(AdminSeeder::class);
        $this->delete('/api/admin/logout', headers: [
            'Authorization' => 'admin'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }

    public function testGetCurrentUser()
    {
        $this->seed(AdminSeeder::class);

        $this->get('/api/admin/current', [
            'Authorization' => 'admin'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'test',
                    'email' => 'test@gmail.com'
                ]
            ]);
    }

    public function testGetCurrentUnauthorize()
    {
        $this->seed(AdminSeeder::class);

        $this->get('/api/admin/current', [
            'Authorization' => 'token salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Unauthorized'
                    ]
                ]
            ]);
    }
}
