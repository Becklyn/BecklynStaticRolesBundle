BecklynStaticRolesBundle
========================

As user roles are directly coupled to the application code and we would like to configure our roles using an existing VCS (instead of the DB) this bundle implements a simple role system.

You define your roles including the hierarchy in your security.yml and the system provides ways to validated that, list them and for you to select them.


Installation
------------

You can install it via composer:

```bash
$ composer require becklyn/static-roles-bundle
```

Afterwards, you need to activate the bundle in your `app/AppKernel.php`:


```php
public function registerBundles()
{
    $bundles = array(
        // ...
        new \Becklyn\StaticRolesBundle\BecklynStaticRolesBundle(),
        // ...
    );

    // ...
}
```



Configuration
-------------

Open up `app/config/security.yml` and first remove the section `role_hierarchy` that is automatically provided by symfony.

Then add your own role configuration on top of the file:

```yml
becklyn_static_roles:
    roles:
        ROLE_ADMIN:
            title: "Admin"
            included_roles: [ROLE_USER]
        ROLE_USER:
            title: "User"
            description: "The default frontend user"
```


Assigning roles to a user entity
--------------------------------

The bundle provides a form type to be used in user forms:

```php
$builder
    ->add("roles", "static_role", [
        "label" => "User roles",
        "multiple" => true,
        "expanded" => true,
    ]);
```

You will receive an array of roles in the entity as values: `["ROLE_ADMIN", "ROLE_USER"]`.

The mapping of these values can be done using the [`simple_array`][doctrine:simple-type] type of doctrine. You need to set it nullable to properly support a user without any roles.


```php
class User implements UserInterface
{
    // ...
    
    /**
     * @var string[]
     *
     * @ORM\Column(name="roles", type="simple_array", nullable=true)
     *
     */
    private $userRoles = null;


    // ...


    /**
     * @inheritdoc
     */
    public function getRoles ()
    {
        return $this->roles;
    }



    /**
     * @param string[]|null $roles
     */
    public function setRoles (array $roles = null)
    {
        $this->roles = $roles;
    }
    
    // ...
}

```


Hidden roles
------------

If you are using roles, that should be used internally, but shouldn't be presented in the form type, you can add `hidden: true` to the role definition:

```yml
becklyn_static_roles:
    roles:
        ROLE_ADMIN:
            title: "Admin"
            included_roles: [ROLE_ALLOWED_TO_SWITCH]
        ROLE_ALLOWED_TO_SWITCH:
            title: "Internal: The user is allowed to switch roles"
            hidden: true
```

In this example, only `ROLE_ADMIN` will be selectable by the user.


Note
----

If you are transforming sensitive data, please keep in mind that updating the roles of the user entity won't automatically update the roles of the authenticated user token.
You need to refresh this token.

You can fix this issue by adding this configuration in your `app/config/security.yml`:

```yml
security:
    always_authenticate_before_granting: true
```



[doctrine:simple-type]: http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html#simple-array
