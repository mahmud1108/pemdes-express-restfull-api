<?php

namespace App\Http\Controllers\Auth;

use App\Helper\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(AdminLoginRequest $request)
    {
        $data = $request->validated();
        $admin = Admin::where('email', $data['email'])->first();
        if (!$admin || !Hash::check($data['password'], $admin->password)) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'email or password wrong'
                    ]
                ]
            ], 401));
        }

        $admin->token = Str::uuid()->toString();
        $admin->save();

        return response()->json([
            'data' => [
                'name' => $admin->name,
                'email' => $admin->email,
            ]
        ]);
    }

    public function update(AdminUpdateRequest $request)
    {
        $data = $request->validated();
        $admin = Admin::find(auth()->user()->id);

        if (isset($data['name'])) {
            $admin->name = $data['name'];
        }

        if (isset($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        if (isset($data['email'])) {
            $admin->email  = $data['email'];
        }

        if (isset($data['photo'])) {
            if ($admin->photo != null) {
                FileHelper::instance()->delete($admin->photo);
            }
            FileHelper::instance()->upload($data['photo'], 'admin');
        }

        $admin->update();
        return new AdminResource($admin);
    }

    public function current()
    {
        $admin = Admin::where('id', auth()->user()->id)->select(['name', 'email'])->first();
        return new AdminResource($admin);
    }

    public function logout()
    {
        $admin = Admin::find(auth()->user()->id);

        $admin->token = null;
        $admin->save();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
