<?php declare(strict_types=1);

namespace Becklyn\StaticRolesBundle\Role;

use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 *
 */
final class RoleCollection implements RoleHierarchyInterface
{
    /** @var RoleHierarchyInterface */
    private $coreHierarchy;

    /** @var RoleHierarchyInterface */
    private $nestedHierarchy;

    /** @var StaticRole[] */
    private $roles;


    /**
     */
    public function __construct (RoleHierarchyInterface $coreHierarchy, array $config = [])
    {
        $this->coreHierarchy = $coreHierarchy;
        $this->nestedHierarchy = $this->buildNestedHierarchy($config);
        $this->roles = $this->prepareRoleCollection($config);
    }


    /**
     *
     */
    private function buildNestedHierarchy (array $config) : RoleHierarchyInterface
    {
        $map = [];

        foreach ($config as $role => $data)
        {
            $map[$role] = $data["included_roles"] ?? [];
        }

        return new RoleHierarchy($map);
    }



    /**
     * Prepares the role collection by transforming the config array to an object structure
     *
     * @return StaticRole[]
     */
    private function prepareRoleCollection (array $config = [])
    {
        /** @var StaticRole[] $preparedRoles */
        $preparedRoles = [];

        foreach ($config as $roleKey => $configuration)
        {
            $preparedRoles[$roleKey] = StaticRole::createFromConfiguration($roleKey, $configuration);
        }

        return $preparedRoles;
    }



    /**
     * Returns a list of all roles
     *
     * @return StaticRole[]
     */
    public function getAllAvailableRoles ()
    {
        return \array_filter(
            $this->roles,
            function (StaticRole $role)
            {
                return !$role->isHidden();
            }
        );
    }


    /**
     * Returns a Role by key
     */
    public function getRoleByKey (string $roleKey) : ?StaticRole
    {
        return $this->roles[$roleKey] ?? null;
    }


    /**
     * @inheritDoc
     */
    public function getReachableRoleNames (array $roles) : array
    {
        $roles = $this->coreHierarchy->getReachableRoleNames($roles);

        foreach ($this->nestedHierarchy->getReachableRoleNames($roles) as $additional)
        {
            $roles[] = $additional;
        }

        $result = \array_unique($roles);

        foreach ($roles as $role)
        {
            $staticRole = $this->roles[$role] ?? null;

            if (null !== $staticRole)
            {
                foreach ($staticRole->getActions() as $action)
                {
                    $result[] = $action;
                }
            }
        }

        return $result;
    }


}
