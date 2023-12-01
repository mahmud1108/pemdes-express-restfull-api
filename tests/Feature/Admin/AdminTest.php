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
            '/api/admin',
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
            '/api/admin',
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
            '/api/admin',
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

        $old = Admin::where('token', 'admin')->first();
        $this->patch(
            '/api/admin',
            [
                'name' => 'admin',
            ],
            [
                'Authorization' => 'admin'
            ]
        )->assertStatus(200)
            ->assertJson(
                [
                    'data' => [
                        'name' => 'admin',
                        'email' => 'test@gmail.com'
                    ]
                ]
            );
        $new = Admin::where('token', 'admin')->first();

        self::assertNotEquals($new->name, $old->name);
    }

    public function testUpdatePhoto()
    {
        $this->seed(AdminSeeder::class);
        $old = Admin::where('name', 'test')->first();

        $this->patch(
            '/api/admin',
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

        $this->patch('/api/admin', [
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
        $this->delete('/api/admin', headers: [
            'Authorization' => 'admin'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }

    public function testGetCurrentUser()
    {
        $this->seed(AdminSeeder::class);

        $this->get('/api/admin', [
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

        $this->get('/api/admin', [
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
