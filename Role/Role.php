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
     * Whether the role should be hidden when providing a list of available roles to the user
     *
     * @var boolean
     */
    private $hidden;


    /**
     * A list of all included roles
     *
     * @var Role[]
     */
    private $includedRoles = [];


    /**
     * A list of tags
     *
     * @var array
     */
    private $tags = [];


    /**
     * A list of actions this role has permission to do
     *
     * @var array
     */
    private $actions = [];



    /**
     * Role constructor.
     *
     * @param string      $role
     * @param null|string $title
     * @param null|string $description
     * @param boolean     $hidden
     * @param array       $tags
     * @param array       $actions
     */
    public function __construct ($role, $title, $description, $hidden, array $tags, array $actions)
    {
        parent::__construct($role);

        $this->title = $title;
        $this->description = $description;
        $this->hidden = $hidden;
        $this->tags = $tags;
        $this->actions = $actions;
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
            isset($configuration["description"]) ? $configuration["description"] : null,
            isset($configuration["hidden"]) ? $configuration["hidden"] : false,
            isset($configuration["tags"]) ? $configuration["tags"] : [],
            isset($configuration["actions"]) ? $configuration["actions"] : []
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
     * @return array
     */
    public function getTags ()
    {
        return $this->tags;
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



    /**
     * @return array
     */
    public function getActions ()
    {
        return $this->actions;
    }



    /**
     * @param BaseRole[] $includedActions
     */
    public function setIncludedActions (array $includedActions)
    {
        $this->actions = $includedActions;
    }



    /**
     * @return boolean
     */
    public function isHidden ()
    {
        return $this->hidden;
    }
}
