<?php
namespace App;

use Zend\Validator\NotEmpty;
use Zend\Validator\EmailAddress;
use Zend\Validator\Digits;
use Zend\Validator\Regex;

class Validator
{

    /**
     *
     * @param array $params            
     * @param unknown $key            
     * @throws \InvalidArgumentException
     */
    public function validateIsSet(array $params, $key)
    {
        if (! isset($params[$key])) {
            throw new \InvalidArgumentException("Missing param $key");
        }
    }

    /**
     *
     * @param array $params            
     * @param unknown $key            
     * @throws \InvalidArgumentException
     */
    public function validateDay(array $params, $key)
    {
        if (is_array($key)) {
            foreach ($key as $inkey) {
                $this->validateDay($params, $inkey);
            }
            return;
        }
        
        $this->validateIsSet($params, $key);
        
        $day = intval($params[$key], 10);
        
        if ($day > 31) {
            throw new \InvalidArgumentException("Invalid day $key");
        }
        
        if ($day <= 0) {
            throw new \InvalidArgumentException("Invalid day $key");
        }
    }

    /**
     *
     * @param array $params            
     * @param unknown $key            
     * @throws \InvalidArgumentException
     */
    public function validateMonth(array $params, $key)
    {
        if (is_array($key)) {
            foreach ($key as $inkey) {
                $this->validateMonth($params, $inkey);
            }
            return;
        }
        
        $this->validateIsSet($params, $key);
        
        $month = intval($params[$key], 10);
        
        if ($month > 12) {
            throw new \InvalidArgumentException("Invalid month $key");
        }
        
        if ($month <= 0) {
            throw new \InvalidArgumentException("Invalid month $key");
        }
    }

    /**
     *
     * @param array $params            
     * @param unknown $key            
     * @throws \InvalidArgumentException
     */
    public function validateYear(array $params, $key, $yearsLess = 100)
    {
        if (is_array($key)) {
            foreach ($key as $inkey) {
                $this->validateYear($params, $inkey, $yearsLess);
            }
            return;
        }
        
        $this->validateIsSet($params, $key);
        
        $year = intval($params[$key], 10);
        
        $date = new \DateTime();
        $currentYear = intval($date->format('Y'), 10);
        $yearsAgo = intval($date->format('Y'), 10) - $yearsLess;
        
        if ($year > $currentYear) {
            throw new \InvalidArgumentException("$key must not be in the future");
        }
        
        if ($year <= $yearsAgo) {
            throw new \InvalidArgumentException("$key must be greater than $yearsAgo");
        }
    }

    /**
     *
     * @param array $params            
     * @param unknown $key            
     * @throws \InvalidArgumentException
     */
    public function validateNotEmpty(array $params, $key)
    {
        if (is_array($key)) {
            foreach ($key as $inkey) {
                $this->validateNotEmpty($params, $inkey);
            }
            return;
        }
        
        $this->validateIsSet($params, $key);
        
        $notEmptyValidator = new NotEmpty();
        if (! $notEmptyValidator->isValid($params[$key])) {
            throw new \InvalidArgumentException("Empty value for key $key");
        }
    }

    /**
     *
     * @param array $params            
     * @param unknown $key            
     * @throws \InvalidArgumentException
     */
    public function validateDigits(array $params, $key)
    {
        if (is_array($key)) {
            foreach ($key as $inkey) {
                $this->validateDigits($params, $inkey);
            }
            return;
        }
        
        $this->validateIsSet($params, $key);
        
        $validator = new Digits();
        if (! $validator->isValid($params[$key])) {
            throw new \InvalidArgumentException("$key is not a Number");
        }
    }

    /**
     *
     * @param array $params            
     * @param unknown $key            
     * @throws \InvalidArgumentException
     */
    public function validateDatePart(array $params, $key)
    {
        if (is_array($key)) {
            foreach ($key as $inkey) {
                $this->validateDatePart($params, $inkey);
            }
            return;
        }
        
        $this->validateIsSet($params, $key);
        
        $validator = new Regex(array(
            'pattern' => '/^[0-9]{2}$/'
        ));
        
        if (! $validator->isValid("" . $params[$key])) {
            throw new \InvalidArgumentException("$key is not a date part");
        }
    }

    /**
     *
     * @param array $params            
     * @param unknown $key            
     * @throws \InvalidArgumentException
     */
    public function validateEmail(array $params, $key)
    {
        $emailValidator = new EmailAddress();
        
        // validate email
        if (! $emailValidator->isValid($params[$key])) {
            foreach ($emailValidator->getMessages() as $messageId => $message) {
                throw new \InvalidArgumentException($message);
            }
        }
    }
}