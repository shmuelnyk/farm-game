<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ApiResource(
 *   collectionOperations={
 *      "get",
 *      "post"
 *  },
 *  itemOperations={
 *      "get",
 *      "put",
 *      "delete"
 *  }
 * )
 */
class Participant
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
     * @ORM\Column(type="string", length=180 , nullable=true)
     */
    private $mTurkId;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuizAnswer", mappedBy="participant")
     */
    private $quizAnswers;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());

        $this->quizAnswers= new ArrayCollection();
    }

    /**
     * @return Collection|QuizAnswer[]
     */
    public function getQuizAnswers(): Collection
    {
        return $this->quizAnswers;
    }


    public function addQuizAnswer(QuizAnswer $quizAnswer): self
    {
        if (!$this->quizAnswers->contains($quizAnswer)) {
            $this->quizAnswers[] = $quizAnswer;
            $quizAnswer->setParticipant($this);
        }

        return $this;
    }

    public function removeQuizAnswer(QuizAnswer $quizAnswer): self
    {
        if ($this->quizAnswers->contains($quizAnswer)) {
            $this->quizAnswers->removeElement($quizAnswer);
            // set the owning side to null (unless already changed)
            if ($quizAnswer->getParticipant() === $this) {
                $quizAnswer->setParticipant(null);
            }
        }

        return $this;
    }

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
    public function getMTurkId(): ?string
    {
        return $this->mTurkId;
    }

    /**
     * @param string|null $mTurkId
     */
    public function setMTurkId(?string $mTurkId): void
    {
        $this->mTurkId = $mTurkId;
    }


    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }



}
