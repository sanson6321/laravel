<?php

namespace App\Models;

class Customer extends BaseModel
{
    protected $table = 'customers';

    protected $tableName = '顧客';

    protected $fillable = [
        'created_at',
        'created_name',
        'updated_at',
        'updated_name',
        'updated_no',
        // 'deleted_at',
        'name',
        'post_code',
        'prefecture',
        'address',
        'address_sub',
        'gender',
        'note',
    ];

    /**
     * すべて取得
     *
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Collection|Customer[]
     */
    public function getAll(array $data)
    {
        /** @var \Illuminate\Database\Eloquent\Builder */
        $builder = $this->select('customers.*');

        if (isset($data['name'])) {
            $builder->where('customers.name', 'like', '%' . $data['name'] . '%');
        }

        if (isset($data['order']) && str_contains($data['order'], '_')) {
            if ($data['order'] === 'id_asc') {
                $builder->orderBy('customers.id', 'asc');
            }
            if ($data['order'] === 'id_desc') {
                $builder->orderBy('customers.id', 'desc');
            }
            if ($data['order'] === 'updated_at_asc') {
                $builder->orderBy('customers.updated_at', 'asc')
                    ->orderBy('customers.id', 'asc');
            }
            if ($data['order'] === 'updated_at_desc') {
                $builder->orderBy('customers.updated_at', 'desc')
                    ->orderBy('customers.id', 'asc');
            }
        }

        return $builder->get();
    }
}
