## ⚠️ Under development ⚠️
This package is still under development and not yet ready for production use. Please use it with caution and at your own risk. We will update this notice once the package is stable and production-ready.

# Introduction
The `fet/postcard-api` package is a PHP implementation of the Swiss Postcard API (see [References](#references)).

# Installation
To install this package, use Composer:

```bash
composer require fet/postcard-api
```

> Make sure you have Composer installed on your system before running this command.

# Configuration
Create a new `Fet\PostcardApi\PostcardCreator` instance using your API credentials:

```php
use Fet\PostcardApi\PostcardCreator;

$postcardCreator = PostcardCreator::factory(
    'POSTCARD_API_URL', // The base URL of the postcard API
    'POSTCARD_API_CAMPAIGN_KEY', // Your postcard API campaign key
    'POSTCARD_API_CLIENT_ID', // Your postcard API client ID
    'POSTCARD_API_CLIENT_SECRET', // Your postcard API client secret
);
```

# Usage
## Campaign
The campaign API allows you to retrieve information about your campaign. To interact with the campaign API, first obtain the campaign instance from the postcard creator:
```php
$campaign = $postcardCreator->getCampaign();
```

Once you have the campaign instance, you can access its information using the following methods:

```php
// get the unique identifier for the campaign
$campaign->getCampaignKey();

// get the total quota of postcards allowed in the campaign
$campaign->getQuota();

// get the number of postcards already created within the campaign
$campaign->getNumberOfCreatedPostcards();

// get the remaining number of postcards that can be created in the campaign
$campaign->getNumberOfAvailablePostcards();
```

## Postcard
To use the postcard API, you need to provide recipient and sender address details, the path to the front image of the postcard and the text to be printed on the postcard. Here's an example of how to do this:

```php
use Fet\PostcardApi\PostcardCreator;

// recipient address details
$recipient = [
    'title' => 'Mr.',
    'firstname' => 'John',
    'lastname' => 'Smith',
    'company' => 'ABC Inc.',
    'street' => '123 Main St.',
    'houseNr' => '456',
    'zip' => '12345',
    'city' => 'Anytown',
    'country' => 'United States',
    'poBox' => 'P.O. Box 789',
    'additionalAdrInfo' => 'Apt. 789',
];

// sender address details
$sender = [
    'firstname' => 'Jane',
    'lastname' => 'Doe',
    'company' => 'XYZ Corp.',
    'street' => '456 Elm St.',
    'houseNr' => '789',
    'zip' => '67890',
    'city' => 'Anyville',
];

// path to the front image
$image = 'path-to-image';

// text to be printed on the postcard
$senderText = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';

// create the postcard
$postcard = $postcardCreator->create($recipient, $sender, $image, $senderText);

// approve the postcard
$postcard->approve();
```

> The `$recipient` and `$sender` array have no required key/value pairs, but the `$postcard` instance will return errors and warnings if something went wrong.

### Advanced usage
You can further manipulate the `$postcard` instance if you want:

```php
// add a stamp image to the postcard
$postcard->addStampImage('path-to-stamp-image');

// add branding text to the postcard
$postcard->addBrandingText('branding-text');

// add a branding image to the postcard
$postcard->addBrandingImage('path-to-branding-image');

// add a branding QR tag to the postcard, with an optional side text
$postcard->addBrandingQrTag('qr-tag-text', 'qr-tag-side-text');

// get a base64-encoded preview image of the front of the postcard
$postcard->getFrontPreview()->getImageData();

// get a base64-encoded preview image of the back of the postcard
$postcard->getBackPreview()->getImageData();
```

> The `addBrandingText()`, `addBrandingImage()`, and `addBrandingQrTag()` methods cannot be used in combination with each other, as they will overwrite each other's content. Choose one method to use for your postcard branding.

### Approval
The postcard can only be approved if no errors are returned from the API. Otherwise, a `Fet\PostcardApi\Exception\PostcardException.php` exception will be thrown.

### Handling warnings and errors
If there are warnings or errors returned from the API during the process of creating a postcard, you can retrieve them as follow:

```php
// returns a multidimensional array with warnings
$warnings = $postcard->getWarnings();

// returns a multidimensional array with errors
$errors = $postcard->getErrors();
```

# Demo
## Configuration
Before running the demo, you have to edit the `examples/config.php` file:

```php
return [
    'url' => 'POSTCARD_API_URL',
    'campaign_key' => 'POSTCARD_API_CAMPAIGN_KEY',
    'client_id' => 'POSTCARD_API_CLIENT_ID',
    'client_secret' => 'POSTCARD_API_CLIENT_SECRET',
];
```

## Running the demo in your browser
To launch a demo and see how the postcard API works in action, follow these steps:

1. Start a local PHP development server by running the following command:

```bash
php -S localhost:8888 -t examples
```

2. Open your web browser and navigate to the following URL: `http://localhost:8888/`

# Tests
Run the tests with:

```bash
composer test
```

# References
## Technical specifications
- https://developer.post.ch/en/technical-specifications-of-postcard-api

## Swagger documentation
- https://api.post.ch/pcc-doc/doctools/swagger-ui/dist/index.html?url=https://api.post.ch/pcc-doc/doctools/swagger-ui/src/openapi/api.yaml&_ga=2.187366318.709186670.1679047313-1057897365.1677000967