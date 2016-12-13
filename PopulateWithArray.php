<?php
namespace DataTablesBundle;


abstract class PopulateWithArray
{
    protected $propertyNameToClassName = array();

    public function __construct(array $propertyNameToValueArray = array())
    {
        foreach ($propertyNameToValueArray as $propertyName => $value) {
            if (property_exists(get_class($this), $propertyName)) {
                $this->setPropertyValue($propertyName, $value);
            }
        }
    }

    private function setPropertyValue($propertyName, $value)
    {
        if (is_array($value)) {
            $this->setPropertyArrayValue($propertyName, $value);
        } else {
            $this->$propertyName = $value;
        }
    }

    private function setPropertyArrayValue($propertyName, array $values)
    {
        if (!$this->propertyNameToClassName[$propertyName]) {
            throw new \Exception('Mapping error: class name for property with object or objects not provided');
        }
        $this->setValueForPropertyWithCollection($propertyName, $values);
        $this->setValueForPropertyWithObject($propertyName, $values);
    }

    private function setValueForPropertyWithCollection($propertyName, array $values)
    {
        if (is_array($this->$propertyName)) {
            foreach ($values as $value) {
                $this->$propertyName[] = new $this->propertyNameToClassName[$propertyName]($value);
            }
        }
    }

    private function setValueForPropertyWithObject($propertyName, array $values)
    {
        if (!is_array($this->$propertyName)) {
            $this->$propertyName = new $this->propertyNameToClassName[$propertyName]($values);
        }
    }
}