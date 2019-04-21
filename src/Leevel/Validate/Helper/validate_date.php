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

use DateTime;

/**
 * 是否为日期
 *
 * @param mixed $value
 *
 * @return bool
 */
function validate_date($value): bool
{
    if ($value instanceof DateTime) {
        return true;
    }

    if (!is_scalar($value)) {
        return false;
    }

    if (false === strtotime((string) ($value))) {
        return false;
    }

    $value = date_parse($value);

    if (false === $value['year'] ||
        false === $value['month'] ||
        false === $value['day']) {
        return false;
    }

    return checkdate($value['month'], $value['day'], $value['year']);
}