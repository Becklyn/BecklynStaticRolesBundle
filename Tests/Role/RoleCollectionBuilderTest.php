<?php

namespace Becklyn\StaticRolesBundle\Tests\Role;

use Becklyn\StaticRolesBundle\Role\RoleCollectionBuilder;


/**
 *
 */
class RoleCollectionBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Role names may not be empty
     *
     * @expectedException \Becklyn\StaticRolesBundle\Exception\EmptyRoleNameException
     */
    public function testEmptyRoleName ()
    {
        $roles = [
            "" => []
        ];

        $builder = new RoleCollectionBuilder();
        $builder->prepareRoleCollection($roles);
    }


    /**
     * Included role names may not be empty
     *
     * @expectedException \Becklyn\StaticRolesBundle\Exception\EmptyRoleNameException
     */
    public function testEmptyIncludedRoleName ()
    {
        $roles = [
            "a" => [
                "included_roles" => [""]
            ]
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
     *
     * @expectedException \Becklyn\StaticRolesBundle\Exception\DuplicateRoleDefinitionException
     */
    public function testDuplicateDefinitions ($firstDefinition, $secondDefinition)
    {
        $roles = [
            $firstDefinition => [],
            $secondDefinition => []
        ];

        $builder = new RoleCollectionBuilder();
        $builder->prepareRoleCollection($roles);
    }



    /**
     * @expectedException \Becklyn\StaticRolesBundle\Exception\UnknownRoleException
     */
    public function testUnknownIncludedRole ()
    {
        $roles = [
            "a" => [
                "included_roles" => ["b"]
            ]
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
                "included_roles" => ["ROLE_B"],
            ],
            "ROLE_B" => [
                "title" => "b",
                "description" => "desc",
            ]
        ], $builder->prepareRoleCollection($roles));
    }
}
