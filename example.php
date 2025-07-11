<?php
require_once 'vendor/autoload.php';

use Renfrewshire\Bartec\Adapter\Version16Adapter;
use Renfrewshire\Bartec\Client\Client as BartecClient;
use Renfrewshire\Bartec\Client\SoapClient;
use Renfrewshire\Bartec\Exception\SoapException;
use Renfrewshire\Bartec\Response\Response;
use Renfrewshire\Bartec\Service\BartecService;

// BARTEC CLIENT USAGE
$bartecClient = new BartecClient(
    new SoapClient(BartecClient::WSDL_AUTH),
    new SoapClient(Version16Adapter::WSDL_COLLECTIVE_API), // v16
    'BARTEC_API_USERNAME',
    'BARTEC_API_PASSWORD'
);

// Fetch a token
try {
    $token = $bartecClient->getAuthToken();
} catch (SoapException $e) {
    // handle $e (see further down)
}

// Fetch an IFRAME token
try {
    $token = $bartecClient->getIFrameAuthToken();
} catch (SoapException $e) {
    // handle $e (see further down)
}

// Service call to retrieve service classes
try {
    $response = $bartecClient->call('ServiceRequests_Classes_Get');
    // get the SOAP result
    $result = $response->getResult();
} catch (SoapException $e) {
    // You have the following available in $e
    $debug = $e->getResponse()->getDebugInfo();     // SOAP debug ie. __getLastRequest(), __getLastResponse() etc if you set 'trace' => 1
    $fault = $e->getResponse()->getFault();         // \SoapFault if it was thrown or NULL
    $exception = $e->getResponse()->getException(); // SoapClients \Exception if thrown  or NULL
    $error = $e->getResponse()->getErrorMessage();  // Bartec's $result->Errors->Error->Message OR $result->Errors->Message if available or NULL
}

// BARTEC SERVICE USAGE
/** @var BartecService $bartecService */
$bartecService = new BartecService(
    $bartecClient,
    Version16Adapter::VERSION // v16
    // OPTIONAL: Override the WSDL in the version adapters
    // OPTIONAL: Any cache library implementing Psr\Cache\CacheItemPoolInterface
);

// optional for debugging
 $bartecService->setClientSoapOptions(['connection_timeout' => 20, 'trace' => 1]);
 $debugInfo = $bartecService->getClient()->getCollectiveSoapClient()->getDebugInfo();

/** @var Response $response */
$response = $bartecService->getServiceRequestClasses();

$result = $response->getResult();
