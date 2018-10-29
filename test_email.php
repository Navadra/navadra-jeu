<?php
/**
* This call sends an email to one recipient, using a validated sender address
* Do not forget to update the sender address used in the sample
*/
use \Mailjet\Resources;
$mj = new \Mailjet\Client('1fe4b30f069c66c071b06c535c8f72aa', '78a5b20e1a1d173373ff6d74ddb54328');
$body = [
    'FromEmail' => "julien@navadra.com",
    'FromName' => "Test email",
    'Subject' => "Test file",
    'Text-part' => "Some text...",
    'Html-part' => "<h3>HTML test</h3>!",
    'Recipients' => [
        [
            'Email' => "jrevault@gmail.com"
        ]
    ]
];
$response = $mj->post(Resources::$Email, ['body' => $body]);
$response->success() && var_dump($response->getData());

