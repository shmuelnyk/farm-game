<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 */
class QuizAnswer
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Participant", inversedBy="quizAnswers")
     */
    private $participant;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $test;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $optionOne;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $optionTwo;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $answer;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $raw;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $time;

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
     * @return string
     */
    public function getTest(): string
    {
        return $this->test;
    }

    /**
     * @param string $test
     */
    public function setTest(string $test): void
    {
        $this->test = $test;
    }

    /**
     * @return string
     */
    public function getOptionOne(): string
    {
        return $this->optionOne;
    }

    /**
     * @param string $optionOne
     */
    public function setOptionOne(string $optionOne): void
    {
        $this->optionOne = $optionOne;
    }

    /**
     * @return string
     */
    public function getOptionTwo(): string
    {
        return $this->optionTwo;
    }

    /**
     * @param string $optionTwo
     */
    public function setOptionTwo(string $optionTwo): void
    {
        $this->optionTwo = $optionTwo;
    }

    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     */
    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @return string|null
     */
    public function getRaw(): ?string
    {
        return $this->raw;
    }

    /**
     * @param string|null $raw
     */
    public function setRaw(?string $raw): void
    {
        $this->raw = $raw;
    }



    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(?Participant $participant): self
    {
        $this->participant= $participant;

        return $this;
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime(string $time): void
    {
        $this->time = $time;
    }



}
