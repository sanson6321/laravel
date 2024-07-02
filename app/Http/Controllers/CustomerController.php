<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    private $customer;

    public function __construct(
        Customer $customer,
    ) {
        $this->customer = $customer;
    }

    public function index(Request $request)
    {
        $input = $request->only([
            'name',
            'order',
        ]);

        $customerList = $this->customer->getAll($input);

        $data = [
            'customerList' => $customerList,
        ];
        return view('customer.index', $data);
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');

        $customer = $this->customer->find($id);

        $data = [
            'customer' => $customer,
        ];
        return view('customer.edit', $data);
    }

    public function upsert(Request $request)
    {
        $input = $request->validate([
            'id' => '',
            'updated_no' => '',
            'name' => 'required',
            'post_code' => 'required|max:10',
            'prefecture' => 'required',
            'address' => 'required|max:200',
            'address_sub' => 'max:200',
            'gender' => 'required',
            'note' => '',
        ]);

        DB::beginTransaction();
        try {
            $customer = $this->customer->upsert($input);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
        session()->flash('message_success', '保存しました');

        return response()->json($customer);
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        DB::beginTransaction();
        try {
            $customer = $this->customer->find($id);
            $customer->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
        session()->flash('message_success', '削除しました');

        return response()->json(true);
    }
}
