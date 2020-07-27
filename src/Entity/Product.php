<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $special_offer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img_path;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer")
     */
    private $free_item;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_eco;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSpecialOffer(): ?int
    {
        return $this->special_offer;
    }

    public function setSpecialOffer(int $special_offer): self
    {
        $this->special_offer = $special_offer;

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->img_path;
    }

    public function setImgPath(string $img_path): self
    {
        $this->img_path = $img_path;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getFreeItem(): ?int
    {
        return $this->free_item;
    }

    public function setFreeItem(int $free_item): self
    {
        $this->free_item = $free_item;

        return $this;
    }

    public function getPriceEco(): ?int
    {
        return $this->price_eco;
    }

    public function setPriceEco(int $price_eco): self
    {
        $this->price_eco = $price_eco;

        return $this;
    }
}
