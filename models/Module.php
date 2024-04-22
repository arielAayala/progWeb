<?php

class Module
{
    private int $moduleId;
    private string $moduleName;

    public function __construct(int $id = null, string $name = null)
    {
        $this->moduleId = $id;
        $this->moduleName = $name;
    }

}