<?php


namespace App\DataObject;
use Symfony\Component\Validator\Constraints as Assert;


class PostDataObject
{
    #[Assert\NotBlank]
    public $title;

    #[Assert\NotBlank]
    public $description;

}