<?php

namespace App\Http\Controller;

use App\Domain\Entity\User\User;
use App\Http\Transformer\User\UserTransformer;
use App\Repository\User\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @Config\Route("/{user}", requirements={"user"="\d+"})
     * @Config\Method("GET")
     *
     * @param User $user
     * @param UserTransformer $transformer
     * @return JsonResponse
     */
    public function viewAction(User $user, UserTransformer $transformer)
    {
        return $this->item($user, $transformer)->asResponse(Response::HTTP_OK);
    }

    /**
     * @Config\Route("/{user}", requirements={"user"="[d]+"})
     * @Config\Method("DELETE")
     *
     * @param User $user
     * @param UserRepository $repository
     * @return JsonResponse
     */
    public function deleteAction(User $user, UserRepository $repository)
    {
        $repository->remove($user);
        $this->flushChanges();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
