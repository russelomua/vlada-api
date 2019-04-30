<?php

namespace Vlada;

class Serialize {
    /**
     * Recursice serialization
     * 
     * @return Array
     */
    public function serialize() {
        $return = [];
        $reflect = new \ReflectionClass($this);

        foreach ($reflect->getProperties() as $prop) {
            if (!$prop->isProtected())
                return;

            $param = $prop->getName();
            $value = $this->{$param};

            if ($value instanceof Serialize) {
                $return[$param] = $value->serialize();
            } else {
                $return[$param] = $value;
            }
        }
        return $return;
    }

    final public function toArray() {
        return $this->serialize();
    }
}


?>