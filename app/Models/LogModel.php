<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * ログモデル
 */
class LogModel extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'created_at',
        'updated_at',
        'id',
        'user_id',
        'user_name',
        'type',
        'table_name',
        'content',
        'content_old',
    ];

    /** 作成 */
    const CREATE_TYPE = 1;

    /** 更新 */
    const UPDATE_TYPE = 2;

    /** 削除 */
    const DELETE_TYPE = 3;

    /**
     * 書き込み
     *
     * @param  int  $type
     * @param  string  $tableName
     * @param  array  $content
     * @param  array  $contentOld
     */
    public static function write(int $type, string $tableName, array $content, array $contentOld)
    {
        if (config('database.log') !== true) {
            return;
        }
        $user = Auth::user();
        self::create([
            'type' => $type,
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'システム',
            'table_name' => $tableName,
            'content' => empty($content) ? null : json_encode($content, JSON_UNESCAPED_UNICODE),
            'content_old' => empty($contentOld) ? null : json_encode($contentOld, JSON_UNESCAPED_UNICODE),
        ]);
    }

    /**
     * 一覧取得
     *
     * @param  array  $data
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection|LogModel[]
     */
    public function getList(array $data, int $limit = 30)
    {
        $builder = self::select('logs.*')
            ->limit($limit)
            ->orderBy('id', 'desc');

        if (isset($data['id'])) {
            // 最終ID
            $builder->where('id', '<', $data['id']);
        }
        if (isset($data['table_name'])) {
            $builder->where('table_name', '=', $data['table_name']);
        }
        if (isset($data['keyword'])) {
            $keywords = explode(' ', $data['keyword']);
            foreach ($keywords as $keyword) {
                $keyword = '%' . $keyword . '%';
                $builder->where(function ($query) use ($keyword) {
                    $query->where('content', 'like', $keyword)
                        ->orWhere('content_old', 'like', $keyword)
                        ->orWhere('user_name', 'like', $keyword);
                });
            }
        }
        if (isset($data['start_at'])) {
            $builder->where('created_at', '>=', $data['start_at']);
        }
        if (isset($data['end_at'])) {
            $builder->where('created_at', '<=', $data['end_at']);
        }

        return $builder->get();
    }

    /**
     * テーブル名一覧取得
     *
     * @return \Illuminate\Database\Eloquent\Collection|LogModel[]
     */
    public function getTableNameList()
    {
        $builder = self::select('logs.table_name')
            ->groupBy('logs.table_name')
            ->orderBy('logs.table_name', 'asc');

        return $builder->get();
    }
}
