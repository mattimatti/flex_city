<?php
namespace App\Service;

use App\Dao\LeadRepository;
use App\Dao\Lead;

class LeadService
{

    /**
     *
     * @var LeadRepository
     */
    protected $leadRepo;

    /**
     *
     * @param LeadRepository $leadRepo            
     */
    function __construct(LeadRepository $leadRepo)
    {
        $this->leadRepo = $leadRepo;
    }

    /**
     *
     * @return Lead
     */
    public function create(array $param)
    {
        return $this->getLeadRepo()->create($param);
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