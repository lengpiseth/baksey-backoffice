<?php

namespace Core\UserBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as odm;
use FOS\UserBundle\Document\User as BaseUser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as assert;

/**
 * @odm\Document(repositoryClass="Core\UserBundle\Repository\UserRepository")
 * @odm\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    /** @odm\Id(strategy="auto") */
    protected $id;

    /** @odm\String */
    protected $firstName;

    /** @odm\String */
    protected $lastName;

    /** @odm\String() */
    protected $profilePicture;

    /**
     * @assert\Image()
     */
    protected $profileImage;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @odm\PrePersist()
     * @odm\PreUpdate()
     */
    public function prePersist()
    {

    }

    /**
     * @odm\PreFlush()
     * @odm\PostPersist()
     * @odm\PostUpdate()
     */
    public function uploading()
    {
        if($this->profileImage instanceof UploadedFile) {
            // Delete old image if exist
            $this->removeProfilePicture();

            $avatarName = 'user_' . $this->id . '.' . $this->profileImage->getClientOriginalExtension();

            $this->profileImage->move($this->getProfilePictureUploadDir(), $avatarName);

            $this->setProfilePicture($avatarName);
        }

        $this->profileImage = null;
    }

    /**
     * @odm\PostRemove()
     */
    public function postRemove()
    {
        $this->removeProfilePicture();
    }

    private function removeProfilePicture()
    {
        if(file_exists($this->getProfilePictureUploadDir().'/'.$this->profilePicture)) {
            unlink($this->getProfilePictureUploadDir().'/'.$this->profilePicture);
        }
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return trim(ucfirst($this->firstName) . ' ' . ucfirst($this->lastName));
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = strtolower($firstName);
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = strtolower($lastName);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param mixed $profilePicture
     * @return User
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
        return $this;
    }

    /**
     * @return UploadedFile
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }

    /**
     * @param UploadedFile $profileImage
     * @return $this
     */
    public function setProfileImage(UploadedFile $profileImage = null)
    {
        $this->profileImage = $profileImage;
        return $this;
    }

    public function getProfilePictureUploadDir()
    {
        return __DIR__.'/../../../../web'.$this->getProfilePictureWebDir();
    }

    public function getProfilePictureWebDir()
    {
        return '/storage/user_profile';
    }
}