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

namespace Tests\Validate\Validator;

use Leevel\Validate\Validator;
use Tests\TestCase;

/**
 * notEqual test.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.08.09
 *
 * @version 1.0
 */
class NotEqualTest extends TestCase
{
    /**
     * @dataProvider baseUseProvider
     *
     * @param mixed $value
     * @param mixed $parameter
     */
    public function testBaseUse($value, $parameter)
    {
        $validate = new Validator(
            [
                'name' => $value,
            ],
            [
                'name'     => 'not_equal:'.$parameter,
            ]
        );

        $this->assertTrue($validate->success());
    }

    public function baseUseProvider()
    {
        return [
            [2, 3],
            ['1.1', '1.5'],
            ['1.5', '2'],
            ['1.5', '3'],
            ['1.5', '4'],
            ['1.5', '4.5'],
            ['a', 'b'],
            ['a', 'c'],
            ['bar', 'foo'],
            [1, '1'],
            [23, '23'],
        ];
    }

    /**
     * @dataProvider badProvider
     *
     * @param mixed $value
     * @param mixed $parameter
     */
    public function testBad($value, $parameter)
    {
        $validate = new Validator(
            [
                'name' => $value,
            ],
            [
                'name'     => 'not_equal:'.$parameter,
            ]
        );

        $this->assertFalse($validate->success());
    }

    public function badProvider()
    {
        return [
            [(string) (3), 3],
            ['1.5', '1.5'],
            ['1', true],
            ['', false],
        ];
    }
}
