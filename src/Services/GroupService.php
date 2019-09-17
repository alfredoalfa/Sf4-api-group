<?php


namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Groups;
use App\Form\GroupType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormFactoryInterface;

class GroupService
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
     * GroupService constructor.
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @param FormFactoryInterface $$formFactory
     */
    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        FormFactoryInterface $formFactory
    ){
        $this->em = $em;
        $this->validator = $validator;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Groups $groups
     * @param array $request
     * @return Groups|null
     */
    public function createGroup(
        array $request
    ) {
        $groups = new Groups();

        $form = $this->formFactory->create(GroupType::class, $groups);
        $form->submit($request);

        dump($groups);

        if(!$form->isValid()){
            $violations = $this->validator->validate($groups);
            die();
        }


        dump($form);
        dump("llaoe");
        die();
    }
}