<?php

namespace App\Support;

class FormatOrder
{
    /**
     * 並び替え情報の取得
     *
     * @param  string  $key
     * @param  string  $params
     * @return array
     */
    public static function get(string $key, string $inputKey = 'order')
    {
        $prefix = $key . '_';
        $active = str_contains(old($inputKey), $prefix);
        $order = old($inputKey) === $prefix . 'asc' ? $prefix . 'desc' : $prefix . 'asc';
        $parameters = array_merge(old(), [$inputKey => $order]);

        return [$active, $order, $parameters];
    }
}
