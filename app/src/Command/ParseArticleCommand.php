<?php

namespace App\Command;

use DOMXPath;
use DOMDocument;
use App\Entity\Article;
use App\Message\CreateArticle;
use App\Service\MessageService;
use Doctrine\ORM\EntityManager;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ParseArticleCommand extends Command
{
    /**
     * @var EntityRepository
     */
    private $entityManager;
    private $repository;
    private $bus;

    protected static $defaultName = 'parse:article';
    protected static $defaultDescription = 'Get articles from the internet and publish to app db';

    /**
     * TemporaryEmailRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $doctrine, MessageBusInterface $bus)
    {
        $this->entityManager = $doctrine->getManager();
        $this->repository = $doctrine->getRepository(Article::class);
        $this->bus = $bus;
        parent::__construct();
    }
    

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to parse articles...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            //code...
            $client = HttpClient::create();
            $response = $client->request('GET', 'https://highload.today/category/novosti/',[
                'headers' => [
                    'Accept' => 'text/plain',
                ],
            ]);
    
            $htmlString = (string) $response->getContent();
            //add this line to suppress any warnings
            libxml_use_internal_errors(true);
            $doc = new DOMDocument();
            $doc->loadHTML($htmlString);
            $xpath = new DOMXPath($doc);
            $titles = $xpath->evaluate('//div[@class="lenta-item"]//a//h2');
            $pictures = $xpath->evaluate('//div[@class="lenta-item"]//a//div[@class="lenta-image"]//img/@src');
            $descriptions = $xpath->evaluate('//div[@class="lenta-item"]//p');
            $count = 0;
            foreach ($titles as $key => $title) {
                // check if title exist in db
                $article = $this->repository->findOneBy(['title' => $title->textContent]);
                if(!$article){
                    $word = "https://";
                    $image = strval($pictures[$key]->nodeValue);
                    $desc = $descriptions[$key]->textContent;
                    $desc = (strlen($desc) > 50) ? substr($desc,0,50) : $desc;
                    // publish article with rabbitMQ for creation
                    // $this->bus->createArticle($titles[$key]->textContent,$pictures[$key]->nodeValue,$descriptions[$key]->textContent);
                    
                    // Test if string contains the word 
                    if(strpos($image, $word) !== false){
                        $io->note(sprintf('published desc : %s', $desc));
                        $count++;
                        $io->note(sprintf('published : %s', $count));
                        $data = [
                            'title' => strval($titles[$key]->textContent),
                            'desc' => empty($desc) ? "..." : $desc,
                            'image' => $image
                        ];
                        $this->bus->dispatch(new CreateArticle($data));
                    }
                }
            }
            
            $io->success('Published '. $count.' articles successfully');
    
            return Command::SUCCESS;
        } catch (\Throwable $th) {
            $io->error($th->getMessage());
            return Command::FAILURE;
        }
    }
}
