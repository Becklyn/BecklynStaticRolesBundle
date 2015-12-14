<?php

namespace Becklyn\StaticRolesBundle\Twig;

use Becklyn\StaticRolesBundle\Role\RoleCollection;


/**
 * Twig helper functions for handling of static roles
 */
class StaticRolesTwigExtension extends \Twig_Extension
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
     * Returns the static role name
     *
     * @param string $roleKey
     *
     * @return null|string
     */
    public function staticRoleTitle ($roleKey)
    {
        $role = $this->roleCollection->getRoleByKey($roleKey);

        return null !== $role
            ? $role->getTitle()
            : null;
    }



    /**
     * {@inheritdoc}
     */
    public function getFunctions ()
    {
        return [
            new \Twig_SimpleFunction("staticRoleTitle", [$this, "staticRoleTitle"]),
        ];
    }



    /**
     * @inheritDoc
     */
    public function getName ()
    {
        return self::class;
    }
}
