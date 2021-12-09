<?php

namespace App\Entity;

use App\Repository\TricksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\AbstractType;

/**
 * @ORM\Entity(repositoryClass=TricksRepository::class)
 */
class Tricks extends AbstractType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img_background;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

//    /**
//     * @ORM\Column(type="string", length=255)
//     */
//    private $media_urls;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $groupe;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_modification;


    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImgBackground(): ?string
    {
        return $this->img_background;
    }

    public function setImgBackground(string $img_background): self
    {
        $this->img_background = $img_background;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGroupe(): ?string
    {
        return $this->groupe;
    }

    public function setGroupe(?string $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->date_modification;
    }

    public function setDateModification(?\DateTimeInterface $date_modification): self
    {
        $this->date_modification = $date_modification;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

//    public function getMediaUrls(): ?string
//    {
//        return $this->media_urls;
//    }
//
//    public function setMediaUrls(?string $media_urls): self
//    {
//        $this->media_urls = $media_urls;
//
//        return $this;
//    }

    public function __construct()
    {
        $this->medias = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

      /**
     * @ORM\OneToMany(targetEntity="App\Entity\Media", mappedBy="trick")
     */
    private $medias;

    /**
     * @return Collection|media[]
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function setMedias(?string $medias): self
    {
        $this->medias = $medias;

        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="trick")
     */
    private $comments;

    /**
     * @return Collection|comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="tricks")
     */
    private $user;

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }


}
