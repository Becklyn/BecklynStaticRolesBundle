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
     */
    public function prepareRoleCollection (array $config) : array
    {
        $roleCollection = [];
        $roleHelper = new RoleNameHelper();

        foreach ($config as $role => $configuration)
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

        return $roleCollection;
    }
}
