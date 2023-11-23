<?php
include 'vendor/autoload.php';

use Unleash\Client\UnleashBuilder;
use Unleash\Client\Configuration\Context;
use Unleash\Client\ContextProvider\UnleashContextProvider;
use Unleash\Client\Configuration\UnleashContext;

final class MyContextProvider implements UnleashContextProvider
{
    public function getContext(): Context
    {
        $context = new UnleashContext();
        $context->setCurrentUserId('madhu');
        $context->setCustomProperty('rootouid','71F199C21061451C5BA755A103F5C770');
        return $context;
    }
}

$contextProvider = new MyContextProvider();
$unleash = UnleashBuilder::create()
    ->withAppName('unleash-proxy')
    ->withHeader('Authorization', 'default:development.unleash-insecure-api-token')
    ->withInstanceId('your-instance-1')
    ->withAppUrl('http://localhost:4242/api/')
    ->withContextProvider($contextProvider)
    ->build();

$featureFlagNames = ["ShowCurrentDateTime", "ShowInUppercase", "myff1"];

foreach ($featureFlagNames as $flagName) {
    $isEnabled = $unleash->isEnabled($flagName);
    $text = "Feature flag $flagName : is " . ($isEnabled ? "enabled" : "disabled") . "\n";
    echo nl2br($text);
}

$content = "\nA PHP client that demonstrates Unleash Feature Toggle\n";
if($unleash->isEnabled("ShowInUppercase"))
    $content = strtoupper($content);
echo nl2br($content);

$body = "\nIts a beautiful day.\n";
if($unleash->isEnabled("ShowCurrentDateTime")) {
    $body += "\nCurrent DateTime is " . gmdate('Y-m-d H:i:s', time()) . "\n";
}
echo nl2br($body); 

ob_flush();
flush();