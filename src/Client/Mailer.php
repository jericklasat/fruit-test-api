<?php

declare(strict_types=1);

namespace App\Client;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
  public function __construct(
    private MailerInterface $mailer,
  ){}
  
  public function sendEmail(string $from, string $to, string $subject, string $body): void
  {
    $email = (new Email())
      ->from($from)
      ->to($to)
      ->subject($subject)
      ->text($body);

    $this->mailer->send($email);
  }
}
