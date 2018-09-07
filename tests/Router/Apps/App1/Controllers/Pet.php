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

namespace Tests\Router\Apps\App1\Controllers;

/**
 * Class Pet.
 *
 *
 * @author  Donii Sergii <doniysa@gmail.com>
 */
class Pet
{
    /**
     * @OAS\Get(
     *     path="/api/v1/petLeevel/{petId:[A-Za-z]+}/",
     *     tags={"pet"},
     *     summary="Just test the router",
     *     operationId="petLeevel",
     *     @OAS\Parameter(
     *         name="petId",
     *         in="path",
     *         description="ID of pet to return",
     *         required=true,
     *         @OAS\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OAS\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"petstore_auth": {"write:pets", "read:pets"}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/Pet"},
     *     leevelScheme="https",
     *     leevelDomain="{subdomain:[A-Za-z]+}-vip.{domain}",
     *     leevelParams={"args1": "hello", "args2": "world"},
     *     leevelBind="/PetLeevel/show/?query1=foo&query2=bar",
     *     leevelMiddlewares="api"
     * )
     */
    public function petLeevel()
    {
    }

    /**
     * @OAS\Get(
     *     path="/api/v2/petLeevelIgnore/",
     *     tags={"pet"},
     *     summary="Just test ignore the router",
     *     operationId="petLeevelIgnore",
     *     @OAS\Parameter(
     *         name="petId",
     *         in="path",
     *         description="ID of pet to return",
     *         required=true,
     *         @OAS\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OAS\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"petstore_auth": {"write:pets", "read:pets"}}
     *     },
     *     leevelIgnore=true
     * )
     */
    public function petLeevelIgnore()
    {
    }
}