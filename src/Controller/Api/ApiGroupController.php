<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Groups;
use App\Entity\User;
use App\Services\GroupService;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;

/**
 * @Route("/group")
 */
class ApiGroupController extends AbstractController
{
    /**
     * @var GroupService
     */
    private $groupService;

    /**
     * ApiGroupController constructor.
     * @param GroupService $groupService
     */
    public function __construct(
        GroupService $groupService
    ) {
        $this->groupService = $groupService;
    }

    /**
     * Create Group.
     *
     * This endpoint create a new group.
     *
     * @Rest\Post("/new", name="api_group_register")
     * @Rest\View(serializerGroups={"api_users"})
     * @Security("is_granted('ROLE_USER')")
     *
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     *
     */
    public function newGroupAction(Request $request)
    {
        dump($request->request->all());
        ;
        $respuesta = $this->groupService->createGroup($request->request->all());
        die();

    }
}