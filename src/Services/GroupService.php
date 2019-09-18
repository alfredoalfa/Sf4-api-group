<?php


namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Groups;
use App\Form\GroupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use JMS\Serializer\SerializerInterface;

class GroupService extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * GroupService constructor.
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @param FormFactoryInterface $formFactory
     * @param SerializerInterface $serializer
     */
    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        FormFactoryInterface $formFactory,
        SerializerInterface $serializer
    ){
        $this->em = $em;
        $this->validator = $validator;
        $this->formFactory = $formFactory;
        $this->serializer = $serializer;
    }

    /**
     * @param Groups $groups
     * @param array $request
     * @return JsonResponse
     */
    public function createGroup(
        array $request
    ) {
        $groups = new Groups();

        $form = $this->formFactory->create(GroupType::class, $groups);
        $form->submit($request);

        if (!$form->isValid()) {
            $violations = $this->validator->validate($groups);
            $errorsString = (string) $violations[0]->getMessage();

            if ($violations->count() > 0) {
                return new JsonResponse(["error" => (string)$errorsString], 500);
            }
            return new JsonResponse(["error" => "Not valid form"], 400);
        }

        $this->em->persist($groups);
        $this->em->flush();

        $json = $this->serializer->serialize($groups,'json');

        return new JsonResponse(["Success" => (string)$json], 200);
    }
}