<?php

declare(strict_types=1);

namespace App\Controller\Api;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function register(Request $request, UserManagerInterface $userManager, ValidatorInterface $validator)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $data = json_decode(
            $request->getContent(),
            true
        );
         $form->submit($data);

        if(!$form->isValid()){
            $violations = $validator->validate($user);
            $errorsString = (string) $violations[0]->getConstraint()->exactMessage;
            if ($violations->count() > 0) {
                return new JsonResponse(["error" => (string)$errorsString], 500);
            }

            return new JsonResponse(["error" => "Not valid form"], 400);
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