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
    private $hierarchy;

    /** @var StaticRole[] */
    private $roles;


    /**
     */
    public function __construct (
        array $config = [],
        array $coreHierarchy = []
    )
    {
        $this->roles = $this->prepareRoleCollection($config);
        $this->hierarchy = $this->buildFullHierarchy($coreHierarchy, $this->roles);
    }


    /**
     * @param array $coreHierarchy
     * @param StaticRole[] $roles
     *
     * @return RoleHierarchyInterface
     */
    private function buildFullHierarchy (array $coreHierarchy, array $roles) : RoleHierarchyInterface
    {
        foreach ($roles as $staticRole)
        {
            $role = $staticRole->getRole();

            if (!\array_key_exists($role, $coreHierarchy))
            {
                $coreHierarchy[$role] = [];
            }

            foreach ($staticRole->getIncludedRoles() as $included)
            {
                $coreHierarchy[$role][] = $included;
            }

            $coreHierarchy[$role] = \array_unique($coreHierarchy[$role]);
        }

        return new RoleHierarchy($coreHierarchy);
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
        $roles = $this->hierarchy->getReachableRoleNames($roles);

        $result = $roles;

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
