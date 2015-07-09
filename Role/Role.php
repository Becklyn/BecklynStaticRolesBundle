<?php

namespace Becklyn\StaticRolesBundle\Role;

use Symfony\Component\Security\Core\Role\Role as BaseRole;


/**
 *
 */
class Role extends BaseRole
{
    /**
     * The title of the role
     *
     * @var null|string
     */
    private $title = null;


    /**
     * The description of the role
     *
     * @var null|string
     */
    private $description = null;


    /**
     * A list of all included roles
     *
     * @var Role[]
     */
    private $includedRoles = [];



    /**
     * Role constructor.
     *
     * @param string      $role
     * @param null|string $title
     * @param null|string $description
     */
    public function __construct ($role, $title, $description)
    {
        parent::__construct($role);
        $this->title       = $title;
        $this->description = $description;
    }



    /**
     * Creates the role from the configuration
     *
     * @param string $role
     * @param array  $configuration
     *
     * @return Role
     */
    public static function createFromConfiguration ($role, array $configuration)
    {
        return new Role(
            $role,
            isset($configuration["title"]) ? $configuration["title"] : null,
            isset($configuration["description"]) ? $configuration["description"] : null
        );
    }



    /**
     * @return null|string
     */
    public function getTitle ()
    {
        return $this->title;
    }



    /**
     * @return null|string
     */
    public function getDescription ()
    {
        return $this->description;
    }



    /**
     * @return Role[]
     */
    public function getIncludedRoles ()
    {
        return $this->includedRoles;
    }



    /**
     * @param Role[] $includedRoles
     */
    public function setIncludedRoles (array $includedRoles)
    {
        $this->includedRoles = $includedRoles;
    }
}
