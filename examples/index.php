<?php

use Fet\PostcardApi\PostcardCreator;
use Fet\PostcardApi\Exception\PostcardException;

include_once '../vendor/autoload.php';

$warnings = [];
$errors = [];
$branding = '';

$config = include 'config.php';

$postcardCreator = PostcardCreator::factory(
  $config['url'],
  $config['campaign_key'],
  $config['client_id'],
  $config['client_secret'],
);

try {
  $campaign = $postcardCreator->getCampaign();
} catch (PostcardException $e) {
  die(sprintf('Error: %s (%s)', $e->getCode(), $e->getMessage()));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $titleRecipient = $_POST["title"];
  $lastnameRecipient = $_POST["lastname"];
  $firstnameRecipient = $_POST["firstname"];
  $companyRecipient = $_POST["company"];
  $streetRecipient = $_POST["street"];
  $houseNrRecipient = $_POST["houseNr"];
  $zipRecipient = $_POST["zip"];
  $cityRecipient = $_POST["city"];
  $countryRecipient = $_POST["country"];
  $poBoxRecipient = $_POST["poBox"];
  $additionalAdrInfoRecipient = $_POST["additionalAdrInfo"];

  $lastnameSender = $_POST["lastnameSender"];
  $firstnameSender = $_POST["firstnameSender"];
  $companySender = $_POST["companySender"];
  $streetSender = $_POST["streetSender"];
  $houseNrSender = $_POST["houseNrSender"];
  $zipSender = $_POST["zipSender"];
  $citySender = $_POST["citySender"];

  $senderText = $_POST["senderText"];
  $destination = '';

  if (isset($_FILES["imageUpload"]) && $_FILES["imageUpload"]["error"] == 0) {
    $uploadedFile = $_FILES["imageUpload"];
    $fileName = $uploadedFile["name"];
    $fileTmpName = $uploadedFile["tmp_name"];
    $fileSize = $uploadedFile["size"];

    $destination = "/tmp/" . $fileName;
    move_uploaded_file($fileTmpName, $destination);
  }

  if (isset($_FILES["stamp"]) && $_FILES["stamp"]["error"] == 0) {
    $uploadedFile = $_FILES["stamp"];
    $fileName = $uploadedFile["name"];
    $fileTmpName = $uploadedFile["tmp_name"];
    $fileSize = $uploadedFile["size"];

    $stamp = "/tmp/" . $fileName;
    move_uploaded_file($fileTmpName, $stamp);
  }

  try {
    $postcard = $postcardCreator->create(
      [
        'title' => $titleRecipient,
        'lastname' => $lastnameRecipient,
        'firstname' => $firstnameRecipient,
        'company' => $companyRecipient,
        'street' => $streetRecipient,
        'houseNr' => $houseNrRecipient,
        'zip' => $zipRecipient,
        'city' => $cityRecipient,
        'country' => $countryRecipient,
        'poBox' => $poBoxRecipient,
        'additionalAdrInfo' => $additionalAdrInfoRecipient,
      ],
      [
        'lastname' => $lastnameSender,
        'firstname' => $firstnameSender,
        'company' => $companySender,
        'street' => $streetSender,
        'houseNr' => $houseNrSender,
        'zip' => $zipSender,
        'city' => $citySender,
      ],
      $destination,
      $senderText,
    );

    if (isset($stamp)) {
      $postcard->addStampImage($stamp);
    }

    if (isset($_POST["branding"])) {
      $branding = $_POST["branding"];

      switch ($_POST["branding"]) {
        case 'text':
          $postcard->addBrandingText($_POST["brandingText"]);
          break;
        case 'image':
          if (isset($_FILES["brandingImage"]) && $_FILES["brandingImage"]["error"] == 0) {
            $uploadedFile = $_FILES["brandingImage"];
            $fileName = $uploadedFile["name"];
            $fileTmpName = $uploadedFile["tmp_name"];
            $fileSize = $uploadedFile["size"];

            $image = "/tmp/" . $fileName;
            move_uploaded_file($fileTmpName, $image);
            $postcard->addBrandingImage($image);
          }
          break;
        case 'qrTag':
          $postcard->addBrandingQrTag($_POST["qrTagText"], $_POST["qrTagSideText"]);
          break;
      }
    }
  } catch (PostcardException $e) {
    die(sprintf('Error: %s (%s)', $e->getCode(), $e->getMessage()));
  }

  $cardKey = $postcard->getResource()->getCardKey();
  $warnings = $postcard->getWarnings();
  $errors = $postcard->getErrors();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet" />
  <title>Postcard API example</title>
</head>

<body class="bg-gray-100">
  <?php $cardKey = $cardKey ?? ''; ?>

  <!-- Campaign API Details -->
  <div class="bg-white p-4 rounded-lg shadow-md mb-8">
    <div class="flex justify-between space-x-4 text-sm">
      <div>
        <span class="font-semibold text-gray-700">Campaign Key:</span>
        <span class="text-gray-700"><?php echo $campaign->getCampaignKey(); ?></span>
      </div>
      <div>
        <span class="font-semibold text-gray-700">Quota:</span>
        <span class="text-gray-700"><?php echo $campaign->getQuota(); ?></span>
      </div>
      <div>
        <span class="font-semibold text-gray-700">Number of Created Postcards:</span>
        <span class="text-gray-700"><?php echo $campaign->getNumberOfCreatedPostcards(); ?></span>
      </div>
      <div>
        <span class="font-semibold text-gray-700">Number of Available Postcards:</span>
        <span class="text-gray-700"><?php echo $campaign->getNumberOfAvailablePostcards(); ?></span>
      </div>
    </div>
  </div>


  <div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-semibold mb-8">POSTCARD API EXAMPLE</h1>
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="space-y-8">
        <!-- Recipient -->
        <div class="bg-white p-8 rounded-lg shadow-md">
          <h2 class="text-2xl font-semibold mb-6">Recipient Address</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
              <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $titleRecipient ?? ''; ?>" />
            </div>
            <div>
              <label for="firstname" class="block text-gray-700 text-sm font-bold mb-2">First Name:</label>
              <input type="text" id="firstname" name="firstname" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $firstnameRecipient ?? ''; ?>" />
            </div>
            <div>
              <label for="lastname" class="block text-gray-700 text-sm font-bold mb-2">Last Name:</label>
              <input type="text" id="lastname" name="lastname" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $lastnameRecipient ?? ''; ?>" />
            </div>
            <div>
              <label for="company" class="block text-gray-700 text-sm font-bold mb-2">Company:</label>
              <input type="text" id="company" name="company" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $companyRecipient ?? ''; ?>" />
            </div>
            <div>
              <label for="street" class="block text-gray-700 text-sm font-bold mb-2">Street:</label>
              <input type="text" id="street" name="street" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $streetRecipient ?? ''; ?>" />
            </div>
            <div>
              <label for="houseNr" class="block text-gray-700 text-sm font-bold mb-2">House Number:</label>
              <input type="text" id="houseNr" name="houseNr" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $houseNrRecipient ?? ''; ?>" />
            </div>
            <div>
              <label for="zip" class="block text-gray-700 text-sm font-bold mb-2">ZIP Code:</label>
              <input type="text" id="zip" name="zip" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $zipRecipient ?? ''; ?>" />
            </div>
            <div>
              <label for="city" class="block text-gray-700 text-sm font-bold mb-2">City:</label>
              <input type="text" id="city" name="city" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $cityRecipient ?? ''; ?>" />
            </div>
            <div>
              <label for="country" class="block text-gray-700 text-sm font-bold mb-2">Country:</label>
              <input type="text" id="country" name="country" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $countryRecipient ?? ''; ?>" />
            </div>
            <div>
              <label for="poBox" class="block text-gray-700 text-sm font-bold mb-2">P.O. Box:</label>
              <input type="text" id="poBox" name="poBox" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $poBoxRecipient ?? ''; ?>" />
            </div>
            <div>
              <label for="additionalAdrInfo" class="block text-gray-700 text-sm font-bold mb-2">Additional Address Info:</label>
              <input type="text" id="additionalAdrInfo" name="additionalAdrInfo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $additionalAdrInfoRecipient ?? ''; ?>" />
            </div>
          </div>
        </div>

        <!-- Sender -->
        <div class="bg-white p-8 rounded-lg shadow-md">
          <h2 class="text-2xl font-semibold mb-6">Sender Address</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="firstnameSender" class="block text-gray-700 text-sm font-bold mb-2">First Name:</label>
              <input type="text" id="firstnameSender" name="firstnameSender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $firstnameSender ?? ''; ?>" />
            </div>
            <div>
              <label for="lastnameSender" class="block text-gray-700 text-sm font-bold mb-2">Last Name:</label>
              <input type="text" id="lastnameSender" name="lastnameSender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $lastnameSender ?? ''; ?>" />
            </div>
            <div>
              <label for="companySender" class="block text-gray-700 text-sm font-bold mb-2">Company:</label>
              <input type="text" id="companySender" name="companySender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $companySender ?? ''; ?>" />
            </div>
            <div>
              <label for="streetSender" class="block text-gray-700 text-sm font-bold mb-2">Street:</label>
              <input type="text" id="streetSender" name="streetSender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $streetSender ?? ''; ?>" />
            </div>
            <div>
              <label for="houseNrSender" class="block text-gray-700 text-sm font-bold mb-2">House Number:</label>
              <input type="text" id="houseNrSender" name="houseNrSender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $houseNrSender ?? ''; ?>" />
            </div>
            <div>
              <label for="zipSender" class="block text-gray-700 text-sm font-bold mb-2">ZIP Code:</label>
              <input type="text" id="zipSender" name="zipSender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $zipSender ?? ''; ?>" />
            </div>
            <div>
              <label for="citySender" class="block text-gray-700 text-sm font-bold mb-2">City:</label>
              <input type="text" id="citySender" name="citySender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $citySender ?? ''; ?>" />
            </div>
          </div>
        </div>

        <!-- Image -->
        <div class="bg-white p-8 rounded-lg shadow-md">
          <h2 class="text-2xl font-semibold mb-6">Image Upload</h2>
          <div class="flex flex-row justify-between">
            <div class="flex flex-col w-1/2 pr-4">
              <label for="imageUpload" class="block text-gray-700 text-sm font-bold mb-2 text-left">Front Image:</label>
              <input type="file" id="imageUpload" name="imageUpload" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required />
            </div>
            <div class="flex flex-col w-1/2 pl-4">
              <label for="stamp" class="block text-gray-700 text-sm font-bold mb-2 text-left">Stamp Image:</label>
              <input type="file" id="stamp" name="stamp" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
          </div>
        </div>

        <!-- Branding -->
        <div class="bg-white p-8 rounded-lg shadow-md mt-8">
          <h2 class="text-2xl font-semibold mb-6">Branding</h2>
          <div class="flex flex-col">
            <div>
              <input type="radio" id="none" name="branding" value="" onchange="handleBrandingChange()" <?php if ($branding === '') {
                                                                                                          echo 'checked';
                                                                                                        } ?> />
              <label for="none">None</label>
            </div>
            <div>
              <input type="radio" id="text" name="branding" value="text" onchange="handleBrandingChange()" <?php if ($branding === 'text') {
                                                                                                              echo 'checked';
                                                                                                            } ?> />
              <label for="text">Text</label>
            </div>
            <div>
              <input type="radio" id="image" name="branding" value="image" onchange="handleBrandingChange()" <?php if ($branding === 'image') {
                                                                                                                echo 'checked';
                                                                                                              } ?> />
              <label for="image">Image</label>
            </div>
            <div>
              <input type="radio" id="qrTag" name="branding" value="qrTag" onchange="handleBrandingChange()" <?php if ($branding === 'qrTag') {
                                                                                                                echo 'checked';
                                                                                                              } ?> />
              <label for="qrTag">QR Tag</label>
            </div>
          </div>

          <div id="textContainer" class="<?php if ($branding !== 'text') {
                                            echo 'hidden';
                                          } ?> mt-4">
            <label for="brandingText" class="block text-gray-700 text-sm font-bold mb-2 text-left">Branding Text:</label>
            <textarea class="w-full h-32 border rounded p-2" name="brandingText"><?php echo $_POST["brandingText"] ?? ''; ?></textarea>
          </div>

          <div id="imageContainer" class="<?php if ($branding !== 'image') {
                                            echo 'hidden';
                                          } ?> mt-4">
            <label for="brandingImage" class="block text-gray-700 text-sm font-bold mb-2 text-left">Branding Image:</label>
            <input type="file" id="brandingImage" name="brandingImage" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
          </div>

          <div id="qrTagContainer" class="<?php if ($branding !== 'qrTag') {
                                            echo 'hidden';
                                          } ?> mt-4">
            <label for="qrTagText" class="block text-gray-700 text-sm font-bold mb-2 text-left">Branding QR Tag:</label>
            <textarea type="text" id="qrTagText" name="qrTagText" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo $_POST["qrTagText"] ?? ''; ?></textarea>

            <label for="qrTagSideText" class="block text-gray-700 text-sm font-bold mt-4 mb-2 text-left">Branding QR Tag Text:</label>
            <textarea type="text" id="qrTagSideText" name="qrTagSideText" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo $_POST["qrTagSideText"] ?? ''; ?></textarea>
          </div>
        </div>

        <!-- Sender text -->
        <div class="bg-white p-8 rounded-lg shadow-md">
          <h2 class="text-2xl font-semibold mb-6">Sender Text</h2>
          <div>
            <label for="senderText" class="block text-gray-700 text-sm font-bold mb-2">Sender Text:</label>
            <textarea id="senderText" name="senderText" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo $senderText ?? ''; ?></textarea>
          </div>
        </div>
      </div>

      <button type="submit" class="mt-8 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Submit
      </button>
    </form>
  </div>

  <?php $hidden = $cardKey ? '' : 'hidden'; ?>

  <!-- Modal structure -->
  <div id="myModal" class="fixed z-10 inset-0 overflow-y-auto <?php echo $hidden; ?>">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>
      <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-7xl sm:w-full sm:p-6">
        <button id="closeModal" class="absolute top-0 right-0 mr-2 mt-2 bg-white rounded-full p-1 text-gray-600 hover:text-red-600 text-xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <div class="bg-white p-8 rounded-lg shadow-md">
          <h2 class="text-2xl font-semibold mb-6">Postcard Preview</h2>

          <div class="mt-8">
            <?php if ($postcard ?? false && empty($postcard->getErrors())) : ?>
              <div class="flex justify-around">
                <div class="bg-blue-100 p-4 rounded shadow">
                  <h3 class="text-lg font-bold mb-2">Front Image</h3>
                  <img src="preview.php?side=front&card-key=<?php echo $cardKey; ?>" alt="Front Preview" class="rounded shadow" style="width: 500px" />
                </div>
                <div class="bg-blue-100 p-4 rounded shadow">
                  <h3 class="text-lg font-bold mb-2">Back Image</h3>
                  <img src="preview.php?side=back&card-key=<?php echo $cardKey; ?>" alt="Back Preview" class="rounded shadow" style="width: 500px" />
                </div>
              </div>
            <?php endif; ?>

            <?php if(!empty($warnings)): ?>
            <div class="mt-6 bg-yellow-100 p-4 rounded shadow">
              <h3 class="text-lg font-bold mb-2">Warnings</h3>
              <ul class="list-disc pl-5">
                <?php foreach ($warnings as $warning) : ?>
                  <?php if (!empty($warning)) : ?>
                    <?php foreach ($warning as $w) : ?>
                      <li class="text-yellow-600"><?php echo $w['code'] . ': ' . $w['description']; ?></li>
                    <?php endforeach; ?>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </div>
            <?php endif; ?>

            <?php if(!empty($errors)): ?>
            <div class="mt-6 bg-red-100 p-4 rounded shadow">
              <h3 class="text-lg font-bold mb-2">Errors</h3>
              <ul class="list-disc pl-5">
                <?php foreach ($errors as $error) : ?>
                  <?php if (!empty($error)) : ?>
                    <?php foreach ($error as $e) : ?>
                      <li class="text-red-600"><?php echo $e['code'] . ': ' . $e['description']; ?></li>
                    <?php endforeach; ?>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const modal = document.getElementById("myModal");
    const closeModal = document.getElementById("closeModal");

    closeModal.addEventListener("click", () => {
      modal.classList.add("hidden");
    });

    function handleBrandingChange() {
      const selectedBranding = document.querySelector('input[name="branding"]:checked').value;
      document.getElementById('textContainer').style.display = selectedBranding === 'text' ? 'block' : 'none';
      document.getElementById('imageContainer').style.display = selectedBranding === 'image' ? 'block' : 'none';
      document.getElementById('qrTagContainer').style.display = selectedBranding === 'qrTag' ? 'block' : 'none';
    }

    document.getElementById('none').addEventListener('change', handleBrandingChange);
    document.getElementById('text').addEventListener('change', handleBrandingChange);
    document.getElementById('image').addEventListener('change', handleBrandingChange);
    document.getElementById('qrTag').addEventListener('change', handleBrandingChange);
  </script>
</body>

</html>