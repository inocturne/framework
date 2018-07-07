<?php

declare(strict_types=1);

/*
 * This file is part of the ************************ package.
 * _____________                           _______________
 *  ______/     \__  _____  ____  ______  / /_  _________
 *   ____/ __   / / / / _ \/ __`\/ / __ \/ __ \/ __ \___
 *    __/ / /  / /_/ /  __/ /  \  / /_/ / / / / /_/ /__
 *      \_\ \_/\____/\___/_/   / / .___/_/ /_/ .___/
 *         \_\                /_/_/         /_/
 *
 * The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
 * (c) 2010-2018 http://queryphp.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leevel\Filesystem;

use Closure;
use DirectoryIterator;
use Leevel\Support\TMacro;
use RuntimeException;

/**
 * File System Object 管理.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2017.04.05
 *
 * @version 1.0
 */
class Fso
{
    use TMacro;

    /**
     * 取得文件内容.
     *
     * @param string $path
     *
     * @return string
     */
    public static function fileContents($path)
    {
        if (static::isFile($path)) {
            return file_get_contents($path);
        }

        throw new RuntimeException(
            sprintf('File %s does not exist', $path)
        );
    }

    /**
     * 创建目录.
     *
     * @param string $dir
     * @param number $mode
     *
     * @return true|void
     */
    public static function createDirectory($dir, $mode = 0777)
    {
        if (is_dir($dir)) {
            return;
        }

        mkdir($dir, $mode, true);

        return true;
    }

    /**
     * 删除目录.
     *
     * @param string $dir
     * @param bool   $recursive
     */
    public static function deleteDirectory($dir, $recursive = false)
    {
        if (!file_exists($dir) || !is_dir($dir)) {
            return;
        }

        if (!$recursive) {
            rmdir($dir);
        } else {
            $instance = new DirectoryIterator($dir);

            foreach ($instance as $file) {
                if ($file->isDot()) {
                    continue;
                }

                if ($file->isFile()) {
                    if (!unlink($file->getRealPath())) {
                        return;
                    }
                } elseif ($file->idir()) {
                    static::deleteDirectory($file->getRealPath(), $recursive);
                }
            }

            rmdir($dir);
        }
    }

    /**
     * 复制目录.
     *
     * @param string $sourcePath
     * @param string $targetPath
     * @param array  $filter
     */
    public static function copyDirectory($sourcePath, $targetPath, $filter = [])
    {
        if (!is_dir($sourcePath)) {
            return;
        }

        if (file_exists($targetPath)) {
            return;
        }

        $instance = new DirectoryIterator($sourcePath);

        foreach ($instance as $file) {
            if ($file->isDot() ||
                in_array($file->getFilename(), $filter, true)) {
                continue;
            }

            $newPath = $targetPath.'/'.$file->getFilename();

            if ($file->isFile()) {
                if (!is_dir($newPath)) {
                    static::createDirectory(dirname($newPath));
                }

                if (!copy($file->getRealPath(), $newPath)) {
                    return;
                }
            } elseif ($file->idir()) {
                if (!static::copyDirectory($file->getRealPath(), $newPath)) {
                    return;
                }
            }
        }
    }

    /**
     * 浏览目录.
     *
     * @param string   $path
     * @param \Closure $cal
     * @param array    $filter
     */
    public static function listDirectory($path, Closure $cal, array $filter = [])
    {
        if (!is_dir($path)) {
            return;
        }

        $instance = new DirectoryIterator($path);

        foreach ($instance as $file) {
            if ($file->isDot() ||
                in_array($file->getFilename(), $filter, true)) {
                continue;
            }

            call_user_func($cal, $file);

            if ($file->idir()) {
                static::listDirectory(
                    $file->getPath().'/'.$file->getFilename(),
                    $cal,
                    $filter
                );
            }
        }
    }

