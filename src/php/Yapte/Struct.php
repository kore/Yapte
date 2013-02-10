<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte;

/**
 * Struct base class
 *
 * @version $Revision$
 */
abstract class Struct
{
    /**
     * Generic constructor from value array
     *
     * @param array $values
     * @return void
     */
    public function __construct(array $values = array())
    {
        foreach ($values as $name => $value) {
            $this->$name = $value;
        }
    }

    /**
     * Disabled getter
     *
     * @param string $name
     * @return void
     */
    public function __get($name)
    {
        throw new \OutOfRangeException("Unknown property \${$name}.");
    }

    /**
     * Disabled setter
     *
     * @param string $name
     * @return void
     */
    public function __set($name)
    {
        throw new \OutOfRangeException("Unknown property \${$name}.");
    }

    /**
     * Disabled unset
     *
     * @param mixed $name
     * @return void
     */
    public function __unset($name)
    {
        throw new \OutOfRangeException("Unknown property \${$name}.");
    }
}
