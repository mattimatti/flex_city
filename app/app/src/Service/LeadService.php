<?php
namespace App\Service;

use App\Dao\LeadRepository;
use App\Dao\Lead;
use RedBeanPHP\R;
use Zend\Validator\EmailAddress;
use Symfony\Component\Validator\Validation;
use App\Validator;
use App\Debug;
use Symfony\Component\Translation\Translator;
use Port\Reader\ArrayReader;
use Port\Steps\StepAggregator;
use Port\Csv\CsvWriter;

class LeadService
{

    /**
     *
     * @var LeadRepository
     */
    protected $leadRepo;

    /**
     *
     * @var Translator
     */
    protected $translator;

    /**
     *
     * @var MailService
     */
    protected $mailService;

    /**
     * The application settings
     *
     * @var \stdClass
     */
    protected $settings;

    /**
     *
     * @param LeadRepository $leadRepo            
     * @param MailService $mailService            
     * @param Translator $translator            
     */
    function __construct(LeadRepository $leadRepo, MailService $mailService = null, $translator = null, $settings)
    {
        $this->leadRepo = $leadRepo;
        
        $this->mailService = $mailService;
        
        $this->translator = $translator;
        
        $this->settings = $settings;
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
            'product',
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
        $validator = new Validator($this->translator, $this->settings);
        
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
            'pp'
        // 'tc', // Removed and joined to pp
        // 'mkt' // optional field
                ));
        
        $validator->validateEmail($params, 'email');
        
        $validator->validateFieldDuplicated($params, 'email', $this->getLeadRepo());
        
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
        
        $param['date_create'] = R::isoDateTime();
        
        $lead = $this->getLeadRepo()->create($param);
        
        if ($this->getMailService()) {
            
            $this->mailService->addRecipient($lead);
            
            $emailParameters = $lead->export();
            
            // Debug::dump($emailParameters);
            
            $this->mailService->send($emailParameters);
        }
        
        return $lead;
    }

    /**
     *
     * @param
     *            $daysSince
     *            
     * @return array
     */
    public function getSummary($daysSince = 0)
    {
        $summary = array();
        
        if ($daysSince !== 0) {
            $daysSince = "$daysSince days";
        } else {
            $daysSince = '';
        }
        
        $summary['howmany'] = $this->leadRepo->count($daysSince);
        $summary['prizes'] = $this->leadRepo->countBy('prize', $daysSince);
        $summary['gender'] = $this->leadRepo->countBy('gender', $daysSince);
        $summary['country'] = $this->leadRepo->countBy('country', $daysSince);
        $summary['model'] = $this->leadRepo->countBy('model', $daysSince);
        
        return $summary;
    }

    /**
     *
     * @return the $mailService
     */
    public function export($items)
    {
        $data = array();
        
        foreach ($items as $lead) {
            $data[] = $lead->getView();
        }
        
        $fileName = 'all_leads_' . time() . '.csv';
        $filePath = '' . $fileName;
        
        // Your input data
        $reader = new ArrayReader($data);
        
        // Create the workflow from the reader
        $workflow = new StepAggregator($reader);
        
        $writer = new CsvWriter(';', '"', null, true, true);
        $writer->setStream(fopen($filePath, 'w'));
        
        // Add the writer to the workflow
        $workflow->addWriter($writer);
        
        // Process the workflow
        $workflow->process();
        
        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment;filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            
            @unlink($filePath);
            
            exit();
        }
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