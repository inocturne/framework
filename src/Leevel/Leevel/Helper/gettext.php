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

namespace Leevel\Leevel\Helper;

use Leevel\I18n\II18n;
use Leevel\Leevel\App;

/**
 * 获取语言.
 *
 * @param string $text
 * @param array  $arr
 *
 * @return string
 */
function gettext(string $text, ...$arr): string
{
    $service = App::singletons()
        ->make(II18n::class);

    return $service->gettext($text, ...$arr);
}