    /**
     * 只读取一级目录.
     *
     * @param string $dir
     * @param string $returnType
     * @param bool   $fullpath
     * @param array  $filter
     * @param array  $allowedExt
     * @param array  $filterExt
     *
     * @return array
     */
    public static function lists($dir, $returnType = 'dir', bool $fullpath = false, array $filter = [], array $allowedExt = [], array $filterExt = [])
    {
        $filter = array_merge([
            '.svn',
            '.git',
            'node_modules',
            '.gitkeep',
        ], $filter);

        $returnData = [
            'file' => [],
            'dir'  => [],
        ];

        if (is_dir($dir)) {
            $arrFiles = [];

            $instance = new DirectoryIterator($dir);

            foreach ($instance as $file) {
                if ($file->isDot() ||
                    in_array($file->getFilename(), $filter, true)) {
                    continue;
                }

                if ($file->idir() && in_array($returnType, [
                    'dir',
                    'both',
                ], true)) {
                    $returnData['dir'][] = $fullpath ?
                        $file->getRealPath() :
                        $file->getFilename();
                }

                $ext = static::getExtension($file->getFilename(), 2);

                if ($file->isFile() &&
                    in_array($returnType, [
                        'file',
                        'both',
                    ], true) &&
                    (!$filterExt || !in_array($ext, $filterExt, true)) &&
                    (!$allowedExt || in_array($ext, $allowedExt, true))) {
                    $returnData['file'][] = $fullpath ?
                        $file->getRealPath() :
                        $file->getFilename();
                }
            }

            if ('file' === $returnType) {
                return $returnData['file'];
            }

            if ('dir' === $returnType) {
                return $returnData['dir'];
            }

            return $returnData;
        }

        return [];
    }

    /**
     * 整理目录斜线风格
     *
     * @param string $path
     * @param bool   $unix
     *
     * @return string
     */
    public static function tidyPath($path, $unix = true)
    {
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('|/+|', '/', $path);
        $path = str_replace(':/', ':\\', $path);

        if (!$unix) {
            $path = str_replace('/', '\\', $path);
        }

        return rtrim($path, '\\/');
    }

    /**
     * 格式化文件或者目录为 Linux 风格
     *
     * @param string $path
     * @param bool   $windowsWithLetter
     *
     * @return string
     */
    public static function tidyPathLinux($path, bool $windowsWithLetter = false)
    {
        $path = ltrim(static::tidyPath($path, true), '//');

        if (false !== strpos($path, ':\\')) {
            $temp = explode(':\\', $path);

            $path = (true === $windowsWithLetter ?
                strtolower($temp[0]).'/' :
                '').
                $temp[1];
        }

        return '/'.$path;
    }

    /**
     * 判断是否为绝对路径.
     *
     * @param string $path
     *
     * @return bool
     */
    public static function isAbsolute($path)
    {
        return preg_match('/^(\/|[a-z]:)/i', $path);
    }

    /**
     * 根据 ID 获取打散目录.
     *
     * @param int $dataId
     *
     * @return array
     */
    public static function distributed($dataId)
    {
        $dataId = abs((int) $dataId);
        $dataId = sprintf('%09d', $dataId); // 格式化为 9 位数，前面不够填充 0

        return [
            substr($dataId, 0, 3).'/'.
                substr($dataId, 3, 2).'/'.
                substr($dataId, 5, 2).'/',
            substr($dataId, -2),
        ];
    }

    /**
     * 新建文件.
     *
     * @param $path string
     * @param $mode=0766 int
     *
     * @return bool
     */
    public static function createFile($path, $mode = 0766)
    {
        $dir = dirname($path);

        if (is_file($dir)) {
            throw new InvalidArgumentException(
                'Dir cannot be a file.'
            );
        }

        if (!file_exists($dir) &&
            static::createDirectory($dir)) {
            throw new RuntimeException(
                sprint('Create dir %s failed.', $dir)
            );
        }

        if ($file = fopen($path, 'a')) {
            chmod($path, $mode);

            return fclose($file);
        }

        throw new RuntimeException(
            sprint('Create file %s failed.', $path)
        );
    }

    /**
     * 获取上传文件扩展名.
     *
     * @param string $fileName 文件名
     * @param int    $case     格式化参数 0 默认，1 转为大小 ，转为大小
     *
     * @return string
     */
    public static function getExtension($fileName, int $case = 0)
    {
        $fileName = pathinfo($fileName, PATHINFO_EXTENSION);

        if (1 === $case) {
            return strtoupper($fileName);
        }

        if (2 === $case) {
            return strtolower($fileName);
        }

        return $fileName;
    }

    /**
     * 获取文件名字.
     *
     * @param string $path
     *
     * @return string
     */
    public static function getName($path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }
}
