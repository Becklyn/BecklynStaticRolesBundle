<?php declare(strict_types=1);

namespace Becklyn\StaticRolesBundle\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 * Votes on role actions
 */
final class RoleActionsVoter extends RoleHierarchyVoter
{
    /**
     */
    public function __construct (RoleHierarchyInterface $roleHierarchy)
    {
        parent::__construct($roleHierarchy, "CAN_");
    }
}
