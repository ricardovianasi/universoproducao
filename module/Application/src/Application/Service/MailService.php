<?php
namespace Application\Service;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class MailService extends AbstractPlugin
{
    const API_KEY = "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";
    const TEMPLATE_ID = "ea6fb0da-5956-4fa1-a4c5-58febfe7427e";

    protected $apiService;
    protected $defaultFrom;

    public function __construct()
    {
        if(!$this->apiService) {
            $this->apiService = new \SendGrid(self::API_KEY);
        }

        return $this;
    }

    public function simpleSendEmail(array $recipients, $subject="", $msg="", $filename=null)
    {
        $to = new \SendGrid\Email(key($recipients), $recipients[key($recipients)]);

        $content = new \SendGrid\Content("text/html", $msg);

        $mail = new \SendGrid\Mail($this->getDefaultFrom(), $subject, $to, $content);
        $mail->setTemplateId(self::TEMPLATE_ID);

        if($filename) {
            if(file_exists($filename)) {
                $attach = new \SendGrid\Attachment();
                $attach->setDisposition("attachment");
                $attach->setType("application/pdf");
                $attach->setFilename(basename($filename));
                $attach->setContent(base64_encode(file_get_contents($filename)));

                $mail->addAttachment($attach);
            }
        }

        $response = $this->getApiService()->client->mail()->send()->post($mail);
        return $response;

    }

    public function getServiceMail()
    {

    }

    public function send()
    {
        $request_body = json_decode(
            '{
              "personalizations": [
                {
                  "to": [
                    {
                      "email": "ricardovianasi@gmail.com"
                    }
                  ],
                  "subject": "Sending with SendGrid is Fun"
                }
              ],
              "from": {
                "email": "comunicados@universoproducao.com.br"
              },
              "content": [
                {
                    "type": "text/html",
                    "value": "replacing the <strong>body tag</strong>"
                }
              ],
              "template_id": "ea6fb0da-5956-4fa1-a4c5-58febfe7427e"
            }'
        );

        $sg = new \SendGrid(self::API_KEY);
        $response = $sg->client->mail()->send()->post($request_body);
        echo $response->statusCode();
        print_r($response->body());
        print_r($response->headers());
        exit();
    }


    /**
     * @return \SendGrid
     */
    public function getApiService()
    {
        return $this->apiService;
    }

    public function getDefaultFrom()
    {
        return new \SendGrid\Email('Universo Produção', "no-replay@universoproducao.comn.br");
    }

    public function generateButton($label, $href='#')
    {
        return sprintf(
            '<a style="background-color: #f15a22; color:#fff; text-decoration: none; padding: 10px; display: inline-block;" href="%s">%s</a>',
            $href,
            $label
        );
    }

    public function sendMessage($message, array $data=[])
    {

    }
}