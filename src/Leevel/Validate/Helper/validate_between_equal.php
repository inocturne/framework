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
 * 处于 betweenEqual 范围，包含全等.
 *
 * @param mixed $value
 * @param array $parameter
 *
 * @return bool
 */
function validate_between_equal($value, array $parameter): bool
{
    if (!array_key_exists(0, $parameter) ||
        !array_key_exists(1, $parameter)) {
        throw new InvalidArgumentException('Missing the first or second element of parameter.');
    }

    return ($value > $parameter[0] || $value === $parameter[0]) &&
        ($value < $parameter[1] || $value === $parameter[1]);
}

class validate_between_equal
{
}
