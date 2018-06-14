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
 * (c) 2010-2018 http://queryphp.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Database\Query;

use Tests\TestCase;

/**
 * flow test.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.06.14
 *
 * @version 1.0
 * @coversNothing
 */
class QueryFlowTest extends TestCase
{
    use Query;

    public function testBaseUse()
    {
        $connect = $this->createConnect();

        $sql = <<<'eot'
array (
  0 => 'SELECT `test`.* FROM `test` WHERE `test`.`id` = 2 ORDER BY `test`.`name` DESC LIMIT 1',
  1 => 
  array (
  ),
  2 => false,
  3 => NULL,
  4 => NULL,
  5 => 
  array (
  ),
)
eot;

        $id = 2;

        $this->assertSame(
            $sql,
            $this->varExport(
                $connect->table('test')->

                ifs($id == 1)->where('id', 1)->

                elseIfs($id == 2)->where('id', 2)->orderBy('name DESC')->

                elseIfs($id == 3)->where('id', 3)->where('id', 1111)->

                elseIfs($id == 4)->where('id', 4)->

                endIfs()->

                getOne(true),
                __METHOD__
            )
        );

        $sql = <<<'eot'
array (
  0 => 'SELECT `test`.* FROM `test` WHERE `test`.`id` = 1 LIMIT 1',
  1 => 
  array (
  ),
  2 => false,
  3 => NULL,
  4 => NULL,
  5 => 
  array (
  ),
)
eot;

        $id = 1;

        $this->assertSame(
            $sql,
            $this->varExport(
                $connect->table('test')->

                ifs($id == 1)->where('id', 1)->

                elseIfs($id == 2)->where('id', 2)->orderBy('name DESC')->

                elseIfs($id == 3)->where('id', 3)->where('id', 1111)->

                elseIfs($id == 4)->where('id', 4)->

                endIfs()->

                getOne(true),
                __METHOD__
            )
        );

        $sql = <<<'eot'
array (
  0 => 'SELECT `test`.* FROM `test` WHERE `test`.`id` = 3 AND `test`.`id` = 1111 LIMIT 1',
  1 => 
  array (
  ),
  2 => false,
  3 => NULL,
  4 => NULL,
  5 => 
  array (
  ),
)
eot;

        $id = 3;

        $this->assertSame(
            $sql,
            $this->varExport(
                $connect->table('test')->

                ifs($id == 1)->where('id', 1)->

                elseIfs($id == 2)->where('id', 2)->orderBy('name DESC')->

                elseIfs($id == 3)->where('id', 3)->where('id', 1111)->

                elseIfs($id == 4)->where('id', 4)->

                endIfs()->

                getOne(true),
                __METHOD__
            )
        );

        $sql = <<<'eot'
array (
  0 => 'SELECT `test`.* FROM `test` WHERE `test`.`id` = 4 LIMIT 1',
  1 => 
  array (
  ),
  2 => false,
  3 => NULL,
  4 => NULL,
  5 => 
  array (
  ),
)
eot;

        $id = 4;

        $this->assertSame(
            $sql,
            $this->varExport(
                $connect->table('test')->

                ifs($id == 1)->where('id', 1)->

                elseIfs($id == 2)->where('id', 2)->orderBy('name DESC')->

                elseIfs($id == 3)->where('id', 3)->where('id', 1111)->

                elseIfs($id == 4)->where('id', 4)->

                endIfs()->

                getOne(true),
                __METHOD__
            )
        );
    }
}
