<?php


namespace App\Controller\Api;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class ApiUserController extends AbstractController
{
    /**
     * @Route("/{id}", name="api_user_detail", methods={"GET"})
     * @Security("is_granted('view', user)")
     * @param User $user
     * @return JsonResponse
     */
    public function detail(User $user)
    {
        return new JsonResponse($this->serialize($user), 200);
    }

    protected function serialize(User $user)
    {
        /** @var $serializer \JMS\Serializer\Serializer */
        $serializer = $this->get("serializer");
        $json = $serializer->serialize($user, 'json');

        return $json;
    }
}