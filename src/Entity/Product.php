<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column(length: 10)]
    private ?string $price = null;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column(length: 2, nullable: true)]
    private ?string $rate = null;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\OneToMany(mappedBy: 'productId', targetEntity: Comment::class)]
    private Collection $comments;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Brand $brand = null;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products', cascade: ['persist'] )]
    private Collection $category;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\ManyToMany(targetEntity: Order::class, mappedBy: 'product')]
    private Collection $orders;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?Basket $basket = null;
    
    #[Groups(['show_product', 'list_product'])]
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favorite')]
    private Collection $productUserFavorite;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->category = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->productUserFavorite = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(?string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setProductId($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProductId() === $this) {
                $comment->setProductId(null);
            }
        }

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }
    
    public function getCategoryName(): Collection
    {
        return $this->category->name;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->addProduct($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            $order->removeProduct($this);
        }

        return $this;
    }

    public function getBasket(): ?Basket
    {
        return $this->basket;
    }

    public function setBasket(?Basket $basket): self
    {
        $this->basket = $basket;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getProductUserFavorite(): Collection
    {
        return $this->productUserFavorite;
    }

    public function addProductUserFavorite(User $productUserFavorite): self
    {
        if (!$this->productUserFavorite->contains($productUserFavorite)) {
            $this->productUserFavorite->add($productUserFavorite);
            $productUserFavorite->addFavorite($this);
        }

        return $this;
    }

    public function removeProductUserFavorite(User $productUserFavorite): self
    {
        if ($this->productUserFavorite->removeElement($productUserFavorite)) {
            $productUserFavorite->removeFavorite($this);
        }

        return $this;
    }
}
