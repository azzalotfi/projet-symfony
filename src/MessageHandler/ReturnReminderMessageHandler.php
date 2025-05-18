<?php

namespace App\MessageHandler;

use App\Entity\Emprunt;
use App\Message\ReturnReminderMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ReturnReminderMessageHandler
{
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    public function __invoke(ReturnReminderMessage $message)
    {
        $emprunt = $this->entityManager->getRepository(Emprunt::class)->find($message->getEmpruntId());

        if (!$emprunt) {
            return;
        }

        $utilisateur = $emprunt->getUtilisateur();
        $livre = $emprunt->getLivre();

        $email = (new Email())
            ->from('ssou13765@gmail.com')
            ->to($utilisateur->getEmail())
            ->subject('📚 Rappel de Retour de Livre')
            ->text(
                "Bonjour {$utilisateur->getNom()},\n\n" .
                "Ceci est un rappel que vous devez retourner le livre : \"{$livre->getTitre()}\".\n" .
                "Date de retour prévue : " . $emprunt->getDateRetour()->format('d/m/Y') . ".\n\n" .
                "Merci de respecter la date de retour.\nBibliothèque."
            );

        $this->mailer->send($email);
    }
}
