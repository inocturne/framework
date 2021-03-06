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
 * (c) 2010-2019 http://queryphp.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leevel\Filesystem\Fso;

use RuntimeException;

/**
 * 创建文件.
 *
 * @param string $path
 * @param string $content
 * @param int    $mode
 */
function create_file(string $path, ?string $content = null, int $mode = 0666): void
{
    $dirname = dirname($path);

    if (is_file($dirname)) {
        $e = sprintf('Dir `%s` cannot be a file.', $dirname);

        throw new RuntimeException($e);
    }

    create_directory($dirname);

    if (!$file = fopen($path, 'a')) {
        $e = sprintf('Dir `%s` is not writeable.', $dirname);

        throw new RuntimeException($e);
    }

    chmod($path, $mode & ~umask());
    fclose($file);

    if ($content) {
        file_put_contents($path, $content);
    }
}

class create_file
{
}

// import fn.
class_exists(create_directory::class);
