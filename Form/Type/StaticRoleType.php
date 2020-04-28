<?php

namespace Becklyn\StaticRolesBundle\Form\Type;

use Becklyn\StaticRolesBundle\Role\StaticRole;
use Becklyn\StaticRolesBundle\Role\RoleCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


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
        $allRoles = $this->roleCollection->getAllAvailableRoles();
        $allRoleKeys = array_keys($allRoles);

        $resolver->setDefaults([
            "roles_with_tags" => [],
            "choices" => function(Options $options) use ($allRoleKeys, $allRoles)
            {
                // if no tags are selected, just return all roles
                if (empty($options["roles_with_tags"]))
                {
                    return array_combine($allRoleKeys, $allRoleKeys);
                }

                // if tags are selected, only use the choices with the given tags
                $choices = [];

                foreach ($allRoles as $roleKey => $role)
                {
                    if (!empty(array_intersect($options["roles_with_tags"], $role->getTags())))
                    {
                        $choices[$roleKey] = $roleKey;
                    }
                }

                return $choices;
            },
            "choices_as_values" => true,
            "choice_label" => function ($choiceValue, $choiceKey, $index) use ($allRoles)
            {
                return $allRoles[$choiceKey]->getTitle();
            },
            "choice_attr" => function ($choiceValue, $choiceKey, $index) use ($allRoles)
            {
                return [
                    "data-role-description" => $allRoles[$choiceKey]->getDescription(),
                ];
            },
        ]);

        $resolver->setAllowedTypes("roles_with_tags", ["array"]);
    }



    /**
     * {@inheritdoc}
     */
    public function getParent ()
    {
        return ChoiceType::class;
    }



    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix ()
    {
        return "static_role";
    }
}
