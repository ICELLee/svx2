<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UpdateUserRolesRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q',''));

        $users = User::query()
            ->when($q !== '', fn($qr) => $qr->where(fn($w)=>$w
                ->where('name','like',"%$q%")
                ->orWhere('email','like',"%$q%")
                ->orWhere('public_id','like',"%$q%")
            ))
            ->with('roles:id,name')
            ->orderByDesc('id')
            ->paginate(10)
            ->through(fn($u) => [
                'id' => $u->id,
                'public_id' => $u->public_id,
                'name' => $u->name,
                'email' => $u->email,
                'roles' => $u->roles->pluck('name'),
            ])
            ->withQueryString();

        $allRoles = Role::orderBy('name')->pluck('name');

        return Inertia::render('Admin/Users', [
            'users'    => $users,
            'allRoles' => $allRoles,
            'filters'  => ['q' => $q],
        ]);
    }
    public function regeneratePublicId(User $user)
    {
        $user->public_id = User::generatePublicId();
        $user->save();

        return back()->with('success','Neue öffentliche ID vergeben.');
    }
    public function show(User $user)
    {
        $user->load('roles:id,name');
        $allRoles = Role::orderBy('name')->pluck('name');

        return Inertia::render('Admin/UserShow', [
            'user'     => [
                'id' => $user->id,
                'public_id' => $user->public_id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
                'created_at' => $user->created_at?->toDateTimeString(),
                'updated_at' => $user->updated_at?->toDateTimeString(),
            ],
            'allRoles' => $allRoles,
        ]);
    }


    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        // Keine harte Redirect-Route, sondern einfach back()
        return back()->with('success', 'User aktualisiert.');
    }


    public function updateRoles(UpdateUserRolesRequest $request, User $user)
    {
        $roleIds = Role::whereIn('name', $request->input('roles', []))->pluck('id');
        $user->roles()->sync($roleIds);

        return back()->with('success', 'Rollen gespeichert.');
    }
}
