<?php
include 'global.php';

if (!$_SESSION["token"]) {
    header("location: index.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $memoryCategory = $_POST['memoryCategory'];
    $memoryTitle = $_POST['storyTitle'];
    $memoryLocation = $_POST['memoryLocation'];
    $dateFrom = $_POST['inputstoryDate'];
    $memoryText = $_POST['storyDetails'];
    $taggedMembers = $_POST['selectedViewers'];
    $enableDayInHistory = isset($_POST['enableDayInHistory']) ? 1 : 0;
    $memoryImage = $_FILES['storyPic'];
    $imageExtension = '';
    $image = null;
    if (isset($_FILES['storyPic']) && $_FILES["storyPic"]["error"] == UPLOAD_ERR_OK) {

        $check = getimagesize($_FILES["storyPic"]["tmp_name"]);

        if ($check !== false) {
            $image = base64_encode(file_get_contents($_FILES["storyPic"]["tmp_name"]));
            $imageExtension = end((explode(".", $_FILES["storyPic"]["name"])));
            $fileType = $_FILES['storyPic']['type'];
        }
    }


    $memoryImageExtension = $_POST['memoryImageExtension'];
    $body = json_encode(
        array(
            "memoryCategory" => $memoryCategory,
            "memoryTitle" => $memoryTitle,
            "memoryLocation" => $memoryLocation,
            "dateFrom" =>  $dateFrom,
            "dateTo" =>  $dateFrom,
            "memoryText" => $memoryText,
            "memoryImage" => $image,
            "memoryImageExtension" => '.' . $imageExtension,
            "taggedMembers" => [
                $taggedMembers
            ],
            "collaboratemembers" => [
                $_POST['selectedEmails']
            ],
            "enableDayInHistory" => $enableDayInHistory,
            "parent1" => $_POST['parent1'],
            "parent2" => $_POST['parent2'],
            "kindofgrad" => $_POST['kindofgrad'],
            "nameofschool" => $_POST['nameofschool'],
            "partnersname" => $_POST['partnersname'],
            "companyname" => $_POST['companyname']
        )
    );

    //     // echo $body;
    $response = makePostAPIcall('memoryAdd', $body, $_SESSION['token']);
    $responseData = json_decode($response, true);

    if (isset($responseData['success']) && $responseData['success'] === "true") {
        header("Location: timeline.php");
    } else {
        header("Location: timeline.php?error");
    }
    // echo $response;
}

//    {
//       "companyname": null,
//       "datefrom": "2001-01-01",
//       "dateto": "2001-01-01",
//       "enabledayinhistory": 0,
//       "kindofgrad": null,
//       "memoryImageExtension": ".",
//       "memoryTitle": "Started school",
//       "memorycategory": "Started School",
//       "memorycreatedate": "2025-02-12",
//       "memoryid": 37,
//       "memoryimage": null,
//       "memorylocation": "Riviera",
//       "memorytext": "Sterted school do not remember much but I'm sure I was pretty pissed",
//       "nameofschool": "Elementary",
//       "parent1": null,
//       "parent2": null,
//       "partnersname": null
//     },
