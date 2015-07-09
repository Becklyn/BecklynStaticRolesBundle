<?php

namespace Becklyn\StaticRolesBundle\Form\Type;

use Becklyn\StaticRolesBundle\Role\Role;
use Becklyn\StaticRolesBundle\Role\RoleCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 *
 */
class StaticRoleType extends AbstractType
{
    /**
     * @var RoleCollection
     */
    private $roleCollection;



    /**
     * @param RoleCollection $roleCollection
     */
    public function __construct (RoleCollection $roleCollection)
    {
        $this->roleCollection = $roleCollection;
    }



    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions (OptionsResolver $resolver)
    {
        $allRoles = $this->roleCollection->getAllRoles();
        $allRoleKeys = array_keys($allRoles);

        $resolver->setDefaults([
            "choices" => array_combine($allRoleKeys, $allRoleKeys),
            "choices_as_values" => true,
            "choice_label" => function ($choiceValue, $choiceKey, $index) use ($allRoles)
            {
                return $allRoles[$choiceKey]->getDisplayName();
            },
        ]);
    }



    private function getChoicesMapping ()
    {

    }



    /**
     * {@inheritdoc}
     */
    public function getParent ()
    {
        return "choice";
    }



    /**
     * {@inheritdoc}
     */
    public function getName ()
    {
        return "static_role";
    }
}
