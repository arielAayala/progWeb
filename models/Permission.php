<?php
include_once "C:/xampp/htdocs/progWeb/models/Module.php";

class Permission
{
    private int $permissionId;
    private Module $module;
    private bool $canCreate;
    private bool $canRead;
    private bool $canUpdate;
    private bool $canDelete;

    public function __construct(int $permissionid, Module $module = null, bool $create = null, bool $read = null, bool $update = null, bool $delete = null)
    {
        $this->module = $module;
        $this->canCreate = $create;
        $this->canRead = $read;
        $this->canUpdate = $update;
        $this->canDelete = $delete;
    }


}