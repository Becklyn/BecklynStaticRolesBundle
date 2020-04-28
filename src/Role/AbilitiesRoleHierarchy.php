<?php declare(strict_types=1);

namespace Becklyn\StaticRolesBundle\Role;

use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

final class AbilitiesRoleHierarchy implements RoleHierarchyInterface
{
    /** @var RoleHierarchyInterface */
    private $coreHierarchy;

    /** @var array */
    private $config;


    /**
     * @param RoleHierarchyInterface $coreHierarchy
     * @param array                  $config
     */
    public function __construct (RoleHierarchyInterface $coreHierarchy, array $config = [])
    {
        $this->coreHierarchy = $coreHierarchy;
        $this->config = $config;

        dump($config);
    }


    public function getReachableRoleNames (array $roles) : array
    {
        dump("get reachable roles", $roles);
        $roles = $this->coreHierarchy->getReachableRoleNames($roles);

        foreach ($roles as $role)
        {
            $actions = $this->config[$role]["actions"] ?? [];

            foreach ($actions as $action)
            {
                $roles[] = $action;
            }
        }

        dump("after", $roles);
        return $roles;
    }
}
