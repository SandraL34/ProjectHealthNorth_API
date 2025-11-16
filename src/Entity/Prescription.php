<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prescription
 *
 * @ORM\Table(name="prescription")
 * @ORM\Entity
 */
class Prescription
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="report", type="string", length=1024, nullable=true)
     */
    private $report;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prescription_details", type="string", length=1024, nullable=true)
     */
    private $prescriptionDetails;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReport(): ?string
    {
        return $this->report;
    }

    public function setReport(?string $report): static
    {
        $this->report = $report;

        return $this;
    }

    public function getPrescriptionDetails(): ?string
    {
        return $this->prescriptionDetails;
    }

    public function setPrescriptionDetails(?string $prescriptionDetails): static
    {
        $this->prescriptionDetails = $prescriptionDetails;

        return $this;
    }


}
