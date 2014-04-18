<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace Panoramio\Model;


use DeltaDb\IdentityMap;

class AuthorManager
{
    /**
     * @var IdentityMap
     */
    protected $identityMap;

    /**
     * @param \DeltaDb\IdentityMap $identityMap
     */
    public function setIdentityMap($identityMap)
    {
        $this->identityMap = $identityMap;
    }

    /**
     * @return \DeltaDb\IdentityMap
     */
    public function getIdentityMap()
    {
        if (is_null($this->identityMap)) {
            $this->identityMap = new IdentityMap();
        }
        return $this->identityMap;
    }

    public function getAuthor($id)
    {
        $idMap = $this->getIdentityMap();
        return $idMap->get($id);
    }

    public function createAuthor($id, $name, $uri)
    {
        $author = new Author();
        $author->setId($id);
        $author->setName($name);
        $author->setUri($uri);

        $idMap = $this->getIdentityMap();
        $idMap->set($author->getId(), $author);
        return $author;
    }

} 