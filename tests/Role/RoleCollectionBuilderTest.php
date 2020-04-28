<?php

namespace Tests\Becklyn\StaticRolesBundle\Role;

use Becklyn\StaticRolesBundle\Exception\DuplicateRoleDefinitionException;
use Becklyn\StaticRolesBundle\Exception\EmptyRoleNameException;
use Becklyn\StaticRolesBundle\Role\RoleCollectionBuilder;
use PHPUnit\Framework\TestCase;


/**
 *
 */
class RoleCollectionBuilderTest extends TestCase
{
    /**
     * Role names may not be empty
     */
    public function testEmptyRoleName ()
    {
        $this->expectException(EmptyRoleNameException::class);

        $roles = [
            "" => []
        ];

        $builder = new RoleCollectionBuilder();
        $builder->prepareRoleCollection($roles);
    }



    public function dataProviderDuplicateDefinitions ()
    {
        return [
            ["a", "A"],
            ["ROLE_A", "a"],
            ["ROLE_A", "A"],
        ];
    }



    /**
     * @dataProvider dataProviderDuplicateDefinitions
     *
     * @param $firstDefinition
     * @param $secondDefinition
     */
    public function testDuplicateDefinitions ($firstDefinition, $secondDefinition)
    {
        $this->expectException(DuplicateRoleDefinitionException::class);
        $roles = [
            $firstDefinition => [],
            $secondDefinition => []
        ];

        $builder = new RoleCollectionBuilder();
        $builder->prepareRoleCollection($roles);
    }



    public function testTransformation ()
    {
        $roles = [
            "a" => [
                "included_roles" => ["b"],
            ],
            "b" => [
                "title" => "b",
                "description" => "desc",
            ]
        ];

        $builder = new RoleCollectionBuilder();

        $this->assertEquals([
            "ROLE_A" => [
                "included_roles" => ["b"],
            ],
            "ROLE_B" => [
                "title" => "b",
                "description" => "desc",
            ]
        ], $builder->prepareRoleCollection($roles));
    }
}
