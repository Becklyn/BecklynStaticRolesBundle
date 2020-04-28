<?php

namespace Becklyn\StaticRolesBundle\Role;

use Becklyn\StaticRolesBundle\Exception\DuplicateRoleDefinitionException;
use Becklyn\StaticRolesBundle\Exception\EmptyRoleNameException;
use Becklyn\StaticRolesBundle\Exception\UnknownRoleException;
use Becklyn\StaticRolesBundle\Helper\RoleNameHelper;


/**
 * Helps building the role collection
 */
final class RoleCollectionBuilder
{
    /**
     * Prepares the role collection
     *
     * @param array $roles
     *
     * @return array
     */
    public function prepareRoleCollection (array $roles)
    {
        $roleCollection = [];
        $roleHelper = new RoleNameHelper();

        foreach ($roles as $role => $configuration)
        {
            try
            {
                // normalize role name
                $roleName = $roleHelper->normalizeRoleName($role);

                // check for duplicate role definitions
                if (array_key_exists($roleName, $roleCollection))
                {
                    $addition = ($roleName !== $role) ? " (normalized from “{$role}”)" : "";
                    throw new DuplicateRoleDefinitionException("The role “{$roleName}”{$addition} has multiple definitions. Keep in mind that all role names are normalized to a common “ROLE_…” format.");
                }

                $roleCollection[$roleName] = $configuration;
            }
            catch (\InvalidArgumentException $e)
            {
                throw new EmptyRoleNameException("Role name could not be normalized: {$e->getMessage()}.", 0, $e);
            }
        }

        return $this->prepareIncludedRoles($roleCollection);
    }



    /**
     * Prepares and validates the included roles
     *
     * @param array $roleCollection
     *
     * @return array
     */
    private function prepareIncludedRoles (array $roleCollection)
    {
        $roleHelper = new RoleNameHelper();

        foreach ($roleCollection as $role => &$configuration)
        {
            try
            {
                if (!isset($configuration["included_roles"]) || !is_array($configuration["included_roles"]))
                {
                    continue;
                }

                $configuration["included_roles"] = array_map(
                    function ($includedRole) use ($roleHelper, $roleCollection, $role)
                    {
                        $normalizedRoleName = $roleHelper->normalizeRoleName($includedRole);

                        if (!isset($roleCollection[$normalizedRoleName]))
                        {
                            throw new UnknownRoleException("The included role „{$normalizedRoleName}” (in the definition of role “{$role}”) was not found.");
                        }

                        return $normalizedRoleName;
                    },
                    $configuration["included_roles"]
                );
            }
            catch (\InvalidArgumentException $e)
            {
                throw new EmptyRoleNameException("Role name could not be normalized: {$e->getMessage()}.", 0, $e);
            }
        }

        return $roleCollection;
    }
}
