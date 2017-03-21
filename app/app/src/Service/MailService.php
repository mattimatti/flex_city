<?php
namespace App\Service;

class MailService
{

    /**
     *
     * @var \PHPMailer
     */
    protected $mailerer;

    /**
     *
     * @var MailRenderer
     */
    protected $renderer;

    /**
     *
     * @param \PHPMailer $mailerer            
     * @param unknown $renderer            
     */
    public function __construct(\PHPMailer $mailer, MailRenderer $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    /**
     */
    public function send(array $parameters)
    {
        $this->prepare($parameters);
        
        $this->mailer->setFrom('mmonti@gmail.com', 'Matteo Monti');
        $this->mailer->addReplyTo('mmonti@gmail.com', 'Matteo Monti');
        $this->mailer->addAddress('mmonti@gmail.com', 'Matteo Monti');
        
        // send the message, check for errors
        if (! $this->mailer->send()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     */
    protected function prepare(array $parameters)
    {
        $this->mailer->IsSendmail();
        
        // render with param
        $this->renderer->getMessage($parameters);
        
        $this->mailer->Subject = $this->renderer->getSubject();
        $this->mailer->msgHTML($this->renderer->getBodyHtml());
        $this->mailer->AltBody = $this->renderer->getBodyText();
        
        // Attach an image file
        // $this->mailer->addAttachment('images/phpmailer_mini.png');
    }
}