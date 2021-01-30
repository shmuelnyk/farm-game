<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 */
class AdminOption
{

    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     */
    protected $id;


    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64)
     */
    private $optionKey;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64,nullable=true)
     */
    private $optionValue;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getOptionKey(): ?string
    {
        return $this->optionKey;
    }

    /**
     * @param string|null $optionKey
     */
    public function setOptionKey(?string $optionKey): void
    {
        $this->optionKey = $optionKey;
    }

    /**
     * @return string|null
     */
    public function getOptionValue(): ?string
    {
        return $this->optionValue;
    }

    /**
     * @param string|null $optionValue
     */
    public function setOptionValue(?string $optionValue): void
    {
        $this->optionValue = $optionValue;
    }



}
