<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\UserUpdateDTO;
use App\Http\Requests\UserAdmRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        $users = $user->select($columns = ['id', 'name', 'email', 'adm', 'status', 'created_at'])
                        ->orderBy('id')
                        ->paginate(15);

        return view('administrator.user.users', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, int $id)
    {
        $users = $user->find($id, ['id', 'name', 'email', 'adm', 'status']);

        return view('administrator.user.alterUser', compact('users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, int $id, UserAdmRequest $request)
    {
        $users = $user->find($id);
        $users->update(UserUpdateDTO::userDTO($request));

        return redirect()
                ->route('users')
                ->with(["message" => "sucess"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, int $id)
    {
        $user->destroy($id);

        return redirect()
                ->route('users')
                ->with(["status" => "200", "message" => "sucess"]);
    }
}
