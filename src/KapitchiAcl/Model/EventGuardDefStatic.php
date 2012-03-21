<?php

namespace KapitchiAcl\Model;

use Zend\EventManager\Event,
    KapitchiBase\Model\ModelAbstract;

class EventGuardDefStatic extends ModelAbstract implements EventGuardDef {
    protected $eventId;
    protected $event;
    protected $resource;
    protected $privilege;
    
    public function getEventId() {
        return $this->eventId;
    }

    public function setEventId($eventId) {
        $this->eventId = $eventId;
    }

    public function getEvent() {
        return $this->event;
    }

    public function setEvent($event) {
        $this->event = $event;
    }

    public function getResource() {
        return $this->resource;
    }

    public function setResource($resource) {
        $this->resource = $resource;
    }

    public function getPrivilege() {
        return $this->privilege;
    }

    public function setPrivilege($privilege) {
        $this->privilege = $privilege;
    }

}