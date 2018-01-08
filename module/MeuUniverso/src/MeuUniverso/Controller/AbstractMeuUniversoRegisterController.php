<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 15/09/2017
 * Time: 15:32
 */

namespace MeuUniverso\Controller;

use Application\Service\EntityManagerAwareInterface;
use JasperPHP\JasperPHP;
use MeuUniverso\Service\AuthenticationAwareInterface;
use Util\Controller\AbstractController;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Headers;
use Zend\Http\Response\Stream;

class AbstractMeuUniversoRegisterController extends AbstractMeuUniversoController
{
    protected $reportBasePath = __DIR__ ."/../../../../../data/reports";
    protected $reportOutputPath = __DIR__ ."/../../../../../public/reports";

    public function prepareReport(array $items, $reportName, $format)
    {
        //Generate ID to report
        $report_id = time(false).'_'.mt_rand();

        $jsonFile = $this->createJsonFile(json_encode($items), $report_id);
        if(!file_exists($jsonFile)) {
            throw new \Exception("Arquivo $jsonFile não foi encontrado");
        }

        $input = $this->reportBasePath
            .DIRECTORY_SEPARATOR
            .'reports_files'
            .DIRECTORY_SEPARATOR
            .$reportName.".jasper";

        if(!file_exists($input)) {
            throw new \Exception("Arquivo $input não foi encontrado");
        }

        $output = $this->reportOutputPath
            .DIRECTORY_SEPARATOR
            .$report_id;

        mkdir($output, 0775, true);

        $options = [
            'format' => is_array($format) ? $format : [$format],
            'params' => [],
            'locale' => 'pt_BR',
            'db_connection' => [
                'driver' => 'json',
                'data_file' => $jsonFile,
                'json_query' => 'object'
            ]
        ];

        $jasper = new JasperPHP();
        $jasper->process(
            $input,
            $output,
            $options
        )->execute();

        if($this->getRequest()->isXmlHttpRequest()) {
            $reportUrl = rtrim($this->url()->fromRoute('universoproducao'), '/');
            $reportUrl.= '/reports/'
                . $report_id
                . '/' . $reportName.".".$format;

            $report = new JsonModel();
            $report->setTerminal(true);
            $report->report = $reportUrl;
        } else {
            $reportFile =
                $output
                .DIRECTORY_SEPARATOR
                .$reportName.".".$format;

            $report = $this->dowloadReport($reportFile);
        }

        return $report;
    }

    public function dowloadReport($report_file, $downloadToken=null)
    {
        if(!file_exists($report_file)) {
            throw new \Exception("Arquivo $report_file não encontrado");
        }

        $response = new Stream();
        $response->setStream(fopen($report_file, 'r'));
        $response->setStatusCode(200);
        $response->setStreamName(basename($report_file));

        $headers = new Headers();
        $headers->addHeaders([
            'Content-Disposition' => 'attachment; filename="' . basename($report_file) .'"',
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => filesize($report_file),
            'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public'
        ]);

        $response->setHeaders($headers);
        return $response;
    }

    public function createJsonFile($content, $reportId) {
        $fileName =
            $this->reportBasePath
            .DIRECTORY_SEPARATOR
            .'data_files'
            .DIRECTORY_SEPARATOR
            .$reportId.'.json';

        $jsonFile = fopen($fileName, 'w');
        if(!$jsonFile) {
            throw new \Exception("Não foi possível criar o arquivo $fileName");
        }

        fwrite($jsonFile, $content);
        fclose($jsonFile);

        return $fileName;
    }
}