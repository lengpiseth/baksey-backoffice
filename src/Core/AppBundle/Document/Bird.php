<?php
namespace Core\AppBundle\Document;

use Core\CoreBundle\Document\CoreDocument;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as odm;

/**
 * Class Bird
 * @package AppBundle\Document
 * @odm\Document(repositoryClass="Core\AppBundle\Repository\BirdRepository")
 * @odm\HasLifecycleCallbacks()
 */
class Bird extends CoreDocument
{
    /**
     * @odm\String()
     * @Groups({"default"})
     */
    protected $name;
    /**
     * @odm\String()
     * @Groups({"default"})
     * */
    protected $knownAs;
    /** @odm\String() */
    protected $scientificName;
    /** @odm\String() */
    protected $description;

    /** @odm\String() */
    protected $nameInKhmer;
    /** @odm\String() */
    protected $knownAsInKhmer;
    /** @odm\String() */
    protected $scientificNameInKhmer;
    /** @odm\String() */
    protected $descriptionInKhmer;

    /** @odm\Collection() */
    protected $thumbnails = array();
    /** @odm\Collection() */
    protected $photos = array();
    /** @odm\String() */
    protected $kingdom;
    /** @odm\String() */
    protected $phylum;
    /** @odm\String() */
    protected $class;
    /** @odm\String() */
    protected $family;
    /** @odm\String() */
    protected $genus;
    /** @odm\String() */
    protected $species;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getKnownAs()
    {
        return $this->knownAs;
    }

    /**
     * @param mixed $knownAs
     * @return Bird
     */
    public function setKnownAs($knownAs)
    {
        $this->knownAs = $knownAs;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getScientificName()
    {
        return $this->scientificName;
    }

    /**
     * @param mixed $scientificName
     * @return Bird
     */
    public function setScientificName($scientificName)
    {
        $this->scientificName = $scientificName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Bird
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNameInKhmer()
    {
        return $this->nameInKhmer;
    }

    /**
     * @param mixed $nameInKhmer
     * @return Bird
     */
    public function setNameInKhmer($nameInKhmer)
    {
        $this->nameInKhmer = $nameInKhmer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getKnownAsInKhmer()
    {
        return $this->knownAsInKhmer;
    }

    /**
     * @param mixed $knownAsInKhmer
     * @return Bird
     */
    public function setKnownAsInKhmer($knownAsInKhmer)
    {
        $this->knownAsInKhmer = $knownAsInKhmer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescriptionInKhmer()
    {
        return $this->descriptionInKhmer;
    }

    /**
     * @param mixed $descriptionInKhmer
     * @return Bird
     */
    public function setDescriptionInKhmer($descriptionInKhmer)
    {
        $this->descriptionInKhmer = $descriptionInKhmer;

        return $this;
    }

    /**
     * @return string
     */
    public function getScientificNameInKhmer()
    {
        return $this->scientificNameInKhmer;
    }

    /**
     * @param string $scientificNameInKhmer
     * @return $this
     */
    public function setScientificNameInKhmer($scientificNameInKhmer)
    {
        $this->scientificNameInKhmer = $scientificNameInKhmer;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getThumbnails()
    {
        return $this->thumbnails;
    }

    /**
     * @param ArrayCollection $thumbnails
     * @return Bird
     */
    public function setThumbnails($thumbnails)
    {
        $this->thumbnails = $thumbnails;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param $photos
     * @return Bird
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * @param $photo
     * @return $this
     */
    public function removePhoto($photo)
    {
        $this->photos->removeElement($photo);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getKingdom()
    {
        return $this->kingdom;
    }

    /**
     * @param mixed $kingdom
     * @return Bird
     */
    public function setKingdom($kingdom)
    {
        $this->kingdom = $kingdom;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhylum()
    {
        return $this->phylum;
    }

    /**
     * @param mixed $phylum
     * @return Bird
     */
    public function setPhylum($phylum)
    {
        $this->phylum = $phylum;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     * @return Bird
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * @param mixed $family
     * @return Bird
     */
    public function setFamily($family)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGenus()
    {
        return $this->genus;
    }

    /**
     * @param mixed $genus
     * @return Bird
     */
    public function setGenus($genus)
    {
        $this->genus = $genus;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * @param mixed $species
     * @return Bird
     */
    public function setSpecies($species)
    {
        $this->species = $species;

        return $this;
    }
}