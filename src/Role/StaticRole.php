<?php

namespace Becklyn\StaticRolesBundle\Role;

/**
 *
 */
final class StaticRole
{
    /**
     * @var string
     */
    private $role;

    /**
     * The title of the role
     *
     * @var null|string
     */
    private $title;


    /**
     * The description of the role
     *
     * @var null|string
     */
    private $description;


    /**
     * Whether the role should be hidden when providing a list of available roles to the user
     *
     * @var boolean
     */
    private $hidden;


    /**
     * A list of all included roles
     *
     * @var StaticRole[]
     */
    private $includedRoles = [];


    /**
     * A list of tags
     *
     * @var array
     */
    private $tags;


    /**
     * A list of actions this role has permission to do
     *
     * @var array
     */
    private $actions;



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
    public function __construct (
        string $role,
        ?string $title,
        ?string $description,
        bool $hidden,
        array $tags,
        array $actions
    )
    {
        $this->role = $role;
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
     * @return StaticRole
     */
    public static function createFromConfiguration ($role, array $configuration)
    {
        return new StaticRole(
            $role,
            $configuration["title"] ?? null,
            $configuration["description"] ?? null,
            $configuration["hidden"] ?? false,
            $configuration["tags"] ?? [],
            $configuration["actions"] ?? []
        );
    }



    /**
     */
    public function getTitle () : ?string
    {
        return $this->title;
    }



    /**
     */
    public function getDescription () : ?string
    {
        return $this->description;
    }



    /**
     * @return array
     */
    public function getTags () : array
    {
        return $this->tags;
    }



    /**
     * @return StaticRole[]
     */
    public function getIncludedRoles () : array
    {
        return $this->includedRoles;
    }



    /**
     * @param StaticRole[] $includedRoles
     */
    public function setIncludedRoles (array $includedRoles)
    {
        $this->includedRoles = $includedRoles;
    }



    /**
     * @return array
     */
    public function getActions () : array
    {
        return $this->actions;
    }



    /**
     * @param string[] $includedActions
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


    /**
     * @return string
     */
    public function getRole () : string
    {
        return $this->role;
    }
}
