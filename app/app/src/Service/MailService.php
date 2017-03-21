<?php
namespace App\Service;

use App\Service\Mailing\IEmailRecipient;

class MailService
{

    /**
     *
     * @var \PHPMailer
     */
    protected $mailer;

    /**
     *
     * @var MailRenderer
     */
    protected $renderer;

    /**
     *
     * @var IEmailRecipient []
     */
    protected $recipients;

    /**
     *
     * @var IEmailRecipient
     */
    protected $sender;

    /**
     *
     * @param \PHPMailer $mailer            
     * @param unknown $renderer            
     */
    public function __construct(\PHPMailer $mailer, MailRenderer $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
        $this->recipients = array();
        $this->sender = null;
    }

    /**
     *
     * @param IEmailRecipient $recipient            
     */
    public function addRecipient(IEmailRecipient $recipient)
    {
        $this->recipients[] = $recipient;
    }

    /**
     *
     * @param IEmailRecipient $sender            
     */
    public function addSender(IEmailRecipient $sender)
    {
        $this->sender = $sender;
    }

    /**
     */
    public function send(array $parameters)
    {
        $this->prepare($parameters);
        
        if (! $this->sender) {
            throw new \Exception('Missing Sender');
        }
        
        if (count($this->recipients) == 0) {
            throw new \Exception('Missing Recipient/s');
        }
        
        $this->mailer->setFrom($this->sender->getEmail(), $this->sender->getLabel());
        
        foreach ($this->recipients as $recipient) {
            $this->mailer->addAddress($recipient->getEmail(), $recipient->getLabel());
        }
        
        // send the message, check for errors
        if (! $this->mailer->send()) {
            throw new \Exception('Unable to send');
        } else {
            return true;
        }
    }

    /**
     */
    protected function prepare(array $parameters)
    {
        
        // render with param
        $this->renderer->getMessage($parameters);
        
        $this->mailer->Subject = $this->renderer->getSubject();
        $this->mailer->msgHTML($this->renderer->getBodyHtml());
        $this->mailer->AltBody = $this->renderer->getBodyText();
        
        // Attach an image file
        // $this->mailer->addAttachment('images/phpmailer_mini.png');
    }

    /**
     *
     * @return PHPMailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }
}