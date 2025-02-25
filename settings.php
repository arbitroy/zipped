<?php

include 'global.php';
session_start();
if (!$_SESSION["token"]) {
	header("location: index.php");
}

$splitName = explode(" ", $_SESSION['username']);
$email = $countrySelected = $stateSelected = $city = $shortbio = $dob = '';

$response = makeGetAPICall('getGuestAdmin', $_SESSION["token"]);
$resp_json = json_decode($response, true);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$type = $_POST['step'];
	if ($type == "1") {
		$imageExtension = $image = '';
		if (isset($_FILES["profile_pic"])) {
			if ($_FILES["profile_pic"]["error"] == 0) {
				$check = getimagesize($_FILES["profile_pic"]["tmp_name"]);

				if ($check !== false) {
					$image = base64_encode(file_get_contents($_FILES["profile_pic"]["tmp_name"]));
					$imageExtension = end((explode(".", $_FILES["profile_pic"]["name"])));
					$fileType = $_FILES['profile_pic']['type'];
					$dataUri = 'data:' . $fileType . ';base64,' . $image;
					$_SESSION["PROFILE_PIC"] = $dataUri;
				}
			}
		}

		$firstname = $_POST['inputFirstName'];
		$lastname = $_POST['inputLastName'];
		$password = $_POST['inputChoosePassword'];
		$newPassword = $_POST['inputNewPassword'];
		$bodyUpdate = json_encode(array(
			"firstname" => $firstname,
			"lastname" => $lastname,
			"profilepic" => $dataUri,
			"pictype" => $imageExtension,
		));
		$name = $_SESSION['username'] = $firstname . " " . $lastname;
		if ($_POST['profile_pic'] != '') {
			$_SESSION['profile_pic'] = $_POST['profile_pic'];
		}
		$responseUpdate = makePostAPIcall('updateProfile', $bodyUpdate, $_SESSION['token']);

		if ($password != '' && $newPassword != '') {
			$passwordBody = json_encode(array(

				"oldpassword" => $password,
				"newpassword" => $newPassword
			));
			$response = makePostAPIcall('changePassword', $passwordBody, $_SESSION['token']);
		}
		header("location: settings.php");
	}

	if ($type == "3") {
		$background = $_POST['background'];
		$accent = $_POST['accent'];
		$_SESSION['background'] = $background;
		$_SESSION['accent'] = getAccentByValue($accent);
		$body = json_encode(array(
			"theme" => true,
			"accentcolor" => $accent,
			"background" => $background,
		));
		$response = makePostAPIcall('updateProfile', $body, $_SESSION['token']);
	}



	if ($type == "4") {
		$profileviewability = $_POST['VIEW_RIGHT_timeline'];
		$treeviewability = $_POST['VIEW_RIGHT_tree'];

		$_SESSION['profileviewability'] = $profileviewability;
		$_SESSION['treeviewability'] = $treeviewability;
		$body = json_encode(array(
			"profileviewability" => $profileviewability,
			"treeviewability" => $treeviewability,
		));
		$response = makePostAPIcall('updateProfile', $body, $_SESSION['token']);
	}
}

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
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script>
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

        <div id="stepdiv1"
            class="totallyHide section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container settings-container-form">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">

                    <div class="card mb-0 cardClear">
                        <div class="card-body onboarding-card mt-2 d-flex justify-content-center">
                            <div class="form-body w-100">
                                <form class="row g-3" method="POST" action="settings.php" enctype="multipart/form-data">
                                    <input type="text" name="step" value="1" hidden>
                                    <div class="scrollable-div">
                                        <div class="col-12 mb-2">
                                            <div class=" mb-2">
                                                <h4>User Settings</h4>
                                                <p class="main-label">Manage your user settings.</p>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <span class="mb-2 text-white">Name <sup>*</sup></span>
                                                <input type="text" class="form-control border-white mb-2"
                                                    id="inputFirstName" name="inputFirstName" placeholder="First name"
                                                    value=<?php echo $splitName[0]; ?>>
                                                <input type="text" class="form-control border-white mb-2"
                                                    id="inputLastName" name="inputLastName" placeholder="Last name"
                                                    value=<?php echo $splitName[1]; ?>>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <span class="mb-2 text-white">Upload a picture of yourself (jpg or png
                                                    under 8MB)</span>
                                                <div class="col-12">
                                                    <input id="profile_pic" class="form-control border-white"
                                                        name="profile_pic" type="file" accept=".jpg, .jpeg, .png"
                                                        name="" onchange="previewImage(this);">
                                                    <div
                                                        class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
                                                        <img id="previewProfile" src="" alt="Profile Preview">
                                                        <span id="fileName" class="text-white"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 mb-2">
                                                <span class="mb-2 text-white">Email <sup>*</sup></span>
                                                <input type="email" readonly class="form-control border-white mb-2"
                                                    id="inputEmail" name="inputEmail" placeholder="name@email.com"
                                                    value=<?php echo $_SESSION["emailaddress"]; ?>>
                                            </div>

                                            <div class="col-12 mb-2">
                                                <span class="mb-2 text-white">Backup Email </span>
                                                <input type="email" class="form-control border-white mb-2"
                                                    id="inputBackupEmail" name="inputBackupEmail"
                                                    placeholder="name@email.com">
                                            </div>

                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label text-white">Current
                                                    Password<sup>*</sup></label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password"
                                                        class="form-control border-end-0 border-white"
                                                        id="inputChoosePassword" name="inputChoosePassword"
                                                        placeholder="Enter Password"> <a href="javascript:;"
                                                        class="input-group-text bg-transparent border-white"><i
                                                            class='bx bx-hide'></i></a>
                                                </div>

                                                <label for="inputNewPassword" class="form-label text-white mt-2">New
                                                    Password<sup>*</sup></label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password"
                                                        class="form-control border-end-0 border-white"
                                                        id="inputNewPassword" name="inputNewPassword"
                                                        placeholder="Enter New Password"> <a href="javascript:;"
                                                        class="input-group-text bg-transparent border-white"><i
                                                            class='bx bx-hide'></i></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
                                            <p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
                                        </div>



                                    </div>

                                    <input type="submit" hidden id="userSettings" />
                                </form>
                            </div>
                            <div class="d-flex justify-content-center settings-btn">
                                <div class="d-flex justify-content-start col-6">
                                    <button type="button" onclick="back(1);"
                                        class="btn btn-transpaernt text-white btn-next">Cancel</button>
                                </div>

                                <div class="d-flex justify-content-end  col-5">
                                    <button type="submit" class="btn btn-white btn-next" tag="userSettings">Save
                                        Changes</button>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
        <div id="stepdiv2"
            class="totallyHide section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container settings-container-form">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="card mb-0 cardClear">
                        <div class="card-body onboarding-card mt-2 d-flex justify-content-center">
                            <div class="form-body w-100">
                                <form class="row g-3">
                                    <div class="scrollable-div">
                                        <div class="col-12 mb-2">
                                            <div class="prof">
                                                <div class=" mb-2 account-profile-header">
                                                    <!-- style="position:sticky;top:10px;backdrop-filter: blur(10px);z-index: 99;"> -->
                                                    <h4>Account Profile</h4>
                                                    <p class="main-label">Manage the profile information for the subject
                                                        of this account.</p>
                                                </div>
                                                <div class="account-profile-content">
                                                    <div class="col-12 mb-2">
                                                        <span class="mb-2 text-white">Name <sup>*</sup></span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="inputFirstName_acc" name="inputFirstName_acc"
                                                            placeholder="First name" required>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="inputMiddletName_acc" name="inputMiddletName_acc"
                                                            placeholder="Middle name">
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="inputLastName_acc" name="inputLastName_acc"
                                                            placeholder="Last name" required>
                                                        <div class="text-end">
                                                            <span id="invite" style="cursor:pointer;"
                                                                class="text-white"><u>Invite</u></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="birth-label">Date of Birth
                                                            <sup>*</sup></span>
                                                        <input type="date" class="form-control border-white mb-2"
                                                            id="inputDOB" name="inputDOB" placeholder="Select a date"
                                                            required>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white">Place of birth <sup>*</sup></span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="inputCity" name="inputCity" placeholder="City" required>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="inputState" name="inputState"
                                                            placeholder="State/Province/Territory">
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="inputCountry" name="inputCountry" placeholder="Country"
                                                            required>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white" id="birth-label">Date of passing
                                                        </span>
                                                        <input type="date" class="form-control border-white mb-2"
                                                            id="inputDateofPassing" name="inputDateofPassing"
                                                            placeholder="Select a date">
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <span class="mb-2 text-white">Place of passing</span>
                                                        <input type="text" class="form-control border-white mb-2"
                                                            id="inputPassingPlace" name="inputPassingPlace"
                                                            placeholder="City">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
                                            <p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
                                        </div>
                                    </div>
                                    <input type="submit" hidden id="accountProfile" />
                                </form>
                            </div>
                            <div class="d-flex justify-content-center settings-btn">
                                <div class="d-flex justify-content-start col-6">
                                    <button type="button" onclick="back(1);"
                                        class="btn btn-transpaernt text-white btn-next">Cancel</button>
                                </div>
                                <div class="d-flex justify-content-end  col-5">
                                    <button type="button" tag="accountProfile" class="btn btn-white btn-next">Save
                                        Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>

        <div id="stepdiv3"
            class="totallyHide section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container settings-container-form">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">

                    <div class="card mb-0 cardClear">
                        <div class="card-body onboarding-card mt-2 d-flex justify-content-center">
                            <div class="form-body w-100">
                                <form class="row g-3" method="POST" action="settings.php" enctype="multipart/form-data">
                                    <input type="text" name="step" value="3" hidden>
                                    <div class="scrollable-div">
                                        <div class="col-12 mb-2">
                                            <div class=" mb-2">
                                                <h4>Privacy</h4>
                                                <p class="main-label">Manage who can see your information.</p>
                                            </div>
                                            <div class="col-12">
                                                <label for="inputCollection" class="form-label text-white">Who can view
                                                    your timeline?</label>
                                                <select class="form-select border-white" id="VIEW_RIGHT"
                                                    name="VIEW_RIGHT_timeline">
                                                    <option>Select Option </option>
                                                    <option value="1098"
                                                        <?php if ($_SESSION["profileviewability"] == "1098"): ?>
                                                        selected <?php endif ?>>All Connections</option>
                                                    <option value="1099"
                                                        <?php if ($_SESSION["profileviewability"] == "1099"): ?>
                                                        selected <?php endif ?>>Anyone (Public)</option>
                                                    <option value="1101"
                                                        <?php if ($_SESSION["profileviewability"] == "1101"): ?>
                                                        selected <?php endif ?>>Only Family (Default)</option>
                                                    <option value="1140"
                                                        <?php if ($_SESSION["profileviewability"] == "1140"): ?>
                                                        selected <?php endif ?>>Only me</option>
                                                </select>
                                            </div>
                                            <br>
                                            <div class="col-12">
                                                <label for="inputCollection" class="form-label text-white">Who can view
                                                    your family tree?</label>
                                                <select class="form-select border-white" id="VIEW_RIGHT"
                                                    name="VIEW_RIGHT_tree">
                                                    <option>Select Option</option>
                                                    <option value="1098"
                                                        <?php if ($_SESSION["profileviewability"] == "1098"): ?>
                                                        selected <?php endif ?>>All Connections</option>
                                                    <option value="1099"
                                                        <?php if ($_SESSION["profileviewability"] == "1099"): ?>
                                                        selected <?php endif ?>>Anyone (Public)</option>
                                                    <option value="1101"
                                                        <?php if ($_SESSION["profileviewability"] == "1101"): ?>
                                                        selected <?php endif ?>>Only Family (Default)</option>
                                                    <option value="1140"
                                                        <?php if ($_SESSION["profileviewability"] == "1140"): ?>
                                                        selected <?php endif ?>>Only me</option>
                                                </select>
                                            </div>
                                            <p class="mt-4 text-white">You may also set permissions on individual
                                                memories on your timeline.</p>
                                        </div>

                                        <div class="col-12">
                                            <p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
                                            <p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
                                        </div>



                                    </div>

                                    <input type="submit" hidden id="accountPrivacy" />

                                </form>
                            </div>
                            <div class="d-flex justify-content-center settings-btn">
                                <div class="d-flex justify-content-start col-6">
                                    <button type="button" onclick="back(1);"
                                        class="btn btn-transpaernt text-white btn-next">Cancel</button>
                                </div>

                                <div class="d-flex justify-content-end  col-5">
                                    <button type="button" class="btn btn-white btn-next" tag="accountPrivacy">Save
                                        Changes</button>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
            <!--end row-->
        </div>

        <div id="stepdiv4"
            class="totallyHide section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container settings-container-form">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">

                    <div class="card mb-0 cardClear">
                        <div class="card-body onboarding-card mt-2 d-flex justify-content-center">
                            <div class="form-body w-100">
                                <form class="row g-3" method="POST" action="settings.php" enctype="multipart/form-data">
                                    <input type="text" name="step" value="4" hidden>
                                    <div class="scrollable-div">
                                        <div class="col-12 mb-2">
                                            <div class=" mb-2">
                                                <h4>Customizations</h4>
                                                <p class="main-label">Manage your customizations.</p>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <h5 class="mb-2 text-white fw-normal">Choose a theme.</h5>

                                                <div class="toggle-switch-container mt-2">
                                                    <span class="toggle-text-left">Light</span>
                                                    <div class="toggle-switch">
                                                        <input type="checkbox" id="themeToggle" class="toggle-checkbox">
                                                        <label for="themeToggle" class="toggle-label">
                                                            <span class="toggle-slider"></span>
                                                        </label>
                                                    </div>
                                                    <span class="toggle-text-right">Dark</span>
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <p class="text-white ">Choose a background for your app from the
                                                        options below.</p>
                                                    <div class="square-container">
                                                        <div class="square">
                                                            <input type="checkbox" name="background" value="1150" hidden
                                                                checked>
                                                            <img src="assets/images/bg-themes/Summer.jpg" alt="Summer"
                                                                class="square-image">
                                                        </div>
                                                        <div class="square">
                                                            <input type="checkbox" name="background" value="1151"
                                                                hidden>
                                                            <img src="assets/images/bg-themes/Autumn.jpg" alt="Autumn"
                                                                class="square-image">
                                                        </div>
                                                        <div class="square">
                                                            <input type="checkbox" name="background" value="1152"
                                                                hidden>
                                                            <img src="assets/images/bg-themes/Winter.jpg" alt="Winter"
                                                                class="square-image">
                                                        </div>
                                                        <div class="square">
                                                            <input type="checkbox" name="background" value="1153"
                                                                hidden>
                                                            <img src="assets/images/bg-themes/Spring.jpg" alt="Spring"
                                                                class="square-image">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-12 mt-3">
                                                    <p class="text-white">Choose an accent color for your app from the
                                                        options below.</p>


                                                    <div class="row toggle-switch-container">
                                                        <div class="col-2 accent">

                                                            <input type="radio" name="accent" hidden value="1150"
                                                                checked>
                                                            <div class="letter-square text-white accent selected"
                                                                mode="light" tag="rgba(0,0,0,0.6)"
                                                                style="background-color:rgba(0, 0, 0, 0.6);">A</div>
                                                        </div>
                                                        <div class="col-2 accent">

                                                            <input type="radio" name="accent" hidden value="1142">
                                                            <div class="letter-square  text-white accent" mode="light"
                                                                tag="rgba(140, 69, 21, 0.80)"
                                                                style="background-color: #8C4515;">A</div>
                                                        </div>
                                                        <div class="col-2 accent">

                                                            <input type="radio" name="accent" hidden value="1143">
                                                            <div class="letter-square  text-white accent" mode="light"
                                                                tag="rgba(13, 99, 62, 0.80)"
                                                                style="background-color: #0D633E;">A</div>
                                                        </div>
                                                        <div class="col-2 accent">

                                                            <input type="radio" name="accent" hidden value="1144">
                                                            <div class="letter-square  text-white accent" mode="light"
                                                                tag=" rgba(34, 68, 191, 0.80)"
                                                                style="background-color: #2244BF;">A</div>
                                                        </div>
                                                        <div class="col-2 accent">

                                                            <input type="radio" name="accent" hidden value="1145">
                                                            <div class="letter-square  text-white accent" mode="light"
                                                                tag="rgba(120, 47, 193, 0.80)"
                                                                style="background-color: #782FC1;">A</div>
                                                        </div>
                                                        <div class="col-2 accent">

                                                            <input type="radio" name="accent" hidden value="1146">
                                                            <div class="letter-square text-black accent" mode="dark"
                                                                tag="rgba(255, 228, 189, 0.70)"
                                                                style="background-color: rgb(255, 228, 189);">A</div>
                                                        </div>
                                                        <div class="col-2 accent">

                                                            <input type="radio" name="accent" hidden value="1150">
                                                            <div class="letter-square  text-white accent" mode="dark"
                                                                tag="rgba(255, 228, 189, 0.70)"
                                                                style="background-color: #FFC87A;">A</div>
                                                        </div>
                                                        <div class="col-2 accent">

                                                            <input type="radio" name="accent" hidden value="1146">
                                                            <div class="letter-square text-dark accent" mode="dark"
                                                                tag="rgba(182, 255, 202, 0.70)"
                                                                style="background-color: #83FBA5;">A</div>
                                                        </div>
                                                        <div class="col-2 accent">

                                                            <input type="radio" name="accent" hidden value="1148">
                                                            <div class="letter-square  text-white accent" mode="dark"
                                                                tag="rgba(185, 234, 255, 0.70)"
                                                                style="background-color: #89CAE6;">A</div>
                                                        </div>
                                                        <div class="col-2 accent">

                                                            <input type="radio" name="accent" hidden value="1149">
                                                            <div class="letter-square  text-white accent" mode="dark"
                                                                tag="rgba(227, 199, 255, 0.70)"
                                                                style="background-color: #D6B7F5;">A</div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                        </div>

                                        <div class="col-12">
                                            <p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
                                            <p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
                                        </div>



                                    </div>
                                    <input type="submit" hidden id="accountCustom" />

                                </form>
                            </div>
                            <div class="d-flex justify-content-center settings-btn">
                                <div class="d-flex justify-content-start col-6">
                                    <button type="button" onclick="back(1);"
                                        class="btn btn-transpaernt text-white btn-next">Cancel</button>
                                </div>

                                <div class="d-flex justify-content-end  col-5">
                                    <button type="submit" class="btn btn-white btn-next" tag="accountCustom">Save
                                        Changes</button>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
            <!--end row-->
        </div>

        <div id="stepdiv5"
            class="totallyHide section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container settings-container-form">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">

                    <div class="card mb-0 cardClear">
                        <div class="card-body onboarding-card mt-2 mt-2 d-flex justify-content-center">
                            <div class="form-body w-100">
                                <form class="row g-3">
                                    <div class="scrollable-div">
                                        <div class="col-12 mb-2">
                                            <div class=" mb-2">
                                                <h4>Guest Administrator</h4>
                                            </div>
                                            <div class="col-12">
                                                <label for="inputCollection" class="form-label text-white">You have made
                                                    the following connection a guest administrator for this
                                                    account:</label>
                                                <select class="form-select border-white" id="guest" name="guest">
                                                    <option selected><?php echo $_SESSION['guestadmin']; ?></option>
                                                </select>
                                                <br>
                                                <h6>Note: You may have only one guest administrator per account.</h6>
                                            </div>
                                            <hr>
                                            <div class="col-12">
                                                <label for="inputCollection" class="form-label text-white">You are a
                                                    guest administrator for the following accounts:</label>
                                                <div class="accordion" id="accordionExample">


                                                    <?php
													$counter = 1;
													foreach ($resp_json as &$guestAdmin) {
														if (!isset($guestAdmin['emailAddress'])) {
															break;
														}

														$emailaddress = $guestAdmin['emailAddress'] ?? null;
														$name = $guestAdmin['fullName'] ?? null;
														$uniqueId = 'guest_' . $counter;

														echo '
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading' . htmlspecialchars($uniqueId) . '">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . htmlspecialchars($uniqueId) . '" aria-expanded="false" aria-controls="collapse' . htmlspecialchars($uniqueId) . '">
                    Backup Account
                </button>
            </h2>
            <div id="collapse' . htmlspecialchars($uniqueId) . '" class="accordion-collapse collapse" aria-labelledby="heading' . htmlspecialchars($uniqueId) . '" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <h6>Primary administrator:<br> ' . htmlspecialchars($name) . '</h6>
                    <div class="col-12 mb-3">
                        <label for="inputEmailAddress" class="form-label text-white">Email<sup>*</sup></label>
                        <span class="mb-2">
                            <input type="email" class="form-control border-white" id="inputEmailAddress" name="inputEmailAddress" placeholder="name@domain" value="' . htmlspecialchars($emailaddress) . '" required>
                        </span>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="inputPassword" class="form-label text-white">Password<sup>*</sup></label>
                        <span class="mb-2">
                            <input type="password" class="form-control border-white" id="inputPassword" name="inputPassword" placeholder="Password" required>
                        </span>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="inputConfirmPassword" class="form-label text-white">Confirm Password<sup>*</sup></label>
                        <span class="mb-2">
                            <input type="password" class="form-control border-white" id="inputConfirmPassword" name="inputConfirmPassword" placeholder="Confirm password" required>
                        </span>
                    </div>
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <button class="text-center btn btn-white"> Switch to this account</button>
                    </div>
                </div>
            </div>
        </div>';

														$counter++;
													}
													?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
                                            <p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
                                        </div>



                                    </div>
                                    <input type="submit" hidden id="accountGuest" />

                                </form>
                            </div>
                            <div class="d-flex justify-content-center settings-btn">
                                <div class="d-flex justify-content-start col-6">
                                    <button type="button" onclick="back(1);"
                                        class="btn btn-transpaernt text-white btn-next">Cancel</button>
                                </div>

                                <div class="d-flex justify-content-end  col-5">
                                    <button type="button" tag="accountGuest" class="btn btn-white btn-next">Save
                                        Changes</button>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
            <!--end row-->
        </div>

        <div id="stepdiv6"
            class="totallyHide section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container settings-container-form">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">

                    <div class="card mb-0 cardClear">
                        <div class="card-body onboarding-card mt-2 d-flex justify-content-center">
                            <div class="form-body w-100">
                                <form class="row g-3">
                                    <div class="scrollable-div">
                                        <div class="col-12 mb-2">
                                            <div class=" mb-2">
                                                <h4>Tags</h4>
                                                <p class="text-white">You have created the following tags:</p>
                                            </div>

                                            <div class="col-12 mb-2 row">
                                                <div class="col-12 mb-2 d-flex align-items-center">
                                                    <input type="text" class="form-control border-white me-2"
                                                        id="inputFirstName_acc" name="inputFirstName_acc"
                                                        placeholder="Tag" required>
                                                    <button type="button" class="btn btn-transparent btn-sm">
                                                        <i class="bi bi-trash text-white"></i>
                                                    </button>
                                                </div>


                                                <div class="col-12 mb-2 d-flex align-items-center">
                                                    <input type="text" class="form-control border-white me-2"
                                                        id="inputMiddletName_acc" name="inputMiddletName_acc"
                                                        placeholder="Tag Sample">
                                                    <button type="button" class="btn btn-transparent btn-sm">
                                                        <i class="bi bi-trash text-white"></i>
                                                    </button>
                                                </div>


                                                <div class="col-12 mb-2 d-flex align-items-center">
                                                    <input type="text" class="form-control border-white me-2"
                                                        id="inputLastName_acc" name="inputLastName_acc"
                                                        placeholder="Tag Sample" required>
                                                    <button type="button" class="btn btn-transparent btn-sm">
                                                        <i class="bi bi-trash text-white"></i>
                                                    </button>
                                                </div>
                                            </div>



                                        </div>

                                        <div class="col-12">
                                            <p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
                                            <p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
                                        </div>



                                    </div>
                                    <input type="submit" hidden id="accountGuest" />
                                </form>
                            </div>
                            <div class="d-flex justify-content-center settings-btn">
                                <div class="d-flex justify-content-start col-6">
                                    <button type="button" onclick="back(1);"
                                        class="btn btn-transpaernt text-white btn-next">Cancel</button>
                                </div>

                                <div class="d-flex justify-content-end  col-5">
                                    <button type="button" tag="accountGuest" class="btn btn-white btn-next">Save
                                        Changes</button>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
            <!--end row-->
        </div>

        <div class="page-wrapper">
            <div class="page-content">
                <div class="main-body">


                    <div class="settings-options" id="settings-options">
                        <div class="setings-container mb-2">
                            <div class="headingTabTitle">
                                <img id="stept1-trigger" src="assets/images/icons/settings.svg" alt="Open Setting"
                                    class="img-fluid open-settings">
                                <h5 class="settings-title">Application Settings</h5>
                            </div>
                        </div>
                        <div class="setings-container mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">User Settings</h5>
                                <img id="stept1-trigger" src="assets/images/icons/open.svg" alt="Open Setting"
                                    class="img-fluid open-settings">
                            </div>
                            <p class="mt-2 main-label">Manage your login information.</p>
                        </div>

                        <div class="setings-container mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Account Profile</h5>
                                <img id="stept2-trigger" src="assets/images/icons/open.svg" alt="Open Setting"
                                    class="img-fluid open-settings">
                            </div>
                            <p class="mt-2 main-label">Manage the profile information for the subject of this account.
                            </p>
                        </div>

                        <div class="setings-container mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Privacy</h5>
                                <img id="stept3-trigger" src="assets/images/icons/open.svg" alt="Open Setting"
                                    class="img-fluid open-settings">
                            </div>
                            <p class="mt-2 main-label">Manage who can view your timeline and family tree.</p>
                        </div>

                        <div class="setings-container mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Customizations</h5>
                                <img id="stept4-trigger" src="assets/images/icons/open.svg" alt="Open Setting"
                                    class="img-fluid open-settings">
                            </div>
                            <p class="mt-2 main-label">Manage customizations like theme and background.</p>
                        </div>

                        <div class="setings-container mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Guest Administrator</h5>
                                <img id="stept5-trigger" src="assets/images/icons/open.svg" alt="Open Setting"
                                    class="img-fluid open-settings">
                            </div>
                            <p class="mt-2 main-label">Select a guest administrator for this account and see the account
                                for which you are a guest administrator.</p>
                        </div>

                        <div class="setings-container mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Tags</h5>
                                <img id="stept6-trigger" src="assets/images/icons/open.svg" alt="Open Setting"
                                    class="img-fluid open-settings">
                            </div>
                            <p class="mt-2 main-label">Manage the tags used to associate memories on the timeline.</p>
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
    function showStep(stepNumber) {
        document.querySelectorAll('[id^="stepdiv"]').forEach(step => step.classList.add('totallyHide'));
        document.getElementById('settings-options').style.display = 'none';
        document.getElementById(`stepdiv${stepNumber}`).classList.remove('totallyHide');
    }


    document.querySelectorAll('[id^="stept"]').forEach(trigger => {

        trigger.addEventListener('click', function() {
            const stepNumber = this.id.match(/\d+/)[0];
            showStep(stepNumber);
        });
    });

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
    document.querySelectorAll('.square').forEach(square => {
        square.addEventListener('click', () => {
            const checkbox = square.querySelector('input[type="checkbox"]');
            document.querySelectorAll('.square input[type="checkbox"]').forEach(cb => cb.checked =
                false);
            checkbox.checked = true;
        });
    });

    document.querySelectorAll('.accent').forEach(accent => {
        accent.addEventListener('click', () => {
            const radio = accent.querySelector('input[type="radio"]');
            document.querySelectorAll('.accent input[type="radio"]').forEach(cb => cb.checked = false);
            radio.checked = true;
        });
    });

    document.querySelectorAll('.letter-square').forEach((img) => {
        img.addEventListener('click', function() {
            const color = this.getAttribute('tag');
            const accentGroups = document.querySelectorAll('.accent-group');
            accentGroups.forEach(element => {
                element.style.background = color;
            });

            document.querySelectorAll('.onboarding-card').forEach(element => {
                element.style.background = color;
            });

            document.querySelectorAll('.accent-group').forEach(element => {
                element.style.background = color;
            });

            document.querySelectorAll('.setings-container').forEach(element => {
                element.style.background = color;
            });

            const elementsToChange = document.querySelectorAll('.text-element');
            elementsToChange.forEach((el) => {
                el.style.color = 'black';
            });
            const footer = document.querySelectorAll('.page-footer');
            footer.forEach((el) => {
                el.style.color = 'black';
            });
            // 
        });
    });


    $(document).ready(function() {
        $("#show_hide_password a").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("bx-hide");
                $('#show_hide_password i').removeClass("bx-show");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("bx-hide");
                $('#show_hide_password i').addClass("bx-show");
            }
        });
    });

    document.querySelectorAll('button[tag]').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const tag = this.getAttribute('tag');
            const targetInput = document.getElementById(tag);
            if (targetInput) {
                targetInput.click();
                targetInput.focus();
            } else {
                console.error(`No input found with ID: ${tag}`);
            }
        });
    });
    </script>
</body>

</html>