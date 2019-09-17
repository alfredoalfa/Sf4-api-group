<?php


namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Groups;
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
    protected $validatorInterface;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * GroupService constructor.
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validatorInterface
     * @param FormFactoryInterface $$formFactory
     */
    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validatorInterface,
        FormFactoryInterface $formFactory
    ){
        $this->em = $em;
        $this->validatorInterface = $validatorInterface;
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
        dump("llaoe");
        die();
    }
}