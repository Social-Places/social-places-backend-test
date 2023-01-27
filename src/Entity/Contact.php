<?php

namespace App\Entity;

use App\Traits\Entity\HasDateCreated;
use App\Traits\Entity\HasId;
use App\Traits\Entity\HasName;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]

class Contact
{
    use HasId;
    use HasName;
    use HasDateCreated;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $surname = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(min: 2, max: 255)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    #[Assert\Length(min: 2, max: 20)]
    private ?string $contactNumber = null;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => false])]
    private bool $newsletter = false;

    public function getSurname(): ?string {
        return $this->surname;
    }

    public function setSurname(?string $surname): void {
        $this->surname = $surname;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): void {
        $this->email = $email;
    }

    public function getContactNumber(): ?string {
        return $this->contactNumber;
    }

    public function setContactNumber(?string $contactNumber): void {
        $this->contactNumber = $contactNumber;
    }

    public function getNewsletter(): bool {
        return $this->newsletter;
    }

    public function setNewsletter(bool $newsletter): void {
        $this->newsletter = $newsletter;
    }
}
