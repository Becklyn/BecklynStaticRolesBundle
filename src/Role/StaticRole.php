<?php declare(strict_types=1);

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
     * @var string|null
     */
    private $title;


    /**
     * The description of the role
     *
     * @var string|null
     */
    private $description;


    /**
     * Whether the role should be hidden when providing a list of available roles to the user
     *
     * @var bool
     */
    private $hidden;


    /**
     * A list of all included role names
     *
     * @var string[]
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
     */
    public function __construct (
        string $role,
        ?string $title,
        ?string $description,
        bool $hidden,
        array $includedRoles,
        array $tags,
        array $actions
    )
    {
        $this->role = $role;
        $this->title = $title;
        $this->description = $description;
        $this->hidden = $hidden;
        $this->includedRoles = $includedRoles;
        $this->tags = $tags;
        $this->actions = $actions;
    }



    /**
     * Creates the role from the configuration
     *
     * @param string $role
     *
     * @return StaticRole
     */
    public static function createFromConfiguration ($role, array $configuration)
    {
        return new self(
            $role,
            $configuration["title"] ?? null,
            $configuration["description"] ?? null,
            $configuration["hidden"] ?? false,
            $configuration["included_roles"] ?? [],
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
     */
    public function getTags () : array
    {
        return $this->tags;
    }



    /**
     * @return string[]
     */
    public function getIncludedRoles () : array
    {
        return $this->includedRoles;
    }



    /**
     */
    public function getActions () : array
    {
        return $this->actions;
    }



    /**
     * @return bool
     */
    public function isHidden ()
    {
        return $this->hidden;
    }


    /**
     */
    public function getRole () : string
    {
        return $this->role;
    }
}
