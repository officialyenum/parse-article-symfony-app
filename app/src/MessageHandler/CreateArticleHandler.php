<?php

namespace App\MessageHandler;

use Exception;
use App\Entity\Article;
use App\Message\CreateArticle;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateArticleHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
        $this->doctrine = $doctrine;
    }

    public function __invoke(CreateArticle $createArticle)
    {
        sleep(1);
        try {
            $this->entityManager->beginTransaction();
            $article = new Article();
            $article->setTitle($createArticle->getTitle());
            $article->setDescription($createArticle->getDesc());
            $article->setPicture($createArticle->getPicture());
    
            // tell Doctrine you want to (eventually) save the Article (no queries yet)
            $this->entityManager->persist($article);
    
            // actually executes the queries (i.e. the INSERT query)
            $this->entityManager->flush();
    
            $title = $createArticle->getTitle();
            echo "saved .$title.";
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            $this->doctrine->resetManager();
            throw $exception;
        }
    }
}
