<?php

namespace Becklyn\StaticRolesBundle\Role;

use Symfony\Component\Security\Core\Role\RoleInterface;


/**
 *
 */
class RoleCollection
{
    /**
     * @var Role[]
     */
    private $roleCollection;

    public function __construct (array $roleCollection)
    {
        $this->roleCollection = $this->prepareRoleCollection($roleCollection);
    }



    /**
     * Prepares the role collection by transforming the config array to an object structure
     *
     * @param array[] $roleConfiguration
     *
     * @return Role[]
     */
    private function prepareRoleCollection (array $roleConfiguration)
    {
        /** @var Role[] $preparedRoles */
        $preparedRoles = [];

        foreach ($roleConfiguration as $roleKey => $configuration)
        {
            $preparedRoles[$roleKey] = Role::createFromConfiguration($roleKey, $configuration);
        }

        foreach ($roleConfiguration as $roleKey => $configuration)
        {
            if (!isset($configuration["included_roles"]) || !is_array($configuration["included_roles"]))
            {
                continue;
            }

            $includedRoles = array_map(
                function ($role) use ($preparedRoles)
                {
                    return $preparedRoles[$role];
                },
                $configuration["included_roles"]
            );

            $preparedRoles[$roleKey]->setIncludedRoles($includedRoles);
        }

        return $preparedRoles;
    }



    /**
     * Finds all included roles of a set of base roles
     *
     * @param (RoleInterface|string)[] $roles
     *
     * @return array
     */
    public function getAllIncludedRoles (array $roles)
    {
        $allIncludedRoles = [];

        foreach ($this->normalizeRoleList($roles) as $role)
        {
            $includedRoleCollection = [];
            $this->findIncludedRoles($role, $includedRoleCollection);

            $allIncludedRoles = array_replace($allIncludedRoles, $includedRoleCollection);
        }

        return $allIncludedRoles;
    }



    /**
     * Finds all included roles
     *
     * @param Role  $role                   the starting role
     * @param array $includedRoleCollection the list of included roles
     */
    private function findIncludedRoles (Role $role, array &$includedRoleCollection)
    {
        // check whether we already visited this role
        // this is required as we need a safeguard against cyclic role hierarchies
        if (isset($includedRoleCollection[$role->getRole()]))
        {
            return;
        }

        // mark current role as included
        $includedRoleCollection[$role->getRole()] = $role;

        foreach ($role->getIncludedRoles() as $includedRole)
        {
            $this->findIncludedRoles($includedRole, $includedRoleCollection);
        }
    }



    /**
     * Normalizes the role list
     *
     * @param (RoleInterface|string)[] $roles
     *
     * @return Role[]
     */
    private function normalizeRoleList (array $roles)
    {
        $normalized = [];

        foreach ($roles as $role)
        {
            $roleKey = (is_object($role) && ($role instanceof RoleInterface))
                ? $role->getRole()
                : (string) $role;

            if (isset($this->roleCollection[$roleKey]))
            {
                $normalized[] = $this->roleCollection[$roleKey];
            }
        }

        return $normalized;
    }
}
