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

/**
 * 是否可接受的.
 *
 * @param mixed $value
 *
 * @return bool
 */
function validate_accepted($value): bool
{
    return validate_required($value) &&
        in_array($value, [
            'yes',
            'on',
            't',
            '1',
            1,
            true,
            'true',
        ], true);
}

// @codeCoverageIgnoreStart
if (!function_exists('Leevel\\Validate\\Helper\\validate_required')) {
    include __DIR__.'/validate_required.php';
}
// @codeCoverageIgnoreEnd