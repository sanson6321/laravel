<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;

    public function __construct(
        User $user,
    ) {
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $input = $request->only([
            'name',
            'order',
        ]);
        session()->flash('_old_input', $input);

        $userList = $this->user->getAll($input);

        $data = [
            'userList' => $userList,
        ];
        return view('user.index', $data);
    }
}
