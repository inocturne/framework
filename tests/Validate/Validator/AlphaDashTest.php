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
use stdClass;
use Tests\TestCase;

/**
 * alphaDash test.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.08.09
 *
 * @version 1.0
 */
class AlphaDashTest extends TestCase
{
    /**
     * @dataProvider baseUseProvider
     *
     * @param mixed $value
     */
    public function testBaseUse($value)
    {
        $validate = new Validator(
            [
                'name' => $value,
            ],
            [
                'name'     => 'alpha_dash',
            ]
        );

        $this->assertTrue($validate->success());
    }

    public function baseUseProvider()
    {
        return [
            ['abc'],
            ['ABC'],
            ['123abc'],
            ['4ABC'],
            ['A44bc'],
            ['ab1c'],
            ['AB2C'],
            ['Ab3c'],
            ['--abc'],
            ['A_BC'],
            ['123a_bc'],
            ['4A--BC'],
            ['A______---44bc'],
            ['ab1c'],
            ['A111B2C'],
            ['Ab--3c'],
            [123],
        ];
    }

    /**
     * @dataProvider badProvider
     *
     * @param mixed $value
     */
    public function testBad($value)
    {
        $validate = new Validator(
            [
                'name' => $value,
            ],
            [
                'name'     => 'alpha_dash',
            ]
        );

        $this->assertFalse($validate->success());
    }

    public function badProvider()
    {
        return [
            [' '],
            ['not numeric'],
            [new stdClass()],
            [['foo', 'bar']],
            [[1, 2]],
            ['this is a string'],
            [true],
            [[[], []]],
            ['not/numeric'],
            ['not\ numeric'],
            ['not?numeric'],
        ];
    }
}
