<?php

namespace Struzik\EPPClient;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Storage for namespace URIs.
 */
class NamespaceCollection implements Countable, IteratorAggregate, ArrayAccess
{
    public const NS_NAME_ROOT = 'epp';
    public const NS_NAME_DOMAIN = 'domain';
    public const NS_NAME_CONTACT = 'contact';
    public const NS_NAME_HOST = 'host';

    private array $collection;

    public function __construct()
    {
        $this->collection = [];
    }

    /**
     * Returns the number of elements in the collection.
     * Implementation of the Countable interface.
     */
    public function count(): int
    {
        return count($this->collection);
    }

    /**
     * Gets an iterator for iterating over the elements in the collection.
     * Implementation of the IteratorAggregate interface.
     */
    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->collection);
    }

    /**
     * Checks whether the collection contains a specific key.
     * Implementation of the ArrayAccess interface.
     *
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->collection[$offset]);
    }

    /**
     * Gets the element with the given key.
     * Implementation of the ArrayAccess interface.
     *
     * @param mixed $offset
     */
    #[\ReturnTypeWillChange]
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
    public function offsetSet($offset, $value): void
    {
        $this->collection[$offset] = $value;
    }

    /**
     * Removes an element with a specific key from the collection.
     * Implementation of the ArrayAccess interface.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->collection[$offset]);
        }
    }
}
