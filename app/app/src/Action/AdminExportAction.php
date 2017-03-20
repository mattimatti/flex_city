<?php
namespace App\Action;

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

final class AdminExportAction extends AbstractAction
{

    public function __invoke(Request $request, Response $response, $args)
    {
        // Debug::dump($args);
        if (! isset($args['resource'])) {
            exit("no resource found");
        }
        
        $resource = $args['resource'];
        
        $fileName = $resource . '_' . time() . '.csv';
        
        $filePath = '' . $fileName;
        
        // the data object
        $data = array();
        
        switch ($resource) {
            
            case 'leads':
                
                $leadRepo = new LeadRepository();
                
                /* @var $allLeads Lead [] */
                $allLeads = $leadRepo->findAll();
                
                foreach ($allLeads as $lead) {
                    $data[] = $lead->getView();
                }
                
                break;
            
            default:
                
                break;
        }
        
        // get the headers
        $headers = array_keys($data[0]);
        
        // preprend the headers
        array_unshift($data, $headers);
        
        // Your input data
        $reader = new ArrayReader($data);
        
        // Create the workflow from the reader
        $workflow = new StepAggregator($reader);
        
        // Add the writer to the workflow
        // $filePath = new \SplFileObject('output.csv', 'w');
        $writer = new CsvWriter(';', '"', null, true, true);
        $writer->setStream(fopen($filePath, 'w'));
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
