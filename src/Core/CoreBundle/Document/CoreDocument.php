<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 10-Apr-16
 * Time: 9:55 AM
 */

namespace Core\CoreBundle\Document;

use Core\CoreBundle\CoreApp\AppTime;
use Core\UserBundle\Document\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as odm;

/**
 * Class CoreDocument
 * @package Core\CoreBundle\Document
 * @odm\MappedSuperclass()
 * @odm\HasLifecycleCallbacks()
 */
abstract class CoreDocument
{
    /** @odm\Id() */
    protected $id;
    /** @odm\ReferenceOne(targetDocument="Core\UserBundle\Document\User", nullable=true, simple=true) */
    protected $author;
    /** @odm\String() */
    protected $name;
    /** @odm\Integer() */
    protected $createDate;
    /** @odm\Integer() */
    protected $updateDate;
    /** @odm\String() */
    protected $alias;
    /** @odm\Boolean() */
    protected $delete;
    /** @odm\Boolean() */
    protected $active;
    /** @odm\Integer() */
    protected $order;

    public function __construct()
    {
        $this->active = true;
        $this->delete = false;
        $this->order  = 0;
    }

    /** @odm\PrePersist() */
    public function prePersist()
    {
        $this->createDate = $this->updateDate = AppTime::millisecond();
    }

    /** @odm\PreRemove() */
    public function preRemove()
    {

    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setAuthor(User $user)
    {
        $this->author = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CoreDocument
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param mixed $createDate
     * @return CoreDocument
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * @param mixed $updateDate
     * @return CoreDocument
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     * @return CoreDocument
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * @param mixed $delete
     * @return CoreDocument
     */
    public function setDelete($delete)
    {
        $this->delete = $delete;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     * @return CoreDocument
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     * @return CoreDocument
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}