<?php

namespace Vlada;

abstract class Serialize {
    /**
     * Recursice serialization
     * 
     * @return array
     */
    protected function serialize($params) {
        $return = [];
        $reflect = new \ReflectionClass($this);

        foreach ($reflect->getProperties($params) as $prop) {
            $param = $prop->getName();
            $prop->setAccessible(true);
            $value = $prop->getValue($this);
            $prop->setAccessible(false);

            if ($value instanceof Serialize) {
                $return[$param] = $value->serialize($params);
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
    public function updatesListSQL() {
        $return = [];
        $reflect = new \ReflectionClass($this);

        foreach ($reflect->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PRIVATE) as $prop) {
            if ($prop->getName() !== 'id')
                $return[] = $prop->getName().' = :'.$prop->getName();
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
    final public function toArraySQL() {
        return $this->serialize(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PRIVATE);
    }

    /**
     * @return array
     */
    final public function toArray() {
        return $this->serialize(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC);
    }
}


?>