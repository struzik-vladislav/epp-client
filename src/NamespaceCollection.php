<?php

namespace Struzik\EPPClient;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use ArrayAccess;

/**
 * Storage for namespace URIs.
 */
class NamespaceCollection implements Countable, IteratorAggregate, ArrayAccess
{
    const NS_NAME_ROOT = 'epp';
    const NS_NAME_DOMAIN = 'domain';
    const NS_NAME_CONTACT = 'contact';
    const NS_NAME_HOST = 'host';

    /**
     * @var array
     */
    private $collection;

    public function __construct()
    {
        $this->collection = array();
    }

    /**
     * Returns the number of elements in the collection.
     * Implementation of the Countable interface.
     *
     * @return int
     */
    public function count()
    {
        return count($this->collection);
    }

    /**
     * Gets an iterator for iterating over the elements in the collection.
     * Implementation of the IteratorAggregate interface.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->collection);
    }

    /**
     * Checks whether the collection contains a specific key.
     * Implementation of the ArrayAccess interface.
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->collection[$offset]);
    }

    /**
     * Gets the element with the given key.
     * Implementation of the ArrayAccess interface.
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->collection[$offset];
        }

        return null;
    }

    /**
     * Adds/sets an element in the collection with the specified key.
     * Implementation of the ArrayAccess interface.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->collection[$offset] = $value;

        return $this;
    }

    /**
     * Removes an element with a specific key from the collection.
     * Implementation of the ArrayAccess interface.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->collection[$offset]);
        }

        return null;
    }
}
