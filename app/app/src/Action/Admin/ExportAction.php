<?php
namespace App\Action\Admin;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Dao\UserRepository;
use App\Dao\EventRepository;
use App\Acl;
use App\Debug;
use App\Dao\LeadRepository;
use Port\Csv\CsvWriter;
use Port\Reader\ArrayReader;
use Port\Steps\StepAggregator;
use Port\Steps\Step\ValueConverterStep;
use App\Dao\Lead;
use App\Action\AbstractAction;

final class ExportAction extends AbstractAction
{

    public function __invoke(Request $request, Response $response, $args)
    {
        // Debug::dump($args);
        if (! isset($args['resource'])) {
            exit("no resource found");
        }
        
        $resource = 'lead';
        
        $event_id = null;
        if (isset($args['event_id'])) {
            $event_id = $args['event_id'];
        }
        
        $fileName = 'all_leads_' . time() . '.csv';
        if ($event_id) {
            $fileName = 'leads_event_' . $event_id . '_' . time() . '.csv';
        }
        
        $filePath = '' . $fileName;
        
        // the data object
        $data = array();
        
        $leadRepo = new LeadRepository();
        
        /* @var $allLeads Lead [] */
        $allLeads = $leadRepo->findByEvent($event_id);
        
        foreach ($allLeads as $lead) {
            $data[] = $lead->getView();
        }
        
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
        
        return $response;
    }
}
