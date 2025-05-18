<?php

namespace App\Command;

use App\Entity\Emprunt;
use App\Message\ReturnReminderMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:send-return-reminders',
    description: 'Envoie des rappels par email pour les livres à rendre dans 3 jours.',
)]
class SendReturnRemindersCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->bus = $bus;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = (new \DateTime())->modify('+3 days')->setTime(0, 0, 0);
        $end = (clone $start)->setTime(23, 59, 59);
    
        $emprunts = $this->entityManager->getRepository(Emprunt::class)->createQueryBuilder('e')
            ->where('e.date_retour BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    
        if (empty($emprunts)) {
            $output->writeln('⚠️ Aucun emprunt trouvé avec date de retour dans 3 jours.');
        }
    
        foreach ($emprunts as $emprunt) {
            $this->bus->dispatch(new ReturnReminderMessage($emprunt->getId()));
            $output->writeln("📧 Message de rappel envoyé pour l'emprunt ID : " . $emprunt->getId());
        }
    
        return Command::SUCCESS;
    }
    
}
