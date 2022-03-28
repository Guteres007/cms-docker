<?php


namespace App\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

class LabelDataObject
{
    #[Assert\NotBlank]
    public $title;
}