<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace Panoramio\Model;


use DeltaCore\Prototype\AbstractEntity;
use DeltaCore\Prototype\ArrayableInterface;
use DeltaCore\Prototype\ElasticEntityInterface;

class Author extends AbstractEntity implements ElasticEntityInterface, ArrayableInterface
{
    protected $id;
    protected $name;
    protected $uri;

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = (integer) $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $path
     */
    public function setUri($path)
    {
        $this->uri = $path;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }


} 