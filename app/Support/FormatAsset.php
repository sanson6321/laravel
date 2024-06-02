<?php

namespace App\Support;

class FormatAsset
{
    /**
     * ファイル読み出し
     *
     * @param  string  $path
     * @return string
     */
    public static function asset(string $path)
    {
        if (config('app.env') === 'production') {
            return asset($path);
        }

        return asset($path) . '?v=' . time();
    }
}
