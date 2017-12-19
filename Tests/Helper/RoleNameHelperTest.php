<?php

namespace Becklyn\StaticRolesBundle\Tests\Helper;

use Becklyn\StaticRolesBundle\Helper\RoleNameHelper;
use PHPUnit\Framework\TestCase;


/**
 *
 */
class RoleNameHelperTest extends TestCase
{
    public function getData ()
    {
        return [
            [1, "ROLE_1"],
            ["test", "ROLE_TEST"],
            ["ROLE_TEST", "ROLE_TEST"],
            ["role_test", "ROLE_TEST"],
        ];
    }



    /**
     * @dataProvider getData
     *
     * @param string $rawRoleName
     * @param string $expectedRoleName
     */
    public function testValue ($rawRoleName, $expectedRoleName)
    {
        $helper = new RoleNameHelper();
        $actualRoleName = $helper->normalizeRoleName($rawRoleName);
        $this->assertEquals($expectedRoleName, $actualRoleName);
    }



    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEmptyRole ()
    {
        $helper = new RoleNameHelper();
        $helper->normalizeRoleName("");
    }



    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRoleWithOnlyWhitespace ()
    {
        $helper = new RoleNameHelper();
        $helper->normalizeRoleName("   ");
    }
}
