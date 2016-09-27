<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/10/16
 * Time: 3:36 PM
 */

namespace Core\MediaBundle\Document;

use Doctrine\MongoDB\GridFSFile;
use Doctrine\ODM\MongoDB\Mapping\Annotations as odm;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Image
 * @package Core\MediaBundle\Document
 * @odm\Document(repositoryClass="Core\MediaBundle\Repository\MediaRepository")
 */
class Media
{
    /**
     * @odm\Id()
     * @Groups({"admin"})
     */
    protected $id;
    /** @odm\File() */
    protected $file;
    /**
     * @odm\String()
     * @Groups({"admin"})
     * */
    protected $filename;
    /**
     * @odm\String
     * @Groups({"admin"})
     */
    protected $extension;
    /**
     * @odm\String()
     * @Groups({"admin"})
     */
    protected $mimeType;
    /**
     * @odm\Date()
     */
    protected $uploadDate;
    /**
     * @odm\Integer()
     * @Groups({"admin"})
     * */
    protected $size;
    /**
     * @odm\Integer()
     * @Groups({"admin"})
     */
    protected $chunkSize;
    /**
     * @odm\String()
     * @Groups({"admin"})
     */
    protected $md5;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Media
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return GridFSFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return Media
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     * @return Media
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param $extension
     * @return $this
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param mixed $mimeType
     * @return Media
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * @Groups({"admin"})
     * @return int|string
     */
    public function getCreateDate()
    {
        return $this->uploadDate instanceof \DateTime ? $this->uploadDate->getTimestamp() : '';
    }

    /**
     * @return \DateTime
     */
    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    /**
     * @param mixed $uploadDate
     * @return Media
     */
    public function setUploadDate($uploadDate)
    {
        $this->uploadDate = $uploadDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     * @return Media
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChunkSize()
    {
        return $this->chunkSize;
    }

    /**
     * @param mixed $chunkSize
     * @return Media
     */
    public function setChunkSize($chunkSize)
    {
        $this->chunkSize = $chunkSize;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMd5()
    {
        return $this->md5;
    }

    /**
     * @param mixed $md5
     * @return Media
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;
        return $this;
    }
}