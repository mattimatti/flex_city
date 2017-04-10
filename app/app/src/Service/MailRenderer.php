<?php
namespace App\Service;

use App\Debug;

/**
 * This service renders
 *
 * @author mattimatti
 *        
 */
class MailRenderer
{

    protected $twig;

    protected $settings;

    protected $subject = '';

    protected $bodyHtml = '';

    protected $bodyText = '';

    /**
     *
     * @param \Twig_Environment $twig            
     */
    public function __construct(\Twig_Environment $twig, $settings)
    {
        $this->twig = $twig;
        $this->settings = $settings;
        
        // create a base url.
        $domain = $this->settings['domain'];
        $baseUrl = "http://$domain/";
        $this->settings['baseUrl'] = $baseUrl;
        $this->settings['unsubscribeUrl'] = $baseUrl . 'unsubscribe/';
    }

    /**
     *
     * @param unknown $identifier            
     * @param unknown $parameters            
     */
    public function getMessage($parameters = array(), $identifier = 'default')
    {
        $template = $this->twig->loadTemplate('mail/' . $identifier . '.twig');
        
        // Append settings
        $parameters['settings'] = $this->settings;
        
        // this way we render single blocks..
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