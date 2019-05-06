<?php

namespace Vlada;

class Serialize {
    /**
     * Recursice serialization
     * 
     * @return array
     */
    public function serialize() {
        $return = [];
        $reflect = new \ReflectionClass($this);

        foreach ($reflect->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC) as $prop) {
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

    /**
     * @return string[]
     */
    public function paramsList() {
        $return = [];
        $reflect = new \ReflectionClass($this);

        foreach ($reflect->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC) as $prop) {
            $return[] = $prop->getName();
        }
        return $return;
    }

    /**
     * @return string[]
     */
    public function paramsListSQL() {
        $return = [];
        $reflect = new \ReflectionClass($this);

        foreach ($reflect->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC) as $prop) {
            $return[] = ':'.$prop->getName();
        }
        return $return;
    }

    /**
     * @return array
     */
    final public function toArray() {
        return $this->serialize();
    }
}


?>