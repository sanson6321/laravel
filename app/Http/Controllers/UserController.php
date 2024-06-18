<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $userList = $this->user->getAll($input);

        $data = [
            'userList' => $userList,
        ];
        return view('user.index', $data);
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');

        $user = $this->user->find($id);

        $data = [
            'user' => $user,
        ];
        return view('user.edit', $data);
    }

    public function upsert(Request $request)
    {
        $input = $request->validate([
            'id' => '',
            'updated_no' => '',
            'name' => 'required',
            'email' => 'required',
            'password' => '',
        ]);

        DB::beginTransaction();
        try {
            $user = $this->user->upsert($input);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
        session()->flash('message_success', '保存しました');

        return response()->json($user);
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        DB::beginTransaction();
        try {
            $user = $this->user->find($id);
            $user->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
        session()->flash('message_success', '削除しました');

        return response()->json(true);
    }
}
