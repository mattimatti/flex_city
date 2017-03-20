<?php
namespace App;


/**
 *
 * @author mattimatti
 *        
 */
class Messages extends \Slim\Flash\Messages
{

    public function addWarning($message)
    {
        $this->addMessage('warning', $message);
    }

    public function addSuccess($message)
    {
        $this->addMessage('success', $message);
    }

    public function addError($message)
    {
        $this->addMessage('danger', $message);
    }
}