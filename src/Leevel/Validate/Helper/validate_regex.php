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

namespace Leevel\Validate\Helper;

use InvalidArgumentException;

/**
 * 数据是否满足正则条件.
 *
 * @param mixed $value
 * @param array $parameter
 *
 * @return bool
 */
function validate_regex($value, array $parameter): bool
{
    if (!is_scalar($value)) {
        return false;
    }

    $value = (string) ($value);

    if (!array_key_exists(0, $parameter)) {
        throw new InvalidArgumentException('Missing the first element of parameter.');
    }

    return preg_match($parameter[0], $value) > 0;
}

class validate_regex
{
}
