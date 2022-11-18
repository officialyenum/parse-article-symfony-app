<?php

namespace App\Message;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class CreateArticle
{
    private $title;
    private $desc;
    private $picture;
    
    public function __construct($data){
        $this->title = $data['title'];
        $this->desc = $data['desc'];
        $this->picture = $data['image'];
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDesc():string
    {
        return $this->desc;
    }

    public function getPicture():string
    {
        return $this->picture;
    }
}
