<?php


namespace Alura\Pdo\Domain\Model;


class Phone
{
    private ?int $id;
    private string $areaCode;
    private string $number;

    /**
     * Phone constructor.
     * @param int|null $id
     * @param string $areaCode
     * @param string $number
     */
    public function __construct(?int $id, string $areaCode, string $number)
    {
        $this->id = $id;
        $this->areaCode = $areaCode;
        $this->number = $number;
    }


    public function formattedPhone()
    {
        return "({$this->areaCode}) $this->number";
    }

}