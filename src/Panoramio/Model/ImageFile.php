<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace Panoramio\Model;


class ImageFile extends \Attach\Model\ImageFile
{
    /**
     * @var Author
     */
    protected $author;

    /**
     * @param \Panoramio\Model\Author $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return \Panoramio\Model\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    public function getUri($template = null)
    {
        return $this->getPath();
    }

} 