<?php

namespace KapitchiAcl\Guard;

class ProtectorEvent implements Guard {
    protected $aclService;
    
    public function bootstrap() {
        
    }
    
    public function getAclService() {
        return $this->aclService;
    }

    public function setAclService($aclService) {
        $this->aclService = $aclService;
    }

}