<?php

class Role
{
    private int $roleId;
    private string $roleName;

    /* rolePermissions is an array of Permission objects */
    private array $rolePermissions;

    public function __construct(int $id = null, string $name = null, array $permissions = null)
    {
        $this->roleId = $id;
        $this->roleName = $name;
        $this->rolePermissions = $permissions;
    }

}