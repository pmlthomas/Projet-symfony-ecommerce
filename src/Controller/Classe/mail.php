<?php

namespace App\Classe;

use Mailjet\Resources;
use Mailjet\Client;

class Mail
{
    private $api_key = 'e4c0732a1d76eeba94983aac06e4c8a7';
    private $api_secret_key = '978b31a9dec7c22dde5afdff81f39b96';
    public function send($to_email, $to_name, $subject, $content){

        $mj = new Client($this->api_key, $this->api_secret_key, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "pmlthomaspro@gmail.com",
                        'Name' => "Pml boutique"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 4107763,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}