<?php


namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerBuilder;

/**
 * @Route("/user")
 */
class ApiUserController extends AbstractController
{
    /**
     * @Route("/{id}", name="api_user_detail", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function detail(User $user)
    {
        $this->denyAccessUnlessGranted('view', $user);
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