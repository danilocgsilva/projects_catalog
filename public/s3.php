<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

return function (array $context) {
    
    $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
    $kernel->boot();
    $container = $kernel->getContainer();
    
    $awsClientId = $container->getParameter('s3_client_id');
    $awsClientSecret = $container->getParameter('s3_client_secret');

    $s3Client = new S3Client([
        'region' => 'us-east-1',
        'version' => '2006-03-01',
        'credentials' => [
            'key'    => $awsClientId,
            'secret' => $awsClientSecret
        ]
    ]);
    
    /**
     * @var \Aws\Result
     */
    $listedObjectResponse = $s3Client->listObjectsV2([
        'Bucket' => "projects-catalog",
    ]);
    foreach ($listedObjectResponse['Contents'] as $entryInRoot) {
        print($entryInRoot['Key'] . "<br />");
    }
    
    print("Hello world!" . "<br />");
};





