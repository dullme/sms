<?php

namespace App\Services;

class FileService
{

    /**
     * 保存文件
     * @param $file
     * @param string $path
     * @return bool|\Illuminate\Support\Collection
     */
    public static function saveFile($file, $path = '')
    {
        if (is_null($file)) {

            return false;
        }
        $path = $path ? $path . '/' : $path;
        $destinationPath = 'uploads/' . $path;

        $type = getImageType($file);
        if (!$type) {

            return [
                'status'  => 'FAIL',
                'message' => '文件类型错误',
            ];
        }

        $filename = time() . rand(100000, 999999) . $type;
        $file->move($destinationPath, $filename);

        return [
            'status' => 'SUCCESS',
            'path'   => $destinationPath . $filename,
        ];
    }

}