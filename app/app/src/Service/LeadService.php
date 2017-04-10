<?php
namespace App\Service;

use App\Dao\LeadRepository;
use App\Dao\Lead;
use Zend\Validator\EmailAddress;
use Symfony\Component\Validator\Validation;
use App\Validator;
use App\Debug;

class LeadService
{

    /**
     *
     * @var LeadRepository
     */
    protected $leadRepo;

    /**
     *
     * @var MailService
     */
    protected $mailService;

    /**
     *
     * @param LeadRepository $leadRepo            
     * @param MailService $mailService            
     */
    function __construct(LeadRepository $leadRepo, MailService $mailService = null)
    {
        $this->leadRepo = $leadRepo;
        $this->mailService = $mailService;
    }
    
    // Array
    // (
    // [event_id] => 1
    // [name] => Matteo
    // [surname] => Monti
    // [email] => mmonti@gmail.com
    // [day] => 11
    // [month] => 12
    // [year] => 1928
    // [gender] => m
    // [pp] => y
    // [tc] => y
    // [mkt] => y
    // )
    public function filterCreate(array $params)
    {
        $allowed = array(
            'event_id',
            'name',
            'surname',
            'email',
            'phone',
            'city',
            'day',
            'month',
            'year',
            'gender',
            'pp',
            'tc',
            'mkt'
        );
        
        return array_filter($params, function ($key) use($allowed)
        {
            return in_array($key, $allowed);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     *
     * @param unknown $params            
     */
    public function validateCreate(array $params)
    {
        $validator = new Validator();
        
        $validator->validateNotEmpty($params, array(
            'event_id',
            'name',
            'surname',
            'email',
            'phone',
            'city',
            'day',
            'month',
            'year',
            'gender',
            'pp',
            'tc',
            'mkt'
        ));
        
        $validator->validateEmail($params, 'email');
        
        $validator->validateDigits($params, array(
            'event_id',
            'day',
            'month',
            'year'
        ));
        
        $validator->validateDatePart($params, array(
            'day',
            'month'
        ));
        $validator->validateDay($params, array(
            'day'
        ));
        $validator->validateMonth($params, array(
            'month'
        ));
        $validator->validateYear($params, array(
            'year'
        ));
    }

    /**
     * Remove a lead by email address.
     *
     * @param string $email
     *            The email
     */
    public function remove($email)
    {
        return $this->getLeadRepo()->removeByEmail($email);
    }

    /**
     *
     * @return Lead
     */
    public function create(array $param)
    {
        $param = $this->filterCreate($param);
        
        $this->validateCreate($param);
        
        $lead = $this->getLeadRepo()->create($param);
        
        if ($this->getMailService()) {
            
            $this->mailService->addRecipient($lead);
            
            $parameters = $lead->export();
            // Debug::dump($parameters);
            
            $this->mailService->send($parameters);
        }
        
        return $lead;
    }

    /**
     *
     * @return the $mailService
     */
    public function getMailService()
    {
        return $this->mailService;
    }

    /**
     *
     * @return LeadRepository
     */
    public function getLeadRepo()
    {
        return $this->leadRepo;
    }

    /**
     *
     * @param \App\Dao\LeadRepository $leadRepo            
     */
    public function setLeadRepo($leadRepo)
    {
        $this->leadRepo = $leadRepo;
    }
}