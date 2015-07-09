<?php

namespace Becklyn\StaticRolesBundle\Voter;

use Becklyn\StaticRolesBundle\Role\RoleCollection;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;


/**
 *
 */
class RoleHierarchyVoter extends RoleVoter
{
    /**
     * @var RoleCollection
     */
    private $roleCollection;


    /**
     * @param RoleCollection $roleCollection
     * @param string         $prefix
     */
    public function __construct(RoleCollection $roleCollection, $prefix = 'ROLE_')
    {
        parent::__construct($prefix);
        $this->roleCollection = $roleCollection;
    }


    /**
     * {@inheritdoc}
     */
    protected function extractRoles (TokenInterface $token)
    {
        return $this->roleCollection->getAllIncludedRoles($token->getRoles());
    }
}
