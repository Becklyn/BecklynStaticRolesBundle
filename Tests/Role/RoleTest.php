<?php

namespace Becklyn\StaticRolesBundle\Tests\Role;

use Becklyn\StaticRolesBundle\Role\StaticRole;
use PHPUnit\Framework\TestCase;


/**
 *
 */
class RoleTest extends TestCase
{
    /**
     *
     */
    public function testConfigurationFromArray ()
    {
        $role = StaticRole::createFromConfiguration("ROLE_TEST", [
            "title" => "title",
            "description" => "description",
            "hidden" => true,
        ]);

        $this->assertEquals("description", $role->getDescription());
        $this->assertEquals("title", $role->getTitle());
        $this->assertEquals("ROLE_TEST", $role->getRole());
        $this->assertTrue($role->isHidden());
    }



    public function testEmptyConfigurationFromArray ()
    {
        $role = StaticRole::createFromConfiguration("ROLE_TEST", []);

        $this->assertNull($role->getTitle());
        $this->assertNull($role->getDescription());
        $this->assertFalse($role->isHidden());
    }
}
