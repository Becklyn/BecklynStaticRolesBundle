<?php

namespace Becklyn\StaticRolesBundle\Helper;

/**
 * Provides helper methods when dealing with roles
 */
class RoleNameHelper
{
    /**
     * Normalizes the given role name to a "ROLE_*" format
     *
     * @param string $role
     *
     * @return string
     */
    public function normalizeRoleName ($role)
    {
        $role = trim((string) $role);

        if ("" === $role)
        {
            throw new \InvalidArgumentException("Role name can't be empty or only contain whitespace.");
        }

        $role = strtoupper($role);

        return (0 !== strpos($role, "ROLE_"))
            ? "ROLE_{$role}"
            : $role;
    }
}
