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
 * 处于 between 范围，不包含等于.
 *
 * @param mixed $datas
 * @param array $parameter
 *
 * @return bool
 */
function validate_between($datas, array $parameter): bool
{
    check_parameter_length('between', $parameter, 2);

    return $datas > $parameter[0] && $datas < $parameter[1];
}

// @codeCoverageIgnoreStart
if (!function_exists('Leevel\\Validate\\Helper\\check_parameter_length')) {
    include __DIR__.'/check_parameter_length.php';
}
// @codeCoverageIgnoreEnd
