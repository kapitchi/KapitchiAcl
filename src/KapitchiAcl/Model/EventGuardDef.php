<?php
namespace KapitchiAcl\Model;

interface EventGuardDef {
    public function getEventId();
    public function getEvent();
    public function getResource();
    public function getPrivilege();
}