<?php

declare(strict_types=1);

namespace App\Controller\Api;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;

/**
 * @Route("/auth")
 */
class ApiAuthController extends AbstractController
{
    /**
     * Register new users.
     *
     * This endpoint register new users.
     *
     * @Rest\Post("/register", name="api_auth_register")
     * @SWG\Response(
     *     response=200,
     *     description="Returned when the register is successful",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class))
     *     )
     * ),
     * @SWG\Response(
     *     response="401",
     *     description="Returned when the user has not provided his credentials correctly."
     * )
     * @SWG\Parameter(
     *     name="Content-Type",
     *     in="header",
     *     type="string",
     *     description="Content Type",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="Register data",
     *     in="body",
     *     type="string",
     *     description="Register user data",
     *     required=true,
     *     @SWG\Schema(type="object",
     *          @SWG\Property(property="username", description="Username", type="string"),
     *          @SWG\Property(property="password", description="Password", type="string"),
     *          @SWG\Property(property="email", description="Email", type="string"),
     *          required={
     *               "username",
     *               "password",
     *               "email"
     *          }
     *     ),
     * )
     * @SWG\Tag(name="Register")
     * @Security(name="Bearer")
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function register(Request $request, UserManagerInterface $userManager)
    {
        /** @var $serializer \JMS\Serializer\Serializer */
        $serializer = $this->get("serializer");
        $data = $request->getContent();

        $data = json_decode(
            $request->getContent(),
            true
        );

        //$data = $serializer->deserialize($data, User::class, 'json');

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(array(
            // the keys correspond to the keys in the input array
            'username' => new Assert\Length(array('min' => 4)),
            'password' => new Assert\Length(array('min' => 4)),
            'email' => new Assert\Email(),
        ));
        $violations = $validator->validate($data, $constraint);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], 500);
        }

        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];
        $user = new User();
        $user
            ->setUsername($username)
            ->setPlainPassword($password)
            ->setEmail($email)
            ->setEnabled(true)
            ->setRoles(['ROLE_USER'])
            ->setSuperAdmin(false)
        ;
        try {
            $userManager->updateUser($user);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
        return $this->redirectToRoute('api_auth_login', [
            'username' => $data['username'],
            'password' => $data['password']
        ], 307);
    }
}