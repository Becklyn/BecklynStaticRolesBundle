<?php

namespace Becklyn\StaticRolesBundle\Tests\Role;

use Becklyn\StaticRolesBundle\Role\RoleCollection;


/**
 *
 */
class RoleCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the correct detection of role hierarchies
     */
    public function testRoleHierarchy ()
    {
        $configuration = [
            "a" => ["included_roles" => ["b"]],
            "b" => [],
            "c" => [],
        ];

        $roleCollection = new RoleCollection($configuration);
        $includedRoles = $roleCollection->getAllIncludedRoles(["a"]);

        $this->assertArrayHasKey("a", $includedRoles);
        $this->assertArrayHasKey("b", $includedRoles);
        $this->assertArrayNotHasKey("c", $includedRoles);
    }



    /**
     * Tests that multiple entry points are correctly handled when detecting the role hierarchy
     */
    public function testRoleHierarchyWithMultipleEntryPoints ()
    {
        $configuration = [
            "a" => ["included_roles" => ["b"]],
            "b" => [],
            "c" => ["included_roles" => ["d"]],
            "d" => [],
            "3" => [],
        ];

        $roleCollection = new RoleCollection($configuration);
        $includedRoles = $roleCollection->getAllIncludedRoles(["a", "c"]);

        $this->assertArrayHasKey("a", $includedRoles);
        $this->assertArrayHasKey("b", $includedRoles);
        $this->assertArrayHasKey("c", $includedRoles);
        $this->assertArrayHasKey("d", $includedRoles);
        $this->assertArrayNotHasKey("e", $includedRoles);
    }



    /**
     * Tests a role hierarchy multiple levels deep
     */
    public function testDeepRoleHierarchy ()
    {
        $configuration = [
            "a" => ["included_roles" => ["b"]],
            "b" => ["included_roles" => ["c"]],
            "c" => ["included_roles" => ["d"]],
            "d" => [],
        ];

        $roleCollection = new RoleCollection($configuration);
        $includedRoles = $roleCollection->getAllIncludedRoles(["a"]);

        $this->assertArrayHasKey("a", $includedRoles);
        $this->assertArrayHasKey("b", $includedRoles);
        $this->assertArrayHasKey("c", $includedRoles);
        $this->assertArrayHasKey("d", $includedRoles);
    }



    /**
     * Ensures that cyclic role hierarchies don't choke up the system
     */
    public function testCyclicRoleHierarchy ()
    {
        $configuration = [
            "a" => ["included_roles" => ["b"]],
            "b" => ["included_roles" => ["a"]],
        ];

        $roleCollection = new RoleCollection($configuration);
        $includedRoles = $roleCollection->getAllIncludedRoles(["a"]);

        $this->assertArrayHasKey("a", $includedRoles);
        $this->assertArrayHasKey("b", $includedRoles);
    }
}
