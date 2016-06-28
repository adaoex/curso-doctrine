<?php

namespace App\Entity;

abstract class BaseEntity implements \ArrayAccess
{

    public function __construct(array $data = array())
    {
        foreach ($data as $key => $value) {
            $this->__set($key, $value);
        }
    }

    public function __get($name)
    {
        $method = "get" . ucwords($name);
        if (method_exists($this, $method)){
            return $this->$method();
        }elseif (property_exists($this, $name)){
            return $this->$name;
        }
    }

    public function __set($name, $value)
    {
        $method = "set" . ucwords($name);
        if (method_exists($this, $method)){
            return $this->$method($value);
        }elseif (property_exists($this, $name)){
            $this->$name = $value;
        }
    }

    /* Méthodes */

    public function offsetExists($offset)
    {
        if (property_exists($this, $offset)){
            return true;
        }else{
            return false;
        }
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->__set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        if (property_exists($this, $offset)):
            unset($this->$offset);
        endif;
    }

    public function __toString()
    {
        ob_start();
        var_dump($this);
        return ob_get_clean();
    }

    public function serialize()
    {
        return json_encode($this->toArray());
    }

    public function deszerialize($json)
    {
        $datas = json_decode($json);
        $this->__construct($datas);
    }

}
