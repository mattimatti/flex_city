<?php
namespace App;

class CsrfExtension extends \Twig_Extension
{

    /**
     *
     * @var \Slim\Csrf\Guard
     */
    protected $csrf;

    /**
     *
     * @param \Slim\Csrf\Guard $csrf            
     */
    public function __construct(\Slim\Csrf\Guard $csrf)
    {
        $this->csrf = $csrf;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see Twig_Extension::getGlobals()
     */
    public function getGlobals()
    {
        // CSRF token name and value
        $csrfNameKey = $this->csrf->getTokenNameKey();
        $csrfValueKey = $this->csrf->getTokenValueKey();
        $csrfName = $this->csrf->getTokenName();
        $csrfValue = $this->csrf->getTokenValue();
        
        return [
            'csrf' => [
                'keys' => [
                    'name' => $csrfNameKey,
                    'value' => $csrfValueKey
                ],
                'name' => $csrfName,
                'value' => $csrfValue
            ]
        ];
    }

    public function getName()
    {
        return 'slim/csrf';
    }
}