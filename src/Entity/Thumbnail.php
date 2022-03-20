<?php

namespace App\Entity;

use App\Repository\ThumbnailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ThumbnailRepository::class)
 */
class Thumbnail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Thumb::class, mappedBy="thumbnail")
     */
    private $thumbs;

    public function __construct()
    {
        $this->thumbs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Thumb[]
     */
    public function getThumbs(): Collection
    {
        return $this->thumbs;
    }

    public function addThumb(Thumb $thumb): self
    {
        if (!$this->thumbs->contains($thumb)) {
            $this->thumbs[] = $thumb;
            $thumb->setThumbnail($this);
        }

        return $this;
    }

    public function removeThumb(Thumb $thumb): self
    {
        if ($this->thumbs->removeElement($thumb)) {
            // set the owning side to null (unless already changed)
            if ($thumb->getThumbnail() === $this) {
                $thumb->setThumbnail(null);
            }
        }

        return $this;
    }
}
