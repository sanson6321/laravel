<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * ベースモデル
 * 作成もしく更新する際に自動的にユーザー名を書き込む
 */
class BaseModel extends Model
{
    use SoftDeletes;

    /**
     * テーブル名
     */
    protected $tableName = '';

    /**
     * 作成
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return Model|array
     */
    public function create(array $attributes = [], array $options = [])
    {
        $user = Auth::user();
        $isMulti = $this->isMultiArray($attributes);
        $createdAttributes = [
            'created_at' => now(),
            'created_name' => $user?->name ?? 'システム',
            'updated_at' => now(),
            'updated_name' => $user?->name ?? 'システム',
            'updated_no' => 0,
        ];
        if ($isMulti) {
            $callback = fn (array $attribute): array => array_merge($attribute, $createdAttributes);
            $attributes = array_map($callback, $attributes);
        } else {
            unset($attributes['id']);
            unset($attributes['updated_no']);
            $attributes = array_merge($attributes, $createdAttributes);
        }

        try {
            LogModel::write(LogModel::CREATE_TYPE, $this->tableName, $attributes, []);
            if ($isMulti) {
                $dataList = array_chunk($attributes, 300);
                foreach ($dataList as $data) {
                    $isCreated = parent::insert($data);
                    if ($isCreated !== true) {
                        throw new \Exception('データを作成できませんでした', 400);
                    }
                }
            } else {
                $isCreated = parent::fill($attributes)->save($options);
                if ($isCreated !== true) {
                    throw new \Exception('データを作成できませんでした', 400);
                }
            }
        } catch (\Exception $e) {
            Log::error('Database Rollback', ['message' => $e->getMessage()]);
            throw new \Exception('データを作成できませんでした', 500);
        }

        if ($isMulti) {
            return $attributes;
        }
        return $this;
    }

    /**
     * 多次元配列かどうか
     *
     * @param  array  $array
     * @return bool
     */
    private function isMultiArray(array $array)
    {
        $i = 0;
        foreach (array_keys($array) as $key) {
            if ($key !== $i++) {
                return false;
            }
        }
        return true;
    }

    /**
     * 更新
     * 単一モデルのみ
     * 更新する際には正しいupdated_noが必要
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return Model
     */
    public function update(array $attributes = [], array $options = [])
    {
        if (!$this->exists) {
            throw new \Exception('データが存在しません', 400);
        }

        if (!isset($attributes['updated_no']) || (int) $attributes['updated_no'] !== $this->updated_no) {
            throw new \Exception('データが既に更新されています', 400);
        }

        $user = Auth::user();
        $updatedAttributes = [
            'updated_name' => $user?->name ?? 'システム',
            'updated_no' => $attributes['updated_no'] + 1,
        ];
        $attributes = array_merge($attributes, $updatedAttributes);

        try {
            LogModel::write(LogModel::UPDATE_TYPE, $this->tableName, $attributes, $this->toArray());
            $isUpdated = parent::update($attributes, $options);
            if ($isUpdated !== true) {
                throw new \Exception('データを更新できませんでした', 400);
            }
        } catch (\Exception $e) {
            Log::error('Database Rollback', ['message' => $e->getMessage()]);
            throw new \Exception('データを更新できませんでした', 500);
        }
        return $this;
    }

    /**
     * 削除
     * 単一モデルのみ
     *
     * @return bool
     */
    public function delete()
    {
        try {
            LogModel::write(LogModel::DELETE_TYPE, $this->tableName, [], $this->toArray());
            $isDeleted = parent::delete();
            if ($isDeleted !== true) {
                throw new \Exception('データを削除できませんでした', 400);
            }
        } catch (\Exception $e) {
            Log::error('Database Rollback', ['message' => $e->getMessage()]);
            throw new \Exception('データを削除できませんでした', 500);
        }
        return true;
    }

    /**
     * 作成または更新
     * 単一モデルのみ
     *
     * @param  array  $attributes
     * @return Model
     */
    public function upsert(array $attributes)
    {
        if (isset($attributes['id'])) {
            $model = $this->find($attributes['id']);
            $model->update($attributes);
        } else {
            $model = $this->create($attributes);
        }

        return $model;
    }
}
