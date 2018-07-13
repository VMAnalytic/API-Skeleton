<?php

namespace App\Http\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Config\Route("/users")
 */
class UserController extends ApiController
{
    /**
     * @Config\Route("")
     * @Config\Method("GET")
     */
    public function testUser()
    {
        return new JsonResponse('User'); //TODO testing
    }
}
