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

    /**
     * @param $id
     * @param $name
     * @param $uri
     * @return Author
     * @deprecated
     */
    public function createAuthor($id, $name, $uri)
    {
        $data = [
            "id" => $id,
            "name" => $name,
            "uri" => $uri,
        ];
        return $this->create($data);
    }

    public function load(Author $entity, $data)
    {
        $entity->setId($data["id"]);
        $entity->setName($data["name"]);
        $entity->setUri($data["uri"]);
        $idMap = $this->getIdentityMap();
        $idMap->set($entity->getId(), $entity);
        return $entity;
    }

    public function create($data = null)
    {
        $author = new Author();
        if (!empty($data)) {
            $this->load($author, $data);
        }
        return $author;
    }

} 