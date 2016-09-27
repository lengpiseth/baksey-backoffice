<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/11/16
 * Time: 8:58 PM
 */

namespace Core\AppBundle\Document;

use Core\CoreBundle\Document\CoreDocument;
use Core\UserBundle\Document\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as odm;

/**
 * Class Post
 * @package Core\AppBundle\Document
 * @odm\Document(repositoryClass="Core\AppBundle\Repository\PostRepository")
 */
class Post extends CoreDocument
{
    /** @odm\String() */
    protected $content;
    
    /** @odm\String() */
    protected $featureImage;

    public function __construct(User $user = null, $title = null, $content = null, $featureImage = null)
    {
        parent::__construct();
        $this->author       = $user;
        $this->name         = $title;
        $this->content      = $content;
        $this->featureImage = $featureImage;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFeatureImage()
    {
        return $this->featureImage;
    }

    /**
     * @param mixed $featureImage
     * @return Post
     */
    public function setFeatureImage($featureImage)
    {
        $this->featureImage = $featureImage;
        return $this;
    }
    
}