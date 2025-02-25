<?php

include 'global.php';
if (!$_SESSION["token"]) {
    header("location: index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];

    if ($type == 'accept') {
        // Here we're saving the accepts
        $recordId = $_POST['invite'];
        $body =
            json_encode(
                array(
                    "recordid" => $recordId,
                )
            );
        $acceptResponse = makePostAPIcall('acceptInvite', $body, $_SESSION["token"]);
    } else {
        $recordid = $_POST['recordid'];
        $personname = $_POST['inputGuestFirstName'] . ' ' . $_POST['inputGuestLastName'];
        $emailaddress = $_POST['inputEmail'];
        $familytitle = getFamilyOptionKey($_POST['inputGuestRelation']);
        $connectiontype = '1032';
        $message = $_POST['message'];
        $collaborator = isset($_POST['iscollaborator']) ? true : false;
        $guestadmin = isset($_POST['isguestadmin']) ? true : false;


        $body =
            json_encode(
                array(
                    "recordid" => $recordid,
                    "personname" => $personname,
                    "emailaddress" => $emailaddress,
                    "familytitle" => $familytitle,
                    "viewabilityright" => $connectiontype,
                    "connectiontype" => $connectiontype,
                    "message" => $message,
                    "username" => $personname,
                    "isguestadmin" => $guestadmin,
                    "iscollaborator" => $collaborator,
                )
            );
        if (trim($recordid) != '') {
            // This is an update
            $updateResponse = makePostAPIcall('editConnection', $body, $_SESSION["token"]);
        } else {
            // It's creating a new connection.
            $addResponse = makePostAPIcall('addConnection', $body, $_SESSION["token"]);
        }
    }
}

if (!isset($_SESSION['connections']) || empty($_SESSION['connections'])) {
    $connectionResponse = makeGetAPICall('getConnections', $_SESSION["token"]);
    $connections = json_decode($connectionResponse, true);
    $_SESSION['connections'] = array();
    if (is_array($connections)) {
        foreach ($connections as $connection) {
            $_SESSION['connections'][$connection['userId']] = $connection;
        }
    }
    $connections = $_SESSION['connections'];
} else {
    $connections = $_SESSION['connections'];
}
$acceptedConnections = [];
$notAcceptedConnections = [];



foreach ($connections as &$connection) {

    if (!isset($connection['emailAddress'])) {
        break;
    }
    $emailaddress = $connection['emailAddress'];


    $getProfileBody = json_encode(
        array(
            "emailaddress" => $emailaddress
        )
    );

    $profileInfo = makePostAPICall('getProfile', $getProfileBody, $_SESSION["token"]);


    $profileResponse = json_decode($profileInfo, true);


    if (isset($profileResponse[0]) && !isset($profileResponse['error'])) {

        $connection['PersonName'] = $profileResponse[0]['fullname'] ?? $profileResponse[0]['firstName'] . ' ' . $profileResponse[0]['lastName'];
        $connection['documentdata'] = $profileResponse[0]['documentdata'];
        $connection['docType'] = $profileResponse[0]['docType'];
        $connection['image'] =
            'data:image/' . $connection['docType'] . ';base64,' . $connection['documentdata'];
    } else {
        $connection['image'] = 'https://dummyimage.com/600x400/ebebeb/000000&text=NotRegistered';
    }

    if ($connection['InviteStatus'] == 'Accepted') {
        $acceptedConnections[] = $connection;
    } else {
        $notAcceptedConnections[] = $connection;
    }
}


function generateHtml($connections)
{
    $html = '';

    foreach ($connections as $index => $connection) {
        $connection['connectionDate'] = preg_replace('/ \d{2}:\d{2}:\d{2} GMT$/', '', $connection['connectionDate']);
        $html .= '
        <div id="connect' . ($index + 1) . '" class="p-3 connection-card" style="height:130px !important;">
            <div class="row align-items-center">
                <div class="col-3">
                    <img src="' . htmlspecialchars($connection['image']) . '" alt="Profile" class="rounded-circle" width="80" height="80">
                </div>
                <div class="col-9">
                    <h5 class="mb-0">' . htmlspecialchars($connection['PersonName']) . '</h5>
                    <small class="text-white fs-6">Joined: ' . htmlspecialchars($connection['connectionDate']) . '</small>
                    <div class="mt-2 d-flex align-items-center justify-content-between" style="width:100% !important;">
                        <span class="btn btn-transparent border border-white rounded-pill text-white fw-bold align-items-center justify-content-center" style="width: 150px;height: 40px;display: flex;">
                            <img src="assets/images/icons/family.png" alt="Role Icon" class="rounded-circle me-2" style="width: 24px; height: 24px;">
                            ' . htmlspecialchars($connection['Relationship']) . '
                        </span>
                        <span class="btn btn-transparent border border-white rounded-pill text-white fw-bold align-items-center justify-content-center" style="width: 150px;height: 40px;display: flex;">
                             <div class="dropdown-container">
                                 <select class="form-select bg-transparent custom-dropdown border-0 text-white fw-bold" style="width: 100%;">
                                     <option value="0" disabled selected>Roles</option>
                                     <option value="1">Guest Administrator</option>
                                     <option value="2">Collaborator</option>
                                     <option value="3">Viewer</option>
                                 </select>
                             </div>
                         </span>
                        <i class="bi bi-pencil text-white" 
                           style="cursor: pointer;" 
                           onclick="showStepInvite(' . $connection['RecordId'] . ')"></i>
                    </div>
                </div>
            </div>
        </div>';
    }

    return $html;
}


$acceptedHtml = generateHtml($acceptedConnections);
// $notAcceptedHtml = generateHtml($notAcceptedConnections);


$invites_responses = makeGetAPICall('searchInvites', $_SESSION["token"]);
$invites = json_decode($invites_responses, true);

foreach ($invites as &$invite) {
    if (!isset($invite['emailAddress'])) {
        break;
    }
    $emailaddress = $invite['emailAddress'] ?? null;

    if ($emailaddress) {
        $getProfileBody = json_encode(array(
            "emailaddress" => $emailaddress
        ));

        $profileInfo = makePostAPICall('getProfile', $getProfileBody, $_SESSION["token"]);
        $profileResponse = json_decode($profileInfo, true);


        if (isset($profileResponse[0])) {
            $invite['PersonName'] = $profileResponse[0]['fullname'] ?? $profileResponse[0]['firstName'] . ' ' . $profileResponse[0]['lastName'];
            $invite['documentdata'] = $profileResponse[0]['documentdata'] ?? null;
            $invite['docType'] = $profileResponse[0]['docType'] ?? null;
            $invite['image'] = isset($invite['docType'], $invite['documentdata']) ? 'data:image/' . $invite['docType'] . ';base64,' . $invite['documentdata'] : null;
        }
    }
}

function generateInvite($invites)
{
    $html = '';

    foreach ($invites as $index => $invite) {
        if (!isset($invite['recordId'])) {
            break;
        }

        $invite['inviteDate'] = preg_replace('/ \d{2}:\d{2}:\d{2} GMT$/', '', $invite['inviteDate']);
        $html .= '
        <form  action="connections.php" method="POST" enctype="multipart/form-data">
        <div id="connect' . ($index + 1) . '" class="p-3 connection-card" style="height:130px !important;">
        <input name="type" value="accept" hidden>
        <input name="invite" value="' . htmlspecialchars($invite['recordId']) . '" hidden>
            <div class="row align-items-center">
                <div class="col-3">
                    <img src="' . htmlspecialchars($invite['image']) . '" alt="Profile" class="rounded-circle" width="80" height="80">
                </div>
                <div class="col-9">
                    <h5 class="mb-0">' . htmlspecialchars($invite['PersonName']) . '</h5>
                    <small class="text-white fs-6">Received: ' . htmlspecialchars($invite['inviteDate']) . '</small>
                    <div class="mt-2 d-flex align-items-center justify-content-between" style="width:80% !important;">
                        <button class="btn btn-transparent  text-white"> Reject</button>
                         <button class="btn btn-white text-black txt-hover-white border-white"> Accept</button>
                    </div>
                </div>
            </div>
        </div> </form>';
    }

    return $html;
}

$inviteHtml = generateInvite($invites);

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
    <!--plugins-->
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link href="assets/plugins/datetimepicker/css/classic.css" rel="stylesheet" />
    <link href="assets/plugins/datetimepicker/css/classic.time.css" rel="stylesheet" />
    <link href="assets/plugins/datetimepicker/css/classic.date.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css">
    <!-- loader-->
    <!-- <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script> -->
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">


    <title>Dignitrees - Family App</title>
</head>

<body class="bg-theme bg-<?php echo $_SESSION['background']; ?>">
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <?php include 'nav/sidebar.php'; ?>
        <!--end sidebar wrapper -->
        <!--start header -->
        <?php include 'nav/header.php'; ?>
        <!--end header -->
        <!--start page wrapper -->


        <div class="page-wrapper">
            <div class="page-content">
                <!-- totallyHide -->
                <div class="main-body totallyHide" id="builderForms">
                    <div class="timeline-form-container">
                        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                            <div class="card mb-0 cardClear w-100" style="height:100vH !important;">
                                <div class="card-body">
                                    <div class="form-body">
                                        <!-- totallyHide -->
                                        <form class="row g-3 builderForm" id="birthday" action="connections.php"
                                            method="POST" enctype="multipart/form-data">
                                            <div>
                                                <input type="text" name="recordid" value="" hidden>
                                                <div class="text-start col-12" style="padding:0 10px;">
                                                    <h6 class="fw-normal fs-6 connect-title">Connect</h6>
                                                    <span class="mb-2 text-white w-20 connect-sub"
                                                        id="name-label">Invite the following to connect on the
                                                        Dignitrees Family App and view your memories of
                                                        <?php echo $name; ?>.</span>

                                                </div>
                                                <div class="col-12 mb-2 tabgroup">
                                                    <div class="tabsect birth-remember">
                                                        <div class="col-12 mb-4 mt-4">
                                                            <div class="spacing-10 text-white connect-sub"
                                                                id="shortDescription">Name <sup>*</sup></div>
                                                            <input type="text"
                                                                class="form-control border-white spacing-10"
                                                                id="firstname" name="inputGuestFirstName"
                                                                placeholder="First Name" required>
                                                            <input type="text"
                                                                class="form-control border-white spacing-10"
                                                                id="lastname" name="inputGuestLastName"
                                                                placeholder="Last Name" required>
                                                        </div>
                                                        <div class="col-12 mb-4">
                                                            <div class="spacing-10 text-white connect-sub"
                                                                id="shortDescription">Email <sup>*</sup></div>
                                                            <input type="email" class="form-control border-white mb-2"
                                                                id="email" name="inputEmail" placeholder="name@domain"
                                                                required>
                                                        </div>
                                                        <div class="col-12 mb-4">
                                                            <div class="spacing-10 text-white connect-sub">Relationship
                                                                <sup>*</sup>
                                                            </div>
                                                            <select class="form-select border-white mb-2 text-white"
                                                                id="connectionsOptions" name="inputGuestRelation"
                                                                required>
                                                                <option value="" disabled selected>Choose a relationship
                                                                </option>
                                                                <option selected value="friend">Friend</option>
                                                                <option value="sibling">Sibling</option>
                                                                <option value="partner">Partner</option>
                                                                <option value="other">Other</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <div class="spacing-10 text-white connect-sub"
                                                                id="shortDescription">Share some details.</div>
                                                            <textarea class="form-control border-white mb-2 text-white"
                                                                id="jobDetails" name="message" placeholder="Optional"
                                                                rows="3" required></textarea>
                                                            <div class="text-end">
                                                                <span id="jobDetailsCount" class="text-white">0 of
                                                                    500</span>
                                                            </div>
                                                        </div>

                                                        <div class="access-levels mb-3">
                                                            <div
                                                                class="form-check form-swtch d-flex align-items-center mb-2">
                                                                <input class="form-check-input border-white checkbox"
                                                                    type="checkbox" name="iscollaborator"
                                                                    id="collabswitch">
                                                                <label class="form-check-label checkbox-label"
                                                                    for="collabswitch">Collaborator</label>
                                                            </div>
                                                            <span class="connect-sub">Note: Collaborators can suggest
                                                                memories for the timeline.</span>
                                                        </div>

                                                        <div class="access-levels">
                                                            <div
                                                                class="form-check form-swtch d-flex align-items-center">
                                                                <input class="form-check-input border-white checkbox"
                                                                    type="checkbox" name="isguestadmin"
                                                                    id="collabswitch">
                                                                <label class="form-check-label checkbox-label"
                                                                    for="collabswitch">Guest administrator</label>
                                                            </div>
                                                            <span class="connect-sub">Warning: Guest administrators have
                                                                the <br>ability to make changes to any data or setting
                                                                in this application.</span>
                                                        </div>

                                                    </div>


                                                    <div class="col-12">
                                                        <p class="mb-0 text-danger" id="passwordError">
                                                            <?php echo $error; ?></p>
                                                        <p class="mb-0 text-success" id="txtMessage">
                                                            <?php echo $message; ?></p>
                                                    </div>
                                                </div>

                                                <div class="row mt-1 mx-2 my-2 position-relative">
                                                    <span class="btn btn-transparent text-start text-white col-9"
                                                        onclick="saveAndClose()">Close</span>
                                                    <button
                                                        class="btn btn-white text-center text-black txt-hover-white col-3 btn-hover-white slim-send"
                                                        onmouseover="changeIcon(this, 'assets/images/icons/send-white.svg')"
                                                        onmouseout="changeIcon(this, 'assets/images/icons/send.svg')">Send
                                                        <img src="assets/images/icons/send.svg" alt="Back"
                                                            style="margin-left:5px !important; "></button>
                                                </div>

                                            </div>
                                        </form>

                                        <form class="row g-3 totallyHide builderForm" id="graduation">
                                            <div>
                                                <div class="row mt-1 mx-2 my-2">
                                                    <div class="text-start mb-2 col-9">
                                                        <h6 class="fw-normal fs-6">Memory(2 of 3)</h6>
                                                    </div>
                                                    <div class="text-end mb-2 col-3">
                                                        <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
                                                    </div>
                                                </div>
                                                <div class="row mt-1 mx-2 my-2 memtabs">
                                                    <div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
                                                        onclick="activateTab(this)" tag="graduation-remember">
                                                        <h6 class="fw-normal fs-6">Remember</h6>
                                                    </div>
                                                    <div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="graduation-share">
                                                        <h6 class="fw-normal fs-6">Share</h6>
                                                    </div>
                                                    <div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="graduation-collab">
                                                        <h6 class="fw-normal fs-6">Collaborate</h6>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2 tabgroup">
                                                    <div class="tabsect graduation-remember"><span
                                                            class="mb-2 text-white " id="name-label">Share your
                                                            graduation story</span>
                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white" id="shortDescription">Give it
                                                                a title.</span>
                                                            <input type="text" class="form-control border-white mb-2"
                                                                id="graduationTitle" name="graduationTitle"
                                                                placeholder="Title" value="Graduation">
                                                            <div class="text-end">
                                                                <span id="graduationTitleCount" class="text-white">0 of
                                                                    100</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white">What kind of graduation was
                                                                it?</span>
                                                            <select
                                                                class="form-select border-white mb-2 text-white bg-dark"
                                                                id="inputGraduationKind" name="inputGraduationKind"
                                                                required>
                                                                <option value="" disabled selected>Select one</option>
                                                                <option value="sibhighschoolling">High School</option>
                                                                <option value="college">College/ University</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white" id="birth-label">When did it
                                                                take place?</span>
                                                            <input type="date" class="form-control border-white mb-2"
                                                                id="inputGraduationDate" name="inputGraduationDate"
                                                                placeholder="Select a date" required>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input border-white"
                                                                    type="checkbox" id="graduationSwitch">
                                                                <label class="form-check-label"
                                                                    for="graduationSwitch">This Day in History adds
                                                                    facts about current events.</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white" id="shortDescription">What was
                                                                the name of the school?</span>
                                                            <input type="text" class="form-control border-white mb-2"
                                                                id="schoolName" name="schoolName"
                                                                placeholder="School Name">
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white" id="shortDescription">Where
                                                                was it located?</span>
                                                            <input type="text" class="form-control border-white mb-2"
                                                                id="schoolLocation" name="schoolLocation"
                                                                placeholder="School Location">
                                                        </div>


                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white">Add a tag to associate this
                                                                memory with others on your timeline.</span>
                                                            <input type="text" class="form-control border-white mb-2"
                                                                id="graduationTag" name="graduationTag"
                                                                placeholder="Enter text and press return">
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white" id="shortDescription">Share
                                                                some details.</span>
                                                            <textarea class="form-control border-white mb-2 text-white"
                                                                id="graduationDetails" name="graduationDetails"
                                                                placeholder="Where was the ceremony held? Who were your best friends at the time? Did you have any favorite sports or other extra-curricular activities?"
                                                                rows="5" required></textarea>
                                                            <div class="text-end">
                                                                <span id="graduationDetailsCount" class="text-white">0
                                                                    of 500</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white"> Add media to your
                                                                memory.</span>
                                                            <div class="col-12">
                                                                <input id="graduationPic"
                                                                    class="form-control border-white"
                                                                    name="graduationPic" type="file"
                                                                    accept=".jpg, .jpeg, .png" name=""
                                                                    onchange="previewImage(this, 'previewgraduationPic', 'graduationfileName');">
                                                                <div
                                                                    class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                                                                    <img id="previewgraduationPic" src=""
                                                                        alt="Profile Preview">
                                                                    <span id="graduationfileName"
                                                                        class="text-white"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 hideOnMobile" style="cursor:pointer;">
                                                            <div class="text-center text-white"><i
                                                                    class="bi bi-camera-video text-white mx-2"></i>Record
                                                                a video message.</div>
                                                        </div>
                                                    </div>
                                                    <div class="tabsect graduation-share totallyHide">
                                                        <span class="mb-2 text-white fs-6" id="name-label">Choose the
                                                            audience for this memory. Who can see it?</span>
                                                        <div class="col-12 mb-3 mt-3">
                                                            <label class="cbContainer">
                                                                <input type="radio" name="graduation-audience"
                                                                    value="family" checked>
                                                                <span class="checkmark"></span>
                                                                <div class="text">
                                                                    <div class="title">Only family (default)</div>
                                                                </div>
                                                            </label>

                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <label class="cbContainer">
                                                                <input type="radio" name="graduation-audience"
                                                                    value="connections">
                                                                <span class="checkmark"></span>
                                                                <div class="text">
                                                                    <div class="title">All connections</div>
                                                                </div>
                                                            </label>

                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <label class="cbContainer">
                                                                <input type="radio" name="graduation-audience"
                                                                    value="anyone">
                                                                <span class="checkmark"></span>
                                                                <div class="text">
                                                                    <div class="title">Anyone (public)</div>
                                                                </div>
                                                            </label>

                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <label class="cbContainer">
                                                                <input type="radio" name="graduation-audience"
                                                                    value="custom">
                                                                <span class="checkmark"></span>
                                                                <div class="text">
                                                                    <div class="title">Custom</div>
                                                                </div>
                                                            </label>

                                                        </div>
                                                    </div>
                                                    <div class="tabsect graduation-collab totallyHide">
                                                        <span class="mb-2 text-white fs-6" id="name-label">Invite
                                                            existing connections to contribute to this memory.</span>
                                                        <div class="row mt-1 mx-2 my-2 align-items-center">
                                                            <div class="text-start col-1 d-flex justify-content-center"
                                                                style="cursor: pointer;">
                                                                <i class="bi bi-search text-white fs-5"></i>
                                                            </div>
                                                            <div class="text-end col-11">
                                                                <input type="text" class="form-control border-white"
                                                                    id="searchParam" name="searchParam"
                                                                    placeholder="Search" required>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <div
                                                                    class="collab-name d-flex justify-content-center gap-2 px-3 py-2">
                                                                    <i class="bi bi-person text-white text-start"
                                                                        style="cursor: pointer;"></i>
                                                                    <div class="text-center">Name 1</div>
                                                                    <i class="bi bi-x text-white text-end"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <form class="row g-3 w-100" action="connections.php"
                                                            method="POST" enctype="multipart/form-data">

                                                            <div class="scrollable-div col-12 w-100 mt-2">
                                                                <div class="col-12 mb-2 w-100">
                                                                    <div
                                                                        class="mb-3 d-flex align-items-center justify-content-between">
                                                                        <h6 class="mb-0">Invite a new connection to
                                                                            contribute.</h6>
                                                                    </div>


                                                                    <div class="col-12 mb-2">
                                                                        <span class="mb-2 text-white">Name?
                                                                            <sup>*</sup></span>
                                                                        <input type="text"
                                                                            class="form-control border-white mb-2"
                                                                            id="graduationinputCollabFirstName"
                                                                            name="graduationinputCollabFirstName"
                                                                            placeholder="First name" required>
                                                                        <input type="text"
                                                                            class="form-control border-white mb-2"
                                                                            id="graduationinputCollabLastName"
                                                                            name="graduationinputCollabLastName"
                                                                            placeholder="Last name" required>
                                                                    </div>

                                                                    <div class="col-12 mb-2">
                                                                        <span class="mb-2 text-white"><i>Email
                                                                                <sup>*</sup></i></span>
                                                                        <input type="email"
                                                                            class="form-control border-white mb-2"
                                                                            id="graduationinputCollabEmail"
                                                                            name="inputEmail"
                                                                            placeholder="name@email.com" required>
                                                                    </div>

                                                                    <div class="col-12">
                                                                        <span class="mb-2 text-white"><i>Relationship
                                                                                <sup>*</sup></i></span>
                                                                        <select
                                                                            class="form-select border-white mb-2 text-white bg-dark"
                                                                            id="graduationinputCollabRelation"
                                                                            name="graduationinputCollabRelation"
                                                                            required>
                                                                            <option value="" disabled selected>Choose a
                                                                                relationship</option>
                                                                            <option selected value="friend">Friend
                                                                            </option>
                                                                            <option value="sibling">Sibling</option>
                                                                            <option value="partner">Partner</option>
                                                                            <option value="family">Family</option>
                                                                            <option value="other">Other</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-12 mb-3">
                                                                        <span class="mb-2 text-white"
                                                                            id="shortDescription">Share some
                                                                            details.</span>
                                                                        <textarea
                                                                            class="form-control border-white mb-2 text-white"
                                                                            id="graduationCollabDetails"
                                                                            name="graduationCollabDetails"
                                                                            placeholder="Optional" rows="3"></textarea>
                                                                        <div class="text-end">
                                                                            <span id="graduationCollabDetailsCount"
                                                                                class="text-white">0 of 500</span>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?>
                                                    </p>
                                                    <p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?>
                                                    </p>
                                                </div>

                                                <div class="row mt-1 mx-2 my-2 position-relative">
                                                    <div class="text-center mb-2 col-12">
                                                        <button type="button" class="btn btn-white"
                                                            onclick="toggleDropdown('graduation')">
                                                            Save
                                                            <i class="bi bi-caret-down"></i>
                                                        </button>
                                                        <div id="saveDropdown-graduation"
                                                            class=" dropdown-menu-tm position-absolute bg-white shadow"
                                                            style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndContinue('graduation')">Save &
                                                                Continue</button>
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndClose()">Save & Close</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>

                                        <form class="row g-3 totallyHide builderForm" id="wedding">
                                            <div>
                                                <div class="row mt-1 mx-2 my-2">
                                                    <div class="text-start mb-2 col-9">
                                                        <h6 class="fw-normal fs-6">Memory</h6>
                                                    </div>
                                                    <div class="text-end mb-2 col-3">
                                                        <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
                                                    </div>
                                                </div>
                                                <div class="row mt-1 mx-2 my-2 memtabs">
                                                    <div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
                                                        onclick="activateTab(this)" tag="wedding-remember">
                                                        <h6 class="fw-normal fs-6">Remember</h6>
                                                    </div>
                                                    <div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="wedding-share">
                                                        <h6 class="fw-normal fs-6">Share</h6>
                                                    </div>
                                                    <div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="wedding-collab">
                                                        <h6 class="fw-normal fs-6">Collaborate</h6>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2 tabgroup">
                                                    <div class="tabsect wedding-remember">

                                                        <span class="mb-2 text-white " id="name-label">Share your
                                                            wedding story</span>
                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white" id="shortDescription">Give it
                                                                a title.</span>
                                                            <input type="text" class="form-control border-white mb-2"
                                                                id="weddingTitle" name="weddingTitle"
                                                                placeholder="Title" value="Wedding">
                                                            <div class="text-end">
                                                                <span id="weddingTitleCount" class="text-white">0 of
                                                                    100</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white" id="birth-label">When did it
                                                                take place?</span>
                                                            <input type="date" class="form-control border-white mb-2"
                                                                id="inputweddingDate" name="inputweddingDate"
                                                                placeholder="Select a date" required>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input border-white"
                                                                    type="checkbox" id="weddingSwitch">
                                                                <label class="form-check-label" for="weddingSwitch">This
                                                                    Day in History adds facts about current
                                                                    events.</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white" id="shortDescription">Where
                                                                did you get married?</span>
                                                            <input type="text" class="form-control border-white mb-2"
                                                                id="schoolName" name="schoolName"
                                                                placeholder="Location of wedding">
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white">Add a tag to associate this
                                                                memory with others on your timeline.</span>
                                                            <input type="text" class="form-control border-white mb-2"
                                                                id="weddingTag" name="weddingTag"
                                                                placeholder="Enter text and press return">
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white" id="shortDescription">Share
                                                                some details.</span>
                                                            <textarea class="form-control border-white mb-2 text-white"
                                                                id="weddingDetails" name="weddingDetails"
                                                                placeholder="Who was in your wedding party? What made the day special?"
                                                                rows="5" required></textarea>
                                                            <div class="text-end">
                                                                <span id="weddingDetailsCount" class="text-white">0 of
                                                                    500</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white"> Add media to your
                                                                memory.</span>
                                                            <div class="col-12">
                                                                <input id="weddingPic" class="form-control border-white"
                                                                    name="weddingPic" type="file"
                                                                    accept=".jpg, .jpeg, .png" name=""
                                                                    onchange="previewImage(this, 'previewweddingPic', 'weddingfileName');">
                                                                <div
                                                                    class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                                                                    <img id="previewweddingPic" src=""
                                                                        alt="Profile Preview">
                                                                    <span id="weddingfileName"
                                                                        class="text-white"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 hideOnMobile" style="cursor:pointer;">
                                                            <div class="text-center text-white"><i
                                                                    class="bi bi-camera-video text-white mx-2"></i>Record
                                                                a video message.</div>
                                                        </div>
                                                    </div>
                                                    <div class="tabsect wedding-share totallyHide">
                                                        <span class="mb-2 text-white fs-6" id="name-label">Choose the
                                                            audience for this memory. Who can see it?</span>
                                                        <div class="col-12 mb-3 mt-3">
                                                            <label class="cbContainer">
                                                                <input type="radio" name="wedding-audience"
                                                                    value="family" checked>
                                                                <span class="checkmark"></span>
                                                                <div class="text">
                                                                    <div class="title">Only family (default)</div>
                                                                </div>
                                                            </label>

                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <label class="cbContainer">
                                                                <input type="radio" name="wedding-audience"
                                                                    value="connections">
                                                                <span class="checkmark"></span>
                                                                <div class="text">
                                                                    <div class="title">All connections</div>
                                                                </div>
                                                            </label>

                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <label class="cbContainer">
                                                                <input type="radio" name="wedding-audience"
                                                                    value="anyone">
                                                                <span class="checkmark"></span>
                                                                <div class="text">
                                                                    <div class="title">Anyone (public)</div>
                                                                </div>
                                                            </label>

                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <label class="cbContainer">
                                                                <input type="radio" name="wedding-audience"
                                                                    value="custom">
                                                                <span class="checkmark"></span>
                                                                <div class="text">
                                                                    <div class="title">Custom</div>
                                                                </div>
                                                            </label>

                                                        </div>
                                                    </div>
                                                    <div class="tabsect wedding-collab totallyHide">
                                                        <span class="mb-2 text-white fs-6" id="name-label">Invite
                                                            existing connections to contribute to this memory.</span>
                                                        <div class="row mt-1 mx-2 my-2 align-items-center">
                                                            <div class="text-start col-1 d-flex justify-content-center"
                                                                style="cursor: pointer;">
                                                                <i class="bi bi-search text-white fs-5"></i>
                                                            </div>
                                                            <div class="text-end col-11">
                                                                <input type="text" class="form-control border-white"
                                                                    id="searchParam" name="searchParam"
                                                                    placeholder="Search" required>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <div
                                                                    class="collab-name d-flex justify-content-center gap-2 px-3 py-2">
                                                                    <i class="bi bi-person text-white text-start"
                                                                        style="cursor: pointer;"></i>
                                                                    <div class="text-center">Name 1</div>
                                                                    <i class="bi bi-x text-white text-end"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <form class="row g-3 w-100">
                                                            <div class="scrollable-div col-12 w-100 mt-2">
                                                                <div class="col-12 mb-2 w-100">
                                                                    <div
                                                                        class="mb-3 d-flex align-items-center justify-content-between">
                                                                        <h6 class="mb-0">Invite a new connection to
                                                                            contribute.</h6>
                                                                    </div>


                                                                    <div class="col-12 mb-2">
                                                                        <span class="mb-2 text-white">Name?
                                                                            <sup>*</sup></span>
                                                                        <input type="text"
                                                                            class="form-control border-white mb-2"
                                                                            id="weddinginputCollabFirstName"
                                                                            name="weddinginputCollabFirstName"
                                                                            placeholder="First name" required>
                                                                        <input type="text"
                                                                            class="form-control border-white mb-2"
                                                                            id="weddinginputCollabLastName"
                                                                            name="weddinginputCollabLastName"
                                                                            placeholder="Last name" required>
                                                                    </div>

                                                                    <div class="col-12 mb-2">
                                                                        <span class="mb-2 text-white"><i>Email
                                                                                <sup>*</sup></i></span>
                                                                        <input type="email"
                                                                            class="form-control border-white mb-2"
                                                                            id="weddinginputCollabEmail"
                                                                            name="inputEmail"
                                                                            placeholder="name@email.com" required>
                                                                    </div>

                                                                    <div class="col-12">
                                                                        <span class="mb-2 text-white"><i>Relationship
                                                                                <sup>*</sup></i></span>
                                                                        <select
                                                                            class="form-select border-white mb-2 text-white bg-dark"
                                                                            id="weddinginputCollabRelation"
                                                                            name="weddinginputCollabRelation" required>
                                                                            <option value="" disabled selected>Choose a
                                                                                relationship</option>
                                                                            <option selected value="friend">Friend
                                                                            </option>
                                                                            <option value="sibling">Sibling</option>
                                                                            <option value="partner">Partner</option>
                                                                            <option value="other">Other</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-12 mb-3">
                                                                        <span class="mb-2 text-white"
                                                                            id="shortDescription">Share some
                                                                            details.</span>
                                                                        <textarea
                                                                            class="form-control border-white mb-2 text-white"
                                                                            id="weddingCollabDetails"
                                                                            name="weddingCollabDetails"
                                                                            placeholder="Optional" rows="3"></textarea>
                                                                        <div class="text-end">
                                                                            <span id="weddingCollabDetailsCount"
                                                                                class="text-white">0 of 500</span>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?>
                                                    </p>
                                                    <p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?>
                                                    </p>
                                                </div>

                                                <div class="row mt-1 mx-2 my-2 position-relative">
                                                    <div class="text-center mb-2 col-12">
                                                        <button type="button" class="btn btn-white"
                                                            onclick="toggleDropdown('wedding')">
                                                            Save
                                                            <i class="bi bi-caret-down"></i>
                                                        </button>
                                                        <div id="saveDropdown-wedding"
                                                            class=" dropdown-menu-tm position-absolute bg-white shadow"
                                                            style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndContinue('wedding')">Save &
                                                                Continue</button>
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndClose()">Save & Close</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>

                                        <form class="row g-3 totallyHide  builderForm" id="schools">
                                            <div>
                                                <div class="row mt-1 mx-2 my-2">
                                                    <div class="text-start mb-2 col-9">
                                                        <h6 class="fw-normal fs-6">Memory</h6>
                                                    </div>
                                                    <div class="text-end mb-2 col-3">
                                                        <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
                                                    </div>
                                                </div>
                                                <div class="row mt-1 mx-2 my-2 memtabs">
                                                    <div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
                                                        onclick="activateTab(this)" tag="birth-remember">
                                                        <h6 class="fw-normal fs-6">Remember</h6>
                                                    </div>
                                                    <div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-share">
                                                        <h6 class="fw-normal fs-6">Share</h6>
                                                    </div>
                                                    <div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-collab">
                                                        <h6 class="fw-normal fs-6">Collaborate</h6>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2 tabgroup">
                                                    <span class="mb-2 text-white " id="name-label">Share a story about
                                                        starting school.</span>
                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Give it a
                                                            title.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="schoolsTitle" name="schoolsTitle" placeholder="Title"
                                                            value="schools">
                                                        <div class="text-end">
                                                            <span id="schoolsTitleCount" class="text-white">0 of
                                                                100</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">What was the
                                                            name of the school?</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="schoolsName" name="schoolsName"
                                                            placeholder="Enter name">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Where was
                                                            the school located?</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="schoolsLocation" name="schoolsLocation"
                                                            placeholder="Enter location">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="birth-label">When did you
                                                            begin attendance?</span>
                                                        <input type="date" class="form-control border-white mb-2"
                                                            id="inputschoolsDate" name="inputschoolsDate"
                                                            placeholder="Select a date" required>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input border-white" type="checkbox"
                                                                id="schoolsSwitch">
                                                            <label class="form-check-label" for="schoolsSwitch">This Day
                                                                in History adds facts about current events.</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white">Add a tag to associate this memory
                                                            with others on your timeline.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="schoolsTag" name="schoolsTag"
                                                            placeholder="Enter text and press return">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Share some
                                                            details.</span>
                                                        <textarea class="form-control border-white mb-2 text-white"
                                                            id="schoolsDetails" name="schoolsDetails"
                                                            placeholder="Who was your favorite teacher? Did you make any good friends there? What subjects and activities did you most enjoy?"
                                                            rows="5" required></textarea>
                                                        <div class="text-end">
                                                            <span id="schoolsDetailsCount" class="text-white">0 of
                                                                500</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white"> Add media to your memory.</span>
                                                        <div class="col-12">
                                                            <input id="schoolsPic" class="form-control border-white"
                                                                name="schoolsPic" type="file" accept=".jpg, .jpeg, .png"
                                                                name=""
                                                                onchange="previewImage(this, 'previewschoolsPic', 'schoolsfileName');">
                                                            <div
                                                                class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                                                                <img id="previewschoolsPic" src=""
                                                                    alt="Profile Preview">
                                                                <span id="schoolsfileName" class="text-white"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 hideOnMobile" style="cursor:pointer;">
                                                        <div class="text-center text-white"><i
                                                                class="bi bi-camera-video text-white mx-2"></i>Record a
                                                            video message.</div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?>
                                                    </p>
                                                    <p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?>
                                                    </p>
                                                </div>

                                                <div class="row mt-1 mx-2 my-2 position-relative">
                                                    <div class="text-center mb-2 col-12">
                                                        <button type="button" class="btn btn-white"
                                                            onclick="toggleDropdown('schools')">
                                                            Save
                                                            <i class="bi bi-caret-down"></i>
                                                        </button>
                                                        <div id="saveDropdown-schools"
                                                            class=" dropdown-menu-tm position-absolute bg-white shadow"
                                                            style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndContinue('schools')">Save &
                                                                Continue</button>
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndClose()">Save & Close</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                        <form class="row g-3 totallyHide builderForm" id="friendship">
                                            <div>
                                                <div class="row mt-1 mx-2 my-2">
                                                    <div class="text-start mb-2 col-9">
                                                        <h6 class="fw-normal fs-6">Memory</h6>
                                                    </div>
                                                    <div class="text-end mb-2 col-3">
                                                        <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
                                                    </div>
                                                </div>
                                                <div class="row mt-1 mx-2 my-2 memtabs">
                                                    <div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
                                                        onclick="activateTab(this)" tag="birth-remember">
                                                        <h6 class="fw-normal fs-6">Remember</h6>
                                                    </div>
                                                    <div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-share">
                                                        <h6 class="fw-normal fs-6">Share</h6>
                                                    </div>
                                                    <div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-collab">
                                                        <h6 class="fw-normal fs-6">Collaborate</h6>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2 tabgroup">

                                                    <span class="mb-2 text-white " id="name-label">Share a story about
                                                        starting school.</span>
                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Give it a
                                                            title.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="friendshipTitle" name="friendshipTitle"
                                                            placeholder="Title" value="Friendship">
                                                        <div class="text-end">
                                                            <span id="friendshipTitleCount" class="text-white">0 of
                                                                100</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">What was the
                                                            name of your friend?</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="friendshipName" name="friendshipName"
                                                            placeholder="Enter name">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Where did
                                                            you meet?</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="friendshipLocation" name="friendshipLocation"
                                                            placeholder="Enter location">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="birth-label">When did you
                                                            meet?</span>
                                                        <input type="date" class="form-control border-white mb-2"
                                                            id="inputfriendshipDate" name="inputfriendshipDate"
                                                            placeholder="Select a date" required>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input border-white" type="checkbox"
                                                                id="friendshipSwitch">
                                                            <label class="form-check-label" for="friendshipSwitch">This
                                                                Day in History adds facts about current
                                                                events.</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white">Add a tag to associate this memory
                                                            with others on your timeline.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="friendshipTag" name="friendshipTag"
                                                            placeholder="Enter text and press return">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Share some
                                                            details.</span>
                                                        <textarea class="form-control border-white mb-2 text-white"
                                                            id="friendshipDetails" name="friendshipDetails"
                                                            placeholder="How did you meet? What did you have in common? What activities did you enjoy together?"
                                                            rows="5" required></textarea>
                                                        <div class="text-end">
                                                            <span id="friendshipDetailsCount" class="text-white">0 of
                                                                500</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white"> Add media to your memory.</span>
                                                        <div class="col-12">
                                                            <input id="friendshipPic" class="form-control border-white"
                                                                name="friendshipPic" type="file"
                                                                accept=".jpg, .jpeg, .png" name=""
                                                                onchange="previewImage(this, 'previewfriendshipPic', 'friendshipfileName');">
                                                            <div
                                                                class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                                                                <img id="previewfriendshipPic" src=""
                                                                    alt="Profile Preview">
                                                                <span id="friendshipfileName" class="text-white"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 hideOnMobile" style="cursor:pointer;">
                                                        <div class="text-center text-white"><i
                                                                class="bi bi-camera-video text-white mx-2"></i>Record a
                                                            video
                                                            message.</div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <p class="mb-0 text-danger" id="passwordError">
                                                        <?php echo $error; ?>
                                                    </p>
                                                    <p class="mb-0 text-success" id="txtMessage">
                                                        <?php echo $message; ?>
                                                    </p>
                                                </div>

                                                <div class="row mt-1 mx-2 my-2 position-relative">
                                                    <div class="text-center mb-2 col-12">
                                                        <button type="button" class="btn btn-white"
                                                            onclick="toggleDropdown('friendship')">
                                                            Save
                                                            <i class="bi bi-caret-down"></i>
                                                        </button>
                                                        <div id="saveDropdown-friendship"
                                                            class=" dropdown-menu-tm position-absolute bg-white shadow"
                                                            style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndContinue('friendship')">Save &
                                                                Continue</button>
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndClose()">Save & Close</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                        <form class="row g-3 totallyHide builderForm" id="home">
                                            <div>
                                                <div class="row mt-1 mx-2 my-2">
                                                    <div class="text-start mb-2 col-9">
                                                        <h6 class="fw-normal fs-6">Memory</h6>
                                                    </div>
                                                    <div class="text-end mb-2 col-3">
                                                        <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
                                                    </div>
                                                </div>
                                                <div class="row mt-1 mx-2 my-2 memtabs">
                                                    <div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
                                                        onclick="activateTab(this)" tag="birth-remember">
                                                        <h6 class="fw-normal fs-6">Remember</h6>
                                                    </div>
                                                    <div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-share">
                                                        <h6 class="fw-normal fs-6">Share</h6>
                                                    </div>
                                                    <div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-collab">
                                                        <h6 class="fw-normal fs-6">Collaborate</h6>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2 tabgroup">

                                                    <span class="mb-2 text-white " id="name-label">Share a story about
                                                        starting school.</span>
                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Give it a
                                                            title.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="homeTitle" name="homeTitle" placeholder="Title"
                                                            value="Home">
                                                        <div class="text-end">
                                                            <span id="homeTitleCount" class="text-white">0 of 100</span>
                                                        </div>
                                                    </div>


                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Where was
                                                            the home located?</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="homeLocation" name="homeLocation"
                                                            placeholder="Enter location">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="birth-label">When did you move
                                                            in?</span>
                                                        <input type="date" class="form-control border-white mb-2"
                                                            id="inputhomeDate" name="inputhomeDate"
                                                            placeholder="Select a date" required>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input border-white" type="checkbox"
                                                                id="homeSwitch">
                                                            <label class="form-check-label" for="homeSwitch">This Day in
                                                                History adds facts about current
                                                                events.</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white">Add a tag to associate this memory
                                                            with others on your timeline.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="homeTag" name="homeTag"
                                                            placeholder="Enter text and press return">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Share some
                                                            details.</span>
                                                        <textarea class="form-control border-white mb-2 text-white"
                                                            id="homeDetails" name="homeDetails"
                                                            placeholder="What attracted you to this home? Who did you share it with? What was the neighborhood like? Did you make friends with any of the neighbors? "
                                                            rows="5" required></textarea>
                                                        <div class="text-end">
                                                            <span id="homeDetailsCount" class="text-white">0 of
                                                                500</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white"> Add media to your memory.</span>
                                                        <div class="col-12">
                                                            <input id="homePic" class="form-control border-white"
                                                                name="homePic" type="file" accept=".jpg, .jpeg, .png"
                                                                name=""
                                                                onchange="previewImage(this, 'previewhomePic', 'homefileName');">
                                                            <div
                                                                class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                                                                <img id="previewhomePic" src="" alt="Profile Preview">
                                                                <span id="homefileName" class="text-white"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 hideOnMobile" style="cursor:pointer;">
                                                        <div class="text-center text-white"><i
                                                                class="bi bi-camera-video text-white mx-2"></i>Record a
                                                            video
                                                            message.</div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <p class="mb-0 text-danger" id="passwordError">
                                                        <?php echo $error; ?>
                                                    </p>
                                                    <p class="mb-0 text-success" id="txtMessage">
                                                        <?php echo $message; ?>
                                                    </p>
                                                </div>

                                                <div class="row mt-1 mx-2 my-2 position-relative">
                                                    <div class="text-center mb-2 col-12">
                                                        <button type="button" class="btn btn-white"
                                                            onclick="toggleDropdown('home')">
                                                            Save
                                                            <i class="bi bi-caret-down"></i>
                                                        </button>
                                                        <div id="saveDropdown-home"
                                                            class=" dropdown-menu-tm position-absolute bg-white shadow"
                                                            style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndContinue('home')">Save &
                                                                Continue</button>
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndClose()">Save & Close</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                        <form class="row g-3 totallyHide builderForm" id="job">
                                            <div>
                                                <div class="row mt-1 mx-2 my-2">
                                                    <div class="text-start mb-2 col-9">
                                                        <h6 class="fw-normal fs-6">Memory</h6>
                                                    </div>
                                                    <div class="text-end mb-2 col-3">
                                                        <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
                                                    </div>
                                                </div>
                                                <div class="row mt-1 mx-2 my-2 memtabs">
                                                    <div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
                                                        onclick="activateTab(this)" tag="birth-remember">
                                                        <h6 class="fw-normal fs-6">Remember</h6>
                                                    </div>
                                                    <div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-share">
                                                        <h6 class="fw-normal fs-6">Share</h6>
                                                    </div>
                                                    <div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-collab">
                                                        <h6 class="fw-normal fs-6">Collaborate</h6>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2 tabgroup">

                                                    <span class="mb-2 text-white " id="name-label">Share a story about
                                                        starting school.</span>
                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Give it a
                                                            title.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="jobTitle" name="jobTitle" placeholder="Title"
                                                            value="School">
                                                        <div class="text-end">
                                                            <span id="jobTitleCount" class="text-white">0 of 100</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">What was
                                                            company called?</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="jobName" name="jobName" placeholder="Enter name">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Where was it
                                                            located?</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="jobLocation" name="jobLocation"
                                                            placeholder="Enter location">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="birth-label">When did you
                                                            start the job?</span>
                                                        <input type="date" class="form-control border-white mb-2"
                                                            id="inputjobDate" name="inputjobDate"
                                                            placeholder="Select a date" required>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input border-white" type="checkbox"
                                                                id="jobSwitch">
                                                            <label class="form-check-label" for="jobSwitch">This Day in
                                                                History adds facts about current
                                                                events.</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white">Add a tag to associate this memory
                                                            with others on your timeline.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="jobTag" name="jobTag"
                                                            placeholder="Enter text and press return">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Share some
                                                            details.</span>
                                                        <textarea class="form-control border-white mb-2 text-white"
                                                            id="jobDetails" name="jobDetails"
                                                            placeholder="Who was your boss? What was your role there? What were your co-workers like? Did you like the job?"
                                                            rows="5" required></textarea>
                                                        <div class="text-end">
                                                            <span id="jobDetailsCount" class="text-white">0 of
                                                                500</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white"> Add media to your memory.</span>
                                                        <div class="col-12">
                                                            <input id="jobPic" class="form-control border-white"
                                                                name="jobPic" type="file" accept=".jpg, .jpeg, .png"
                                                                name=""
                                                                onchange="previewImage(this, 'previewjobPic', 'jobfileName');">
                                                            <div
                                                                class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                                                                <img id="previewjobPic" src="" alt="Profile Preview">
                                                                <span id="jobfileName" class="text-white"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 hideOnMobile" style="cursor:pointer;">
                                                        <div class="text-center text-white"><i
                                                                class="bi bi-camera-video text-white mx-2"></i>Record a
                                                            video
                                                            message.</div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <p class="mb-0 text-danger" id="passwordError">
                                                        <?php echo $error; ?>
                                                    </p>
                                                    <p class="mb-0 text-success" id="txtMessage">
                                                        <?php echo $message; ?>
                                                    </p>
                                                </div>

                                                <div class="row mt-1 mx-2 my-2 position-relative">
                                                    <div class="text-center mb-2 col-12">
                                                        <button type="button" class="btn btn-white"
                                                            onclick="toggleDropdown('job')">
                                                            Save
                                                            <i class="bi bi-caret-down"></i>
                                                        </button>
                                                        <div id="saveDropdown-job"
                                                            class=" dropdown-menu-tm position-absolute bg-white shadow"
                                                            style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndContinue('job')">Save &
                                                                Continue</button>
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndClose()">Save & Close</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                        <form class="row g-3 totallyHide builderForm" id="relationship">
                                            <div>
                                                <div class="row mt-1 mx-2 my-2">
                                                    <div class="text-start mb-2 col-9">
                                                        <h6 class="fw-normal fs-6">Memory</h6>
                                                    </div>
                                                    <div class="text-end mb-2 col-3">
                                                        <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
                                                    </div>
                                                </div>
                                                <div class="row mt-1 mx-2 my-2 memtabs">
                                                    <div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
                                                        onclick="activateTab(this)" tag="birth-remember">
                                                        <h6 class="fw-normal fs-6">Remember</h6>
                                                    </div>
                                                    <div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-share">
                                                        <h6 class="fw-normal fs-6">Share</h6>
                                                    </div>
                                                    <div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-collab">
                                                        <h6 class="fw-normal fs-6">Collaborate</h6>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2 tabgroup">

                                                    <span class="mb-2 text-white " id="name-label">Share a story about
                                                        starting school.</span>
                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Give it a
                                                            title.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="relationshipTitle" name="relationshipTitle"
                                                            placeholder="Title" value="Relationship">
                                                        <div class="text-end">
                                                            <span id="relationshipTitleCount" class="text-white">0 of
                                                                100</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Who was your
                                                            love interest?</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="relationshipName" name="relationshipName"
                                                            placeholder="Enter name">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Where did
                                                            you meet?</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="relationshipLocation" name="relationshipLocation"
                                                            placeholder="Enter location">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="birth-label">When did you
                                                            meet?</span>
                                                        <input type="date" class="form-control border-white mb-2"
                                                            id="inputrelationshipDate" name="inputrelationshipDate"
                                                            placeholder="Select a date" required>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input border-white" type="checkbox"
                                                                id="relationshipSwitch">
                                                            <label class="form-check-label"
                                                                for="relationshipSwitch">This Day in History adds facts
                                                                about current
                                                                events.</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white">Add a tag to associate this memory
                                                            with others on your timeline.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="relationshipTag" name="relationshipTag"
                                                            placeholder="Enter text and press return">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Share some
                                                            details.</span>
                                                        <textarea class="form-control border-white mb-2 text-white"
                                                            id="relationshipDetails" name="relationshipDetails"
                                                            placeholder="What first attracted you to this person? Do you remember your first date? Where did you like to go together? What activities did you enjoy?"
                                                            rows="5" required></textarea>
                                                        <div class="text-end">
                                                            <span id="relationshipDetailsCount" class="text-white">0 of
                                                                500</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white"> Add media to your memory.</span>
                                                        <div class="col-12">
                                                            <input id="relationshipPic"
                                                                class="form-control border-white" name="relationshipPic"
                                                                type="file" accept=".jpg, .jpeg, .png" name=""
                                                                onchange="previewImage(this, 'previewrelationshipPic', 'relationshipfileName');">
                                                            <div
                                                                class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                                                                <img id="previewrelationshipPic" src=""
                                                                    alt="Profile Preview">
                                                                <span id="relationshipfileName"
                                                                    class="text-white"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 hideOnMobile" style="cursor:pointer;">
                                                        <div class="text-center text-white"><i
                                                                class="bi bi-camera-video text-white mx-2"></i>Record a
                                                            video
                                                            message.</div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <p class="mb-0 text-danger" id="passwordError">
                                                        <?php echo $error; ?>
                                                    </p>
                                                    <p class="mb-0 text-success" id="txtMessage">
                                                        <?php echo $message; ?>
                                                    </p>
                                                </div>

                                                <div class="row mt-1 mx-2 my-2 position-relative">
                                                    <div class="text-center mb-2 col-12">
                                                        <button type="button" class="btn btn-white"
                                                            onclick="toggleDropdown('relationship')">
                                                            Save
                                                            <i class="bi bi-caret-down"></i>
                                                        </button>
                                                        <div id="saveDropdown-relationship"
                                                            class=" dropdown-menu-tm position-absolute bg-white shadow"
                                                            style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndContinue('relationship')">Save &
                                                                Continue</button>
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndClose()">Save & Close</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                        <form class="row g-3 totallyHide builderForm" id="parent">
                                            <div>
                                                <div class="row mt-1 mx-2 my-2">
                                                    <div class="text-start mb-2 col-9">
                                                        <h6 class="fw-normal fs-6">Memory</h6>
                                                    </div>
                                                    <div class="text-end mb-2 col-3">
                                                        <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
                                                    </div>
                                                </div>
                                                <div class="row mt-1 mx-2 my-2 memtabs">
                                                    <div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
                                                        onclick="activateTab(this)" tag="birth-remember">
                                                        <h6 class="fw-normal fs-6">Remember</h6>
                                                    </div>
                                                    <div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-share">
                                                        <h6 class="fw-normal fs-6">Share</h6>
                                                    </div>
                                                    <div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-collab">
                                                        <h6 class="fw-normal fs-6">Collaborate</h6>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2 tabgroup">

                                                    <span class="mb-2 text-white " id="name-label">Share a story about
                                                        starting school.</span>
                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Give it a
                                                            title.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="parentTitle" name="parentTitle" placeholder="Title"
                                                            value="Parenthood">
                                                        <div class="text-end">
                                                            <span id="parentTitleCount" class="text-white">0 of
                                                                100</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">What was the
                                                            child's name?</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="parentName" name="parentName" placeholder="Enter name">
                                                    </div>



                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="birth-label">When did the pet
                                                            become part of your family?</span>
                                                        <input type="date" class="form-control border-white mb-2"
                                                            id="inputparentDate" name="inputparentDate"
                                                            placeholder="Select a date" required>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input border-white" type="checkbox"
                                                                id="parentSwitch">
                                                            <label class="form-check-label" for="parentSwitch">This Day
                                                                in History adds facts about current
                                                                events.</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white">Add a tag to associate this memory
                                                            with others on your timeline.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="parentTag" name="parentTag"
                                                            placeholder="Enter text and press return">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Share some
                                                            details.</span>
                                                        <textarea class="form-control border-white mb-2 text-white"
                                                            id="parentDetails" name="parentDetails"
                                                            placeholder="How did you feel about becoming a parent? Describe your child’s arrival—was it by birth, foster care, or adoption?  What special memories did you have from that time?"
                                                            rows="5" required></textarea>
                                                        <div class="text-end">
                                                            <span id="parentDetailsCount" class="text-white">0 of
                                                                500</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white"> Add media to your memory.</span>
                                                        <div class="col-12">
                                                            <input id="parentPic" class="form-control border-white"
                                                                name="parentPic" type="file" accept=".jpg, .jpeg, .png"
                                                                name=""
                                                                onchange="previewImage(this, 'previewparentPic', 'parentfileName');">
                                                            <div
                                                                class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                                                                <img id="previewparentPic" src="" alt="Profile Preview">
                                                                <span id="parentfileName" class="text-white"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 hideOnMobile" style="cursor:pointer;">
                                                        <div class="text-center text-white"><i
                                                                class="bi bi-camera-video text-white mx-2"></i>Record a
                                                            video
                                                            message.</div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <p class="mb-0 text-danger" id="passwordError">
                                                        <?php echo $error; ?>
                                                    </p>
                                                    <p class="mb-0 text-success" id="txtMessage">
                                                        <?php echo $message; ?>
                                                    </p>
                                                </div>

                                                <div class="row mt-1 mx-2 my-2 position-relative">
                                                    <div class="text-center mb-2 col-12">
                                                        <button type="button" class="btn btn-white"
                                                            onclick="toggleDropdown('parent')">
                                                            Save
                                                            <i class="bi bi-caret-down"></i>
                                                        </button>
                                                        <div id="saveDropdown-parent"
                                                            class=" dropdown-menu-tm position-absolute bg-white shadow"
                                                            style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndContinue('parent')">Save &
                                                                Continue</button>
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndClose()">Save & Close</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                        <form class="row g-3 totallyHide builderForm" id="pet">
                                            <div>
                                                <div class="row mt-1 mx-2 my-2">
                                                    <div class="text-start mb-2 col-9">
                                                        <h6 class="fw-normal fs-6">Memory</h6>
                                                    </div>
                                                    <div class="text-end mb-2 col-3">
                                                        <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
                                                    </div>
                                                </div>
                                                <div class="row mt-1 mx-2 my-2 memtabs">
                                                    <div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
                                                        onclick="activateTab(this)" tag="birth-remember">
                                                        <h6 class="fw-normal fs-6">Remember</h6>
                                                    </div>
                                                    <div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-share">
                                                        <h6 class="fw-normal fs-6">Share</h6>
                                                    </div>
                                                    <div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-collab">
                                                        <h6 class="fw-normal fs-6">Collaborate</h6>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2 tabgroup">

                                                    <span class="mb-2 text-white " id="name-label">Share a story about
                                                        starting school.</span>
                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Give it a
                                                            title.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="petTitle" name="petTitle" placeholder="Title"
                                                            value="Pet">
                                                        <div class="text-end">
                                                            <span id="petTitleCount" class="text-white">0 of 100</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">What was the
                                                            pet's?</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="petName" name="petName" placeholder="Enter name">
                                                    </div>


                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="birth-label">When did the pet
                                                            become part of your family?</span>
                                                        <input type="date" class="form-control border-white mb-2"
                                                            id="inputpetDate" name="inputpetDate"
                                                            placeholder="Select a date" required>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input border-white" type="checkbox"
                                                                id="petSwitch">
                                                            <label class="form-check-label" for="petSwitch">This Day in
                                                                History adds facts about current
                                                                events.</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white">Add a tag to associate this memory
                                                            with others on your timeline.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="petTag" name="petTag"
                                                            placeholder="Enter text and press return">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Share some
                                                            details.</span>
                                                        <textarea class="form-control border-white mb-2 text-white"
                                                            id="petDetails" name="petDetails"
                                                            placeholder="How did you meet this pet? What attracted you to it? Do you remember any funny stories about it?"
                                                            rows="5" required></textarea>
                                                        <div class="text-end">
                                                            <span id="petDetailsCount" class="text-white">0 of
                                                                500</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white"> Add media to your memory.</span>
                                                        <div class="col-12">
                                                            <input id="petPic" class="form-control border-white"
                                                                name="petPic" type="file" accept=".jpg, .jpeg, .png"
                                                                name=""
                                                                onchange="previewImage(this, 'previewpetPic', 'petfileName');">
                                                            <div
                                                                class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                                                                <img id="previewpetPic" src="" alt="Profile Preview">
                                                                <span id="petfileName" class="text-white"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 hideOnMobile" style="cursor:pointer;">
                                                        <div class="text-center text-white"><i
                                                                class="bi bi-camera-video text-white mx-2"></i>Record a
                                                            video
                                                            message.</div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <p class="mb-0 text-danger" id="passwordError">
                                                        <?php echo $error; ?>
                                                    </p>
                                                    <p class="mb-0 text-success" id="txtMessage">
                                                        <?php echo $message; ?>
                                                    </p>
                                                </div>

                                                <div class="row mt-1 mx-2 my-2 position-relative">
                                                    <div class="text-center mb-2 col-12">
                                                        <button type="button" class="btn btn-white"
                                                            onclick="toggleDropdown('pet')">
                                                            Save
                                                            <i class="bi bi-caret-down"></i>
                                                        </button>
                                                        <div id="saveDropdown-pet"
                                                            class=" dropdown-menu-tm position-absolute bg-white shadow"
                                                            style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndContinue('pet')">Save &
                                                                Continue</button>
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndClose()">Save & Close</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                        <form class="row g-3 totallyHide builderForm" id="faith">
                                            <div>
                                                <div class="row mt-1 mx-2 my-2">
                                                    <div class="text-start mb-2 col-9">
                                                        <h6 class="fw-normal fs-6">Memory</h6>
                                                    </div>
                                                    <div class="text-end mb-2 col-3">
                                                        <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
                                                    </div>
                                                </div>
                                                <div class="row mt-1 mx-2 my-2 memtabs">
                                                    <div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
                                                        onclick="activateTab(this)" tag="birth-remember">
                                                        <h6 class="fw-normal fs-6">Remember</h6>
                                                    </div>
                                                    <div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-share">
                                                        <h6 class="fw-normal fs-6">Share</h6>
                                                    </div>
                                                    <div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="birth-collab">
                                                        <h6 class="fw-normal fs-6">Collaborate</h6>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2 tabgroup">

                                                    <span class="mb-2 text-white " id="name-label">Share a story about
                                                        starting school.</span>
                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Give it a
                                                            title.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="faithTitle" name="faithTitle" placeholder="Title"
                                                            value="Faith">
                                                        <div class="text-end">
                                                            <span id="faithTitleCount" class="text-white">0 of
                                                                100</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">What was the
                                                            milestone?</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="faithName" name="faithName" placeholder="Enter name">
                                                    </div>


                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="birth-label">When did it take
                                                            place?</span>
                                                        <input type="date" class="form-control border-white mb-2"
                                                            id="inputfaithDate" name="inputfaithDate"
                                                            placeholder="Select a date" required>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input border-white" type="checkbox"
                                                                id="faithSwitch">
                                                            <label class="form-check-label" for="faithSwitch">This Day
                                                                in History adds facts about current
                                                                events.</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white">Add a tag to associate this memory
                                                            with others on your timeline.</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="faithTag" name="faithTag"
                                                            placeholder="Enter text and press return">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="shortDescription">Share some
                                                            details.</span>
                                                        <textarea class="form-control border-white mb-2 text-white"
                                                            id="faithDetails" name="faithDetails"
                                                            placeholder="Describe this milestone and why it is important to you."
                                                            rows="5" required></textarea>
                                                        <div class="text-end">
                                                            <span id="faithDetailsCount" class="text-white">0 of
                                                                500</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white"> Add media to your memory.</span>
                                                        <div class="col-12">
                                                            <input id="faithPic" class="form-control border-white"
                                                                name="faithPic" type="file" accept=".jpg, .jpeg, .png"
                                                                name=""
                                                                onchange="previewImage(this, 'previewfaithPic', 'faithfileName');">
                                                            <div
                                                                class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                                                                <img id="previewfaithPic" src="" alt="Profile Preview">
                                                                <span id="faithfileName" class="text-white"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 hideOnMobile" style="cursor:pointer;">
                                                        <div class="text-center text-white"><i
                                                                class="bi bi-camera-video text-white mx-2"></i>Record a
                                                            video
                                                            message.</div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <p class="mb-0 text-danger" id="passwordError">
                                                        <?php echo $error; ?>
                                                    </p>
                                                    <p class="mb-0 text-success" id="txtMessage">
                                                        <?php echo $message; ?>
                                                    </p>
                                                </div>

                                                <div class="row mt-1 mx-2 my-2 position-relative">
                                                    <div class="text-center mb-2 col-12">
                                                        <button type="button" class="btn btn-white"
                                                            onclick="toggleDropdown('faith')">
                                                            Save
                                                            <i class="bi bi-caret-down"></i>
                                                        </button>
                                                        <div id="saveDropdown-faith"
                                                            class=" dropdown-menu-tm position-absolute bg-white shadow"
                                                            style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndContinue('faith')">Save &
                                                                Continue</button>
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndClose()">Save & Close</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                        <form class="row g-3 totallyHide builderForm" id="story">
                                            <div>
                                                <div class="row mt-1 mx-2 my-2">
                                                    <div class="text-start mb-2 col-9">
                                                        <h6 class="fw-normal fs-6">Memory</h6>
                                                    </div>
                                                    <div class="text-end mb-2 col-3">
                                                        <i class="bi bi-trash text-white" onclick="saveAndClose()"></i>
                                                    </div>
                                                </div>
                                                <div class="row mt-1 mx-2 my-2 memtabs">
                                                    <div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
                                                        onclick="activateTab(this)" tag="story-remember">
                                                        <h6 class="fw-normal fs-6">Remember</h6>
                                                    </div>
                                                    <div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="story-share">
                                                        <h6 class="fw-normal fs-6">Share</h6>
                                                    </div>
                                                    <div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
                                                        onclick="activateTab(this)" tag="story-collab">
                                                        <h6 class="fw-normal fs-6">Collaborate</h6>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2 tabgroup">
                                                    <div class="tabsect story-remember">
                                                        <span class="mb-2 text-white " id="name-label">Share a
                                                            story.</span>
                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white" id="shortDescription">Give it
                                                                a title.</span>
                                                            <input type="text" class="form-control border-white mb-2"
                                                                id="storyTitle" name="storyTitle" placeholder="Title"
                                                                value="story">
                                                            <div class="text-end">
                                                                <span id="storyTitleCount" class="text-white">0 of
                                                                    100</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white" id="shortDescription">What was
                                                                the milestone?</span>
                                                            <input type="text" class="form-control border-white mb-2"
                                                                id="storyName" name="storyName"
                                                                placeholder="Enter name">
                                                        </div>


                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white" id="birth-label">When did it
                                                                take place?</span>
                                                            <input type="date" class="form-control border-white mb-2"
                                                                id="inputstoryDate" name="inputstoryDate"
                                                                placeholder="Select a date" required>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input border-white"
                                                                    type="checkbox" id="storySwitch">
                                                                <label class="form-check-label" for="storySwitch">This
                                                                    Day in History adds facts about current
                                                                    events.</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white">Add a tag to associate this
                                                                memory with others on your timeline.</span>
                                                            <input type="text" class="form-control border-white mb-2"
                                                                id="storyTag" name="storyTag"
                                                                placeholder="Enter text and press return">
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white" id="shortDescription">Share
                                                                some details.</span>
                                                            <textarea class="form-control border-white mb-2 text-white"
                                                                id="storyDetails" name="storyDetails"
                                                                placeholder="Describe this milestone and why it is important to you."
                                                                rows="5" required></textarea>
                                                            <div class="text-end">
                                                                <span id="storyDetailsCount" class="text-white">0 of
                                                                    500</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <span class="mb-2 text-white"> Add media to your
                                                                memory.</span>
                                                            <div class="col-12">
                                                                <input id="storyPic" class="form-control border-white"
                                                                    name="storyPic" type="file"
                                                                    accept=".jpg, .jpeg, .png" name=""
                                                                    onchange="previewImage(this, 'previewstoryPic', 'storyfileName');">
                                                                <div
                                                                    class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                                                                    <img id="previewstoryPic" src=""
                                                                        alt="Profile Preview">
                                                                    <span id="storyfileName" class="text-white"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 hideOnMobile" style="cursor:pointer;">
                                                            <div class="text-center text-white"><i
                                                                    class="bi bi-camera-video text-white mx-2"></i>Record
                                                                a video
                                                                message.</div>
                                                        </div>

                                                    </div>
                                                    <div class="tabsect story-share totallyHide">
                                                        <span class="mb-2 text-white fs-6" id="name-label">Choose the
                                                            audience for this memory. Who can see it?</span>
                                                        <div class="col-12 mb-3 mt-3">
                                                            <label class="cbContainer">
                                                                <input type="radio" name="story-audience" value="family"
                                                                    checked>
                                                                <span class="checkmark"></span>
                                                                <div class="text">
                                                                    <div class="title">Only family (default)</div>
                                                                </div>
                                                            </label>

                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <label class="cbContainer">
                                                                <input type="radio" name="story-audience"
                                                                    value="connections">
                                                                <span class="checkmark"></span>
                                                                <div class="text">
                                                                    <div class="title">All connections</div>
                                                                </div>
                                                            </label>

                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <label class="cbContainer">
                                                                <input type="radio" name="story-audience"
                                                                    value="anyone">
                                                                <span class="checkmark"></span>
                                                                <div class="text">
                                                                    <div class="title">Anyone (public)</div>
                                                                </div>
                                                            </label>

                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <label class="cbContainer">
                                                                <input type="radio" name="story-audience"
                                                                    value="custom">
                                                                <span class="checkmark"></span>
                                                                <div class="text">
                                                                    <div class="title">Custom</div>
                                                                </div>
                                                            </label>

                                                        </div>
                                                    </div>
                                                    <div class="tabsect story-collab totallyHide">
                                                        <span class="mb-2 text-white fs-6" id="name-label">Invite
                                                            existing connections to contribute to this memory.</span>
                                                        <div class="row mt-1 mx-2 my-2 align-items-center">
                                                            <div class="text-start col-1 d-flex justify-content-center"
                                                                style="cursor: pointer;">
                                                                <i class="bi bi-search text-white fs-5"></i>
                                                            </div>
                                                            <div class="text-end col-11">
                                                                <input type="text" class="form-control border-white"
                                                                    id="searchParam" name="searchParam"
                                                                    placeholder="Search" required>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <div
                                                                    class="collab-name d-flex justify-content-center gap-2 px-3 py-2">
                                                                    <i class="bi bi-person text-white text-start"
                                                                        style="cursor: pointer;"></i>
                                                                    <div class="text-center">Name 1</div>
                                                                    <i class="bi bi-x text-white text-end"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <form class="row g-3 w-100">
                                                            <div class="scrollable-div col-12 w-100 mt-2">
                                                                <div class="col-12 mb-2 w-100">
                                                                    <div
                                                                        class="mb-3 d-flex align-items-center justify-content-between">
                                                                        <h6 class="mb-0">Invite a new connection to
                                                                            contribute.</h6>
                                                                    </div>


                                                                    <div class="col-12 mb-2">
                                                                        <span class="mb-2 text-white">Name?
                                                                            <sup>*</sup></span>
                                                                        <input type="text"
                                                                            class="form-control border-white mb-2"
                                                                            id="storyinputCollabFirstName"
                                                                            name="storyinputCollabFirstName"
                                                                            placeholder="First name" required>
                                                                        <input type="text"
                                                                            class="form-control border-white mb-2"
                                                                            id="storyinputCollabLastName"
                                                                            name="storyinputCollabLastName"
                                                                            placeholder="Last name" required>
                                                                    </div>

                                                                    <div class="col-12 mb-2">
                                                                        <span class="mb-2 text-white"><i>Email
                                                                                <sup>*</sup></i></span>
                                                                        <input type="email"
                                                                            class="form-control border-white mb-2"
                                                                            id="storyinputCollabEmail" name="inputEmail"
                                                                            placeholder="name@email.com" required>
                                                                    </div>

                                                                    <div class="col-12">
                                                                        <span class="mb-2 text-white"><i>Relationship
                                                                                <sup>*</sup></i></span>
                                                                        <select
                                                                            class="form-select border-white mb-2 text-white bg-dark"
                                                                            id="storyinputCollabRelation"
                                                                            name="storyinputCollabRelation" required>
                                                                            <option value="" disabled selected>Choose a
                                                                                relationship</option>
                                                                            <option selected value="friend">Friend
                                                                            </option>
                                                                            <option value="sibling">Sibling</option>
                                                                            <option value="partner">Partner</option>
                                                                            <option value="other">Other</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-12 mb-3">
                                                                        <span class="mb-2 text-white"
                                                                            id="shortDescription">Share some
                                                                            details.</span>
                                                                        <textarea
                                                                            class="form-control border-white mb-2 text-white"
                                                                            id="storyCollabDetails"
                                                                            name="storyCollabDetails"
                                                                            placeholder="Optional" rows="3"></textarea>
                                                                        <div class="text-end">
                                                                            <span id="storyCollabDetailsCount"
                                                                                class="text-white">0 of 500</span>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <p class="mb-0 text-danger" id="passwordError">
                                                        <?php echo $error; ?>
                                                    </p>
                                                    <p class="mb-0 text-success" id="txtMessage">
                                                        <?php echo $message; ?>
                                                    </p>
                                                </div>

                                                <div class="row mt-1 mx-2 my-2 position-relative">
                                                    <div class="text-center mb-2 col-12">
                                                        <button type="button" class="btn btn-white"
                                                            onclick="toggleDropdown('story')">
                                                            Save
                                                            <i class="bi bi-caret-down"></i>
                                                        </button>
                                                        <div id="saveDropdown-story"
                                                            class=" dropdown-menu-tm position-absolute bg-white shadow"
                                                            style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndContinue('story')">Save &
                                                                Continue</button>
                                                            <button class="dropdown-item-tm"
                                                                onclick="saveAndClose()">Save & Close</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>

                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>

                <div class="main-body">
                    <div class="connections-header">
                        <div class="row mt-1 mx-2 my-2">
                            <div class="text-start mb-2 col-6">
                                <h6 class="fw-normal fs-4"><img src="assets/images/icons/connections.png" alt="Back"
                                        style="height: 1em; vertical-align: middle; margin-right:10px;">Connections</h6>
                            </div>
                            <div class="d-flex justify-content-end mb-2 col-6">
                                <button type="button" onclick="addNew('new');"
                                    class="btn btn-white text-black slim-next"
                                    onmouseover="changeIcon(this, 'assets/images/icons/send-white.svg')"
                                    onmouseout="changeIcon(this, 'assets/images/icons/send.svg')"> <img
                                        src="assets/images/icons/send.svg" alt="Back"
                                        style="vertical-align: middle; margin-right:5px;"> Invite </button>
                            </div>
                        </div>

                        <div class="row mt-1 mx-2 my-2">
                            <div class="text-start mb-2 col-6 btn btn-white border text-black border-white d-flex justify-content-center align-items-center btn-hover-white"
                                id="btn-active">

                                Active
                            </div>
                            <div class="text-end mb-2 col-6 btn border border-white text-white d-flex justify-content-center align-items-center btn-hover-white"
                                id="btn-pending">

                                Pending
                            </div>
                        </div>


                        <div class="row mt-1 mx-2 my-2 align-items-center">
                            <div class="text-start col-1 d-flex justify-content-center" style="cursor: pointer;">
                                <i class="bi bi-search text-white fs-5"></i>
                            </div>
                            <div class="text-end col-11">
                                <input type="text" class="form-control border-white" id="searchParam" name="searchParam"
                                    placeholder="Search" required>
                            </div>
                        </div>

                        <div class="row mt-1 mx-2 my-2 align-items-center">
                            <div class="text-start col-6 d-flex align-items-center">
                                <!-- <span class="text-white">Sort:</span> -->
                                <!-- <span class="custom-arrow">▼</span> -->
                                <div class="dropdown-container-2" id="sortDropdown">
                                    <span id="selectedSort">Sort: None ▼</span>
                                    <div class="dropdown-menu-2">
                                        <div data-value="Most Recent">Most Recent</div>
                                        <div data-value="A-Z">A-Z</div>
                                        <div data-value="Z-A">Z-A</div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end col-6 d-flex justify-content-end align-items-center">
                                <!-- <span class="text-white">Filter:</span> -->
                                <div class="dropdown-container-2 text-start" id="filterDropdown">
                                    <span id="selectedFilter">Filter: None ▼</span>
                                    <div class="dropdown-menu-2">
                                        <div data-value="All">All</div>
                                        <div data-value="Family">Family</div>
                                        <div data-value="Friends">Friends</div>
                                        <div data-value="Other">Other</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="stepinvite" class="totallyHide connection-container-form">
                        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                            <div class="form-body w-100">
                                <form class="row g-3 w-100" action="connections.php" method="POST"
                                    enctype="multipart/form-data">
                                    <input type="text" id="recordid" name="recordid" hidden>
                                    <div class="scrollable-div w-100">
                                        <div class="col-12 mb-2 w-100">
                                            <div class="mb-2 d-flex align-items-center justify-content-between">
                                                <h6 class="mb-0" id="formTitle">Edit connection</h6>
                                                <div>
                                                    <i class="bi bi-send me-2" style="cursor: pointer;"></i>
                                                    <i class="bi bi-trash" style="cursor: pointer;"></i>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <span class="mb-2 text-white">Name? <sup>*</sup></span>
                                                <input type="text" class="form-control border-white mb-2"
                                                    id="inputGuestFirstName" name="inputGuestFirstName"
                                                    placeholder="First name" required>
                                                <input type="text" class="form-control border-white mb-2"
                                                    id="inputGuestLastName" name="inputGuestLastName"
                                                    placeholder="Last name" required>
                                            </div>

                                            <div class="col-12 mb-2">
                                                <span class="mb-2 text-white">Email <sup>*</sup></span>
                                                <input type="email" class="form-control border-white mb-2"
                                                    id="inputEmail" name="inputEmail" placeholder="name@email.com"
                                                    required>
                                            </div>

                                            <div class="col-12">
                                                <span class="mb-2 text-white">Relationship <sup>*</sup></span>
                                                <select class="form-select border-white mb-2 text-white bg-dark"
                                                    id="inputGuestRelation" name="inputGuestRelation" required>
                                                    <option value="" disabled selected>Choose a relationship</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="partner">Partner</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                            <div class="access-levels mb-3">
                                                <div class="form-check form-swtch d-flex align-items-center mb-2">
                                                    <input class="form-check-input border-white checkbox square-radio"
                                                        type="radio" name="collaborator" value="1134" id="collabswitch">
                                                    <label class="form-check-label checkbox-label"
                                                        for="collabswitch">Collaborator</label>
                                                </div>
                                                <span>Note: Collaborators can suggest memories for the timeline.</span>
                                            </div>

                                            <div class="access-levels">
                                                <div class="form-check form-swtch d-flex align-items-center">
                                                    <input class="form-check-input border-white checkbox square-radio"
                                                        type="radio" name="collaborator" value="1136" id="collabswitch">
                                                    <label class="form-check-label checkbox-label"
                                                        for="collabswitch">Guest administrator</label>
                                                </div>
                                                <span>Warning: Guest administrators have the ability to make changes to
                                                    any data or setting in this application.</span>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
                                            <p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
                                        </div>
                                        <div class="row my-3">
                                            <div class="text-start col-6">
                                                <button type="button" onclick="validate(1);"
                                                    class="btn btn-transpaernt text-white btn-next">Cancel</button>
                                            </div>

                                            <div class="text-end  col-6">
                                                <button type="submit" class="btn btn-white btn-next">Save
                                                    Changes</button>
                                            </div>
                                        </div>


                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                    <div id="connections">
                        <div id="active-tab" class="">

                            <?php echo $acceptedHtml; ?>
                            <!-- End Accepted card -->
                        </div>

                        <div id="pending-tab" class="totallyHide">
                            <?php echo $inviteHtml; ?>
                            <?php //echo $notAcceptedHtml; 
                            ?>

                        </div>
                    </div>




                </div>
            </div>
        </div>
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i
                class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
    </div>
    <footer class="accent-group page-footer">
        <p class="mb-0">Copyright © <?php echo $year; ?>. All right reserved.</p>
    </footer>
    <!--end wrapper-->

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="assets/plugins/datetimepicker/js/legacy.js"></script>
    <script src="assets/plugins/datetimepicker/js/picker.js"></script>
    <script src="assets/plugins/datetimepicker/js/picker.time.js"></script>
    <script src="assets/plugins/datetimepicker/js/picker.date.js"></script>
    <script src="assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js"></script>
    <script src="assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js"></script>

    <!-- <script>
        $(document).ready(function() {
            $('#image-uploadify').imageuploadify();
        })
    </script> -->
    <script>
    $('.datepicker').pickadate({
            selectMonths: true,
            selectYears: true
        }),
        $('.timepicker').pickatime()
    </script>
    <script>
    $(function() {
        $('#date-time').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm'
        });
        $('#date').bootstrapMaterialDatePicker({
            time: false
        });
        $('#time').bootstrapMaterialDatePicker({
            date: false,
            format: 'HH:mm'
        });
    });
    </script>
    <!--app JS-->
    <script src="assets/js/app.js"></script>

    <script>
    function previewImage(input) {
        const preview = document.getElementById('previewProfile');
        const fileNameSpan = document.getElementById('fileName');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            fileNameSpan.textContent = input.files[0].name;
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'inline';
            }

            reader.readAsDataURL(input.files[0]);

        } else {
            preview.src = '';
            preview.style.display = 'none';
            fileNameSpan.textContent = '';
        }
    }

    function back(step) {
        console.log(document.querySelectorAll('[id^="stepdiv"]'));

        document.querySelectorAll('[id^="stepdiv"]').forEach(step => step.classList.add('totallyHide'));


        document.getElementById('settings-options').style.display = 'block';

    }

    function validate(step) {
        document.querySelectorAll('[id^="stepdiv"]').forEach(step => step.classList.add('totallyHide'));


        document.getElementById('settings-options').style.display = 'block';
        // document.getElementById('step1').style.display = 'block';
    }

    document.querySelectorAll('.letter-square').forEach(square => {
        square.addEventListener('click', function() {
            document.querySelectorAll('.letter-square').forEach(sq => sq.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    document.querySelectorAll('.square-image').forEach((img) => {
        img.addEventListener('click', function() {
            const bgImage = this.src;
            document.body.style.backgroundImage = `url(${bgImage})`;
        });
    });
    </script>

    <script>
    const btnActive = document.getElementById('btn-active');
    const btnPending = document.getElementById('btn-pending');


    function toggleButtonStyles() {
        if (this.id === 'btn-active') {
            btnActive.classList.add('btn-white', 'text-black');
            btnActive.classList.remove('btn-transparent', 'text-white');
            btnPending.classList.add('btn-transparent', 'text-white');
            btnPending.classList.remove('btn-white', 'text-black');
            document.getElementById('pending-tab').classList.add('totallyHide');
            document.getElementById('active-tab').classList.remove('totallyHide');
        } else if (this.id === 'btn-pending') {
            btnPending.classList.add('btn-white', 'text-black');
            btnPending.classList.remove('btn-transparent', 'text-white');
            btnActive.classList.add('btn-transparent', 'text-white');
            btnActive.classList.remove('btn-white', 'text-black');
            document.getElementById('active-tab').classList.add('totallyHide');
            document.getElementById('pending-tab').classList.remove('totallyHide');
        }
    }

    btnActive.addEventListener('click', toggleButtonStyles);
    btnPending.addEventListener('click', toggleButtonStyles);



    function showStepInvite(recordId) {

        const connections = <?php echo json_encode(array_merge($acceptedConnections, $notAcceptedConnections)); ?>;
        document.getElementById("stepinvite").classList.remove("totallyHide");
        document.getElementById('connections').classList.add("totallyHide");

        const connectionData = connections.find(conn => conn.RecordId === recordId);

        if (connectionData) {
            // Populate the form fields with the connection's details
            const fullName = connectionData.PersonName.split(" ");
            const firstName = fullName[0];
            const lastName = fullName.slice(1).join(" "); // Handles names with multiple words
            document.getElementById("recordid").value = recordId;
            document.getElementById("inputGuestFirstName").value = firstName;
            document.getElementById("inputGuestLastName").value = lastName;
            document.getElementById("inputEmail").value = connectionData.emailAddress;
            document.getElementById("inputGuestRelation").value = connectionData.Relationship.toLowerCase();
            if (connectionData.viewabilityRight === "Sibling") {
                document.querySelector('input[name="collaborator"][value="1134"]').checked = true;
            } else {
                document.querySelector('input[name="collaborator"][value="1136"]').checked = true;
            }
        }

    }

    function addNew() {
        document.getElementById("builderForms").classList.remove("totallyHide");

    }



    function validate(step) {
        document.getElementById("stepinvite").classList.add("totallyHide");
        document.getElementById('connections').classList.remove("totallyHide");
    }

    function setupCharCount(inputId, countId, maxLength) {
        const inputElement = document.getElementById(inputId);
        const countElement = document.getElementById(countId);

        inputElement.addEventListener('input', function() {
            const currentLength = inputElement.value.length;

            countElement.textContent = `${currentLength} of ${maxLength}`;

            if (currentLength > maxLength) {
                inputElement.value = inputElement.value.substring(0, maxLength);
            }
        });
    }
    setupCharCount('jobDetails', 'jobDetailsCount', 500);

    function saveAndClose() {

        document.getElementById("builderForms").classList.add("totallyHide");
    }

    function changeIcon(button, newSrc) {
        const img = button.querySelector("img");
        if (img) {
            img.src = newSrc;
        }
    }
    </script>

    <script>
    $(document).ready(function() {
        $(".dropdown-container-2").click(function(e) {
            console.log("toggling");
            e.stopPropagation();
            $(this).find(".dropdown-menu-2").toggle();
        });

        $(".dropdown-menu-2 div").click(function() {
            let selectedValue = $(this).data("value");
            let parentDropdown = $(this).closest(".dropdown-container-2");

            if (parentDropdown.attr("id") === "sortDropdown") {
                $("#selectedSort").text(`Sort: ${selectedValue} ▼`);
                console.log("clicked");
            } else if (parentDropdown.attr("id") === "filterDropdown") {
                $("#selectedFilter").text(`Filter: ${selectedValue} ▼`);
            }

        });


    });
    </script>

</body>

</html>