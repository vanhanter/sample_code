<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Таблица пользователей
 *
 * @package App\Entity
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(
    name: '`user`',
    options: ['comment' => 'Пользователи - Аутентификация']
)]
#[ORM\UniqueConstraint(
    name: 'UNIQ_IDENTIFIER_EMAIL',
    fields: ['email']
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Почта пользователя - уникальный идентификатор
     *
     * @var string|null
     */
    #[ORM\Column(length: 180, nullable: false)]
    #[Assert\NotNull(message: 'Почта не может быть null')]
    #[Assert\NotBlank(message: 'Почта не может быть пустым значением')]
    #[Assert\Email(message: 'Формат электронной почты НЕ корректен')]
    private ?string $email = null;

    /**
     * Роль пользователя
     *
     * @var list<string> The user roles
     */
    #[ORM\Column(
        nullable: true,
        options: ['default' => null])]
    private ?array $roles = null;

    /**
     * Пароль пользователя
     *
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotNull(message: 'Пароль не может быть null')]
    #[Assert\NotBlank(message: 'Пароль не может быть пустым значением')]
    #[Assert\PasswordStrength(message: 'Сложности пароля НЕ достаточная')]
    private ?string $password = null;

    /**
     * Поле таблицы Order
     *
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'user')]
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setUser($this);
        }

        return $this;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }
}
