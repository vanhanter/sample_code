<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Таблица услуг
 *
 * @package App\Entity
 */
#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\Table(
    name: '`service`',
    options: ['comment' => 'Услуги']
)]
class Service
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Название услуги
     *
     * @var string|null
     */
    #[ORM\Column(
        length: 255,
        nullable: false,
        unique: true
    )]
    #[Assert\NotNull(message: 'Название сервиса не может быть null')]
    #[Assert\NotBlank(message: 'Название сервиса не может быть пустым значением')]
    private ?string $name = null;

    /**
     * Стоимость услуги
     *
     * @var string|null
     */
    #[ORM\Column(
        type: Types::DECIMAL,
        precision: 10,
        scale: 2,
        nullable: false
    )]
    #[Assert\NotNull(message: 'Цена не может быть null')]
    #[Assert\Positive(message: 'Цена должна быть положительным числом')]
    private ?string $price = null;

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

}
