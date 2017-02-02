<?php

namespace Becklyn\StaticRolesBundle\Role;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\Role\Role as BaseRole;


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

        // Transform the Actions to Roles
        foreach ($roleConfiguration as $roleKey => $configuration)
        {
            if (!isset($configuration["actions"]) || !is_array($configuration["actions"]))
            {
                continue;
            }

            foreach ($configuration["actions"] as $action)
            {
                if (!isset($preparedRoles[$action]))
                {
                    $preparedRoles[$action] = new BaseRole($action);
                }
            }

            $includedActions = array_map(
                function ($role) use ($preparedRoles)
                {
                    return $preparedRoles[$role];
                },
                $configuration["actions"]
            );

            $preparedRoles[$roleKey]->setIncludedActions($includedActions);
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

            $includedActionCollection = [];
            $this->findIncludedActions($role, $includedActionCollection);
            $allIncludedRoles = array_replace($allIncludedRoles, $includedActionCollection);
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

        foreach ($role->getActions() as $includedAction)
        {
            $includedRoleCollection[$includedAction->getRole()] = $includedAction;
        }

        foreach ($role->getIncludedRoles() as $includedRole)
        {
            $this->findIncludedRoles($includedRole, $includedRoleCollection);
        }
    }



    /**
     * Finds all included roles
     *
     * @param Role  $role
     * @param array $includedActionCollection
     */
    private function findIncludedActions (Role $role, array &$includedActionCollection)
    {
        foreach ($role->getActions() as $action)
        {
            /** @var $action BaseRole */
            if (isset($includedRoleCollection[$action->getRole()]))
            {
                return;
            }

            $includedActionCollection[$action->getRole()] = $action;
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



    /**
     * Returns a list of all roles
     *
     * @return Role[]
     */
    public function getAllAvailableRoles ()
    {
        return array_filter(
            $this->roleCollection,
            function (BaseRole $role)
            {
                if ($role instanceof Role)
                {
                    return !$role->isHidden();
                }

                //remove all BaseRoles
                return false;
            }
        );
    }


    /**
     * Returns a Role by key
     *
     * @param string $roleKey
     *
     * @return Role|null
     */
    public function getRoleByKey ($roleKey)
    {
        return array_key_exists($roleKey, $this->roleCollection)
            ? $this->roleCollection[$roleKey]
            : null;
    }
}
