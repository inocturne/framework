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

namespace Leevel\Encryption\Safe;

if (!function_exists('Leevel\\Encryption\\Safe\\clean_js')) {
    include_once __DIR__.'/clean_js.php';
}

/**
 * 过滤十六进制字符串.
 *
 * @param stirng $strings
 *
 * @return string
 */
function clean_hex(string $strings): string
{
    return preg_replace('![\\][xX]([A-Fa-f0-9]{1,3})!', '', $strings);
}