<?php
namespace App\Service;

class MailRenderer
{

    protected $twig;

    protected $subject = '';

    protected $bodyHtml = '';

    protected $bodyText = '';

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     *
     * @param unknown $identifier            
     * @param unknown $parameters            
     */
    public function getMessage($parameters = array(), $identifier = 'default')
    {
        $template = $this->twig->loadTemplate('mail/' . $identifier . '.twig'); // Define your own schema
        
        $this->subject = $template->renderBlock('subject', $parameters);
        $this->bodyHtml = $template->renderBlock('body_html', $parameters);
        $this->bodyText = $template->renderBlock('body_text', $parameters);
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getBodyHtml()
    {
        return $this->bodyHtml;
    }

    public function getBodyText()
    {
        return $this->bodyText;
    }
}