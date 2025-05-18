<?php

   namespace App\Service;

   use Symfony\Component\Mailer\MailerInterface;
   use Symfony\Component\Mime\Email;
   use App\Entity\Emprunt;
   
   class EmailService
   {
       private MailerInterface $mailer;
   
       public function __construct(MailerInterface $mailer)
       {
           $this->mailer = $mailer;
       }
   
       public function sendConfirmationEmprunt(Emprunt $emprunt): void
       {
           $utilisateur = $emprunt->getUtilisateur();
           $livre = $emprunt->getLivre();
   
           $email = (new Email())
               ->from('ssou13765@gmail.com')
               ->to($utilisateur->getEmail())
               ->subject('Confirmation de votre emprunt')
               ->text("Bonjour {$utilisateur->getNom()},\n\nVous avez emprunté le livre \"{$livre->getTitre()}\". La date de retour prévue est le {$emprunt->getDateRetour()->format('d/m/Y')}.\n\nMerci de respecter cette date.\n\nBibliothèque");
   
           $this->mailer->send($email);
       }
   
       public function sendRappelRetour(Emprunt $emprunt): void
       {
           $utilisateur = $emprunt->getUtilisateur();
           $livre = $emprunt->getLivre();
   
           $email = (new Email())
               ->from('ssou13765@gmail.com')
               ->to($utilisateur->getEmail())
               ->subject('Rappel de Retour de Livre')
               ->text("Bonjour {$utilisateur->getNom()},\n\nRappel : Vous devez retourner le livre \"{$livre->getTitre()}\" avant le {$emprunt->getDateRetour()->format('d/m/Y')}.\n\nMerci !");
   
           $this->mailer->send($email);
       }
   }
   