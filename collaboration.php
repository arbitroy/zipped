<?php

include 'global.php';

$tr = $tr_pending = '';

$country = mapOptionSets('COUNTRY');

$state = mapOptionSets('STATES');

$viewability = mapOptionSets('VIEW_RIGHT_CONNECTION');

$lifeLayers = mapOptionSets('LIFE_LAYERS');

$response = makeGetAPICall('getConnections', $_SESSION["token"]);

$resp_json = json_decode($response, true);

$family = '';
$approved = "false";

if (sizeof($resp_json) > 0 && isset($resp_json[0]['InviteStatus'])) {

	foreach ($resp_json as $item) {
		$item['InviteStatus'] == 'Accepted' ?
			$tr .= '<tr>
		<td>' . $item['PersonName'] . '</td>
		<td>' . $item['Relationship'] . '</td>
		</tr>' : $tr_pending .= '<tr>
		<td>' . $item['PersonName'] . '</td>
		<td>' . $item['Relationship'] . '</td>
		</tr>';
	}

	$family = '<select class="form-select" id="family" name="family">
    <option>Select Option</option>';

	foreach ($resp_json as $item) {
		$item['connectionType'] == 'Family' ?
			$family .= '<option value="' . $item['userId'] . '">' . $item['PersonName'] . '</option>'
			: $family .= '';
	}

	$family .= '</select>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$city_ = $_POST['city_'];
	$country_ = $_POST['COUNTRY'];
	$state_ = $_POST['STATES'];
	// $lifelayer_ = $_POST['LIFE_LAYERS'];
	$lifelayer_ = "";
	// $viewability_ = $_POST['VIEW_RIGHT_CONNECTION'];
	$viewability_ = "friend";
	$date_origi = $_POST['date_'];
	$date_ = "2024-04-27T21:00:00.000Z";
	$memoryname = $_POST['memoryname'];
	$memorytext = $_POST['memorytext'];
	$specificconnection = "";
	$taggedmember = $_POST['taggedmember'];

	// convert date to desired format 2024-04-27T21:00:00.000Z
	// Unix time = 1685491200
	$unixTime = strtotime($date_origi);

	// Pass the new date format as a string and the original date in Unix time
	$date_ = date("Y-m-d\TH:i:s.v\Z", $unixTime);

	/* "city": "Istanbul",
    "country": "1309",
    "date": "2024-04-27T21:00:00.000Z",
    "lifelayer": "1093",
    "memoryImage": "iVBORw0KGgoAAAANSUhEUgAAAsQAAAHYCAIAAADFy2mpAAAAG",
    "memoryImageExtension": "png",
    "memoryname": "Anniversary",
    "memorytext": "This was our 3rd anniversary celebration",
    "specificconnection": "",
    "state": "1082",
    "taggedmember": "",
    "viewability": "1128" */

	$memoryImageExtension = $memoryImage = '';
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

	if ($check !== false) {
		$memoryImage = base64_encode(file_get_contents($_FILES["fileToUpload"]["tmp_name"]));
		// $memoryImageExtension = $check["mime"];
		$memoryImageExtension = end((explode(".", $_FILES["fileToUpload"]["name"])));
		// $memoryImageExtension = "png";
		// echo "copy + paste the data below, use it as a string in ur JavaScript Code<br><br>";
		// echo "<textarea id='data' style=''>data:" . $check["mime"] . ";base64," . $data . "</textarea>";
	}

	$body = json_encode(
		array(
			"city" => $city_,
			"country" => $country_,
			"date" => $date_,
			"familytreeid" => $lifelayer_,
			"memoryImage" => $memoryImage,
			"memoryImageExtension" => $memoryImageExtension,
			"memoryname" => $memoryname,
			"memorytext" => $memorytext,
			// "specificconnection" => $specificconnection,
			"state" => $state_,
			"touserid" => $taggedmember,
			"typeofrequest" => $viewability_
		)
	);

	// echo $body;

	$response = makePostAPIcall('memoryAddSuggestion', $body, $_SESSION["token"]);

	// echo $response;

	// $resp_json = json_decode($response, true);

	// echo '<script type="text/JavaScript">  
	// round_success_noti(); 
	//  </script>';

	// if (array_key_exists("success", $resp_json)) {
	// 	echo '<script type="text/JavaScript">  
	// 	alert("Success"); 
	// 	 </script>';
	// } else {
	// 	echo '<script type="text/JavaScript">  
	// 	alert("An Error occured"); 
	// 	 </script>';
	// }

	// header("Refresh:0");
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
	<link href="assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css" rel="stylesheet" />
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<link href="assets/plugins/datetimepicker/css/classic.css" rel="stylesheet" />
	<link href="assets/plugins/datetimepicker/css/classic.time.css" rel="stylesheet" />
	<link href="assets/plugins/datetimepicker/css/classic.date.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css">
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">

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
				<div class="row">

					<div class="container">
						<div class="main-body">
							<!-- Has add media -->
							<div class="timeline-form-container totallyHide" id="response">
								<div class=" row row-cols-1 row-cols-lg-2 row-cols-xl-3 collaboration-form-container">
									<div class="card mb-0 cardClear w-100" style="height:100vH !important;">
										<div class="card-body">
											<div class="form-body">
												<form class="row g-3 builderForm " id="birthday">
													<div>

														<div class="row mt-1 mx-2 my-2">
															<div class="text-start mb-2 col-9">
																<h6 class="fw-normal fs-5">Collaboration</h6>
																<h6 class="fw-normal fs-6">Response</h6>
															</div>
															<div class="text-end mb-2 col-3">
																<i class="bi bi-trash text-white"></i>
															</div>
														</div>
														<div class="row mt-1 mx-2 my-2 memtabs">
															<div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
																onclick="activateTab(this)" tag="response-remember">
																<h6 class="fw-normal fs-6">Remember</h6>
															</div>
															<div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
																onclick="activateTab(this)" tag="response-share">
																<h6 class="fw-normal fs-6">Share</h6>
															</div>
															<div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
																onclick="activateTab(this)" tag="response-collab">
																<h6 class="fw-normal fs-6">Collaborate</h6>
															</div>
														</div>
														<div class="col-12 mb-2 tabgroup">
															<div class="tabsect response-remember">
																<span class="mb-2 text-white fs-6" id="name-label">Kim Ashbaugh suggested the following memory for your timeline.</span>
																<div class="mt-4 mb-4">
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Event:</b> Our Honeymoon</span>
																	</div>
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Date:</b> 07/01/1990</span>
																	</div>
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Location:</b> Pacofoc Grove, California (USA)</span>
																	</div>
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Kim's Memory:</b></span>
																		<p>
																			After the wedding, we drove down the coast to Pacific Grove, a cute little town south of Monterey. We stayed in a gorgeous old Queen Anne that had been converted into a BnB. <br /><br />
																			One of the special things we did was go down to the beach at Carmel to watch the sunset. I loved the quaint architecture of that town
																		</p>
																	</div>
																</div>

																<div class="col-12 mb-3">
																	<span class="mb-2 text-white"> Add media to your memory.</span>
																	<div class="col-12">
																		<input id="birthPic" class="form-control border-white" name="birthPic"
																			type="file" accept=".jpg, .jpeg, .png" name=""
																			onchange="previewImage(this, 'previewbirthPic', 'birthfileName');">
																		<div class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
																			<img id="previewbirthPic" src="" alt="Profile Preview">
																			<span id="birthfileName" class="text-white"></span>
																		</div>
																	</div>
																</div>
																<div class="col-12 hideOnMobile" style="cursor:pointer;">
																	<div class="text-center text-white"><i
																			class="bi bi-camera-video text-white mx-2"></i>Record a video message.
																	</div>
																</div>
															</div>

															<div class="tabsect response-share totallyHide">
																<span class="mb-2 text-white fs-6" id="name-label">Choose the audience for this
																	memory. Who can see it?</span>
																<div class="col-12 mb-3 mt-3">
																	<label class="cbContainer">
																		<input type="radio" name="audience" value="family" checked>
																		<span class="checkmark"></span>
																		<div class="text">
																			<div class="title">Only family (default)</div>
																		</div>
																	</label>

																</div>
																<div class="col-12 mb-3">
																	<label class="cbContainer">
																		<input type="radio" name="audience" value="connections">
																		<span class="checkmark"></span>
																		<div class="text">
																			<div class="title">All connections</div>
																		</div>
																	</label>

																</div>

																<div class="col-12 mb-3">
																	<label class="cbContainer">
																		<input type="radio" name="audience" value="anyone">
																		<span class="checkmark"></span>
																		<div class="text">
																			<div class="title">Anyone (public)</div>
																		</div>
																	</label>

																</div>

																<div class="col-12 mb-3">
																	<label class="cbContainer">
																		<input type="radio" name="audience" value="custom">
																		<span class="checkmark"></span>
																		<div class="text">
																			<div class="title">Custom</div>
																		</div>
																	</label>

																</div>
															</div>
															<div class="tabsect response-collab totallyHide">
																<span class="mb-2 text-white fs-6" id="name-label">Invite existing connections to
																	contribute to this memory.</span>
																<div class="row mt-1 mx-2 my-2 align-items-center">
																	<div class="text-start col-1 d-flex justify-content-center"
																		style="cursor: pointer;">
																		<i class="bi bi-search text-white fs-5"></i>
																	</div>
																	<div class="text-end col-11">
																		<input type="text" class="form-control border-white" id="searchParam"
																			name="searchParam" placeholder="Search" required>
																	</div>
																	<div class="col-12 mt-3">
																		<div class="collab-name d-flex justify-content-center gap-2 px-3 py-2">
																			<i class="bi bi-person text-white text-start"
																				style="cursor: pointer;"></i>
																			<div class="text-center">Name 1</div>
																			<i class="bi bi-x text-white text-end" style="cursor: pointer;"></i>
																		</div>
																	</div>
																</div>
																<form class="row g-3 w-100">
																	<div class="scrollable-div col-12 w-100 mt-2">
																		<div class="col-12 mb-2 w-100">
																			<div class="mb-3 d-flex align-items-center justify-content-between">
																				<h6 class="mb-0">Invite a new connection to contribute.</h6>
																			</div>


																			<div class="col-12 mb-2">
																				<span class="mb-2 text-white">Name? <sup>*</sup></span>
																				<input type="text" class="form-control border-white mb-2"
																					id="birthinputCollabFirstName" name="birthinputCollabFirstName"
																					placeholder="First name" required>
																				<input type="text" class="form-control border-white mb-2"
																					id="birthinputCollabLastName" name="birthinputCollabLastName"
																					placeholder="Last name" required>
																			</div>

																			<div class="col-12 mb-2">
																				<span class="mb-2 text-white"><i>Email <sup>*</sup></i></span>
																				<input type="email" class="form-control border-white mb-2"
																					id="birthinputCollabEmail" name="inputEmail"
																					placeholder="name@email.com" required>
																			</div>

																			<div class="col-12">
																				<span class="mb-2 text-white"><i>Relationship
																						<sup>*</sup></i></span>
																				<select class="form-select border-white mb-2 text-white bg-dark"
																					id="birthinputCollabRelation" name="birthinputCollabRelation"
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
																				<span class="mb-2 text-white" id="shortDescription">Share some
																					details.</span>
																				<textarea class="form-control border-white mb-2 text-white"
																					id="birthCollabDetails" name="birthCollabDetails"
																					placeholder="Optional" rows="3"></textarea>
																				<div class="text-end">
																					<span id="birthCollabDetailsCount" class="text-white">0 of
																						500</span>
																				</div>
																			</div>


																		</div>
																	</div>

																</form>
															</div>


															<div class="col-12">
																<p class="mb-0 text-danger" id="passwordError">
																	<?php echo $error; ?>
																</p>
																<p class="mb-0 text-success" id="txtMessage">
																	<?php echo $message; ?>
																</p>
															</div>
														</div>

														<div class="row mt-1 mx-2 my-2 position-relative">
															<div class="text-center mb-2 col-12">
																<button type="button" class="btn btn-white" onclick="toggleDropdown('response')">
																	Save
																	<i class="bi bi-caret-down"></i>
																</button>
																<div id="saveDropdown-response"
																	class="dropdown-menu-tm position-absolute bg-white shadow"
																	style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
																	<button class="dropdown-item-tm" onclick="saveAndContinue('response')">Save &
																		Continue</button>
																	<button class="dropdown-item-tm" onclick="saveAndClose()">Save & Close</button>
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
							<!-- End add media -->

							<!-- Collaboration request -->
							<div class="timeline-form-container totallyHide" id="request">
								<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 collaboration-form-container">
									<div class="card mb-0 cardClear w-100" style="height:100vH !important;">
										<div class="card-body">
											<div class="form-body">
												<form class="row g-3 builderForm " id="birthday">
													<div>

														<div class="row mt-1 mx-2 my-2">
															<div class="text-start mb-2 col-9">
																<h6 class="fw-normal fs-5">Collaboration</h6>
																<h6 class="fw-normal fs-6">Response</h6>
															</div>
															<div class="text-end mb-2 col-3">
																<i class="bi bi-trash text-white"></i>
															</div>
														</div>
														<div class="row mt-1 mx-2 my-2 memtabs">
															<div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
																onclick="activateTab(this)" tag="request-remember">
																<h6 class="fw-normal fs-6">Remember</h6>
															</div>
															<div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
																onclick="activateTab(this)" tag="request-share">
																<h6 class="fw-normal fs-6">Share</h6>
															</div>
															<div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
																onclick="activateTab(this)" tag="request-collab">
																<h6 class="fw-normal fs-6">Collaborate</h6>
															</div>
														</div>
														<div class="col-12 mb-2 tabgroup">
															<div class="tabsect request-remember">
																<span class="mb-2 text-white fs-6" id="name-label">Kim Ashbaugh suggested the following memory for your timeline.</span>
																<div class="mt-4 mb-4">
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Event:</b> Our Honeymoon</span>
																	</div>
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Date:</b> 07/01/1990</span>
																	</div>
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Location:</b> Pacofoc Grove, California (USA)</span>
																	</div>
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Kim's Memory:</b></span>
																		<p>
																			After the wedding, we drove down the coast to Pacific Grove, a cute little town south of Monterey. We stayed in a gorgeous old Queen Anne that had been converted into a BnB. <br /><br />
																			One of the special things we did was go down to the beach at Carmel to watch the sunset. I loved the quaint architecture of that town
																		</p>
																	</div>
																</div>
															</div>

															<div class="tabsect request-share totallyHide">
																<span class="mb-2 text-white fs-6" id="name-label">Choose the audience for this
																	memory. Who can see it?</span>
																<div class="col-12 mb-3 mt-3">
																	<label class="cbContainer">
																		<input type="radio" name="audience" value="family" checked>
																		<span class="checkmark"></span>
																		<div class="text">
																			<div class="title">Only family (default)</div>
																		</div>
																	</label>

																</div>
																<div class="col-12 mb-3">
																	<label class="cbContainer">
																		<input type="radio" name="audience" value="connections">
																		<span class="checkmark"></span>
																		<div class="text">
																			<div class="title">All connections</div>
																		</div>
																	</label>

																</div>

																<div class="col-12 mb-3">
																	<label class="cbContainer">
																		<input type="radio" name="audience" value="anyone">
																		<span class="checkmark"></span>
																		<div class="text">
																			<div class="title">Anyone (public)</div>
																		</div>
																	</label>

																</div>

																<div class="col-12 mb-3">
																	<label class="cbContainer">
																		<input type="radio" name="audience" value="custom">
																		<span class="checkmark"></span>
																		<div class="text">
																			<div class="title">Custom</div>
																		</div>
																	</label>

																</div>
															</div>
															<div class="tabsect request-collab totallyHide">
																<span class="mb-2 text-white fs-6" id="name-label">Invite existing connections to
																	contribute to this memory.</span>
																<div class="row mt-1 mx-2 my-2 align-items-center">
																	<div class="text-start col-1 d-flex justify-content-center"
																		style="cursor: pointer;">
																		<i class="bi bi-search text-white fs-5"></i>
																	</div>
																	<div class="text-end col-11">
																		<input type="text" class="form-control border-white" id="searchParam"
																			name="searchParam" placeholder="Search" required>
																	</div>
																	<div class="col-12 mt-3">
																		<div class="collab-name d-flex justify-content-center gap-2 px-3 py-2">
																			<i class="bi bi-person text-white text-start"
																				style="cursor: pointer;"></i>
																			<div class="text-center">Name 1</div>
																			<i class="bi bi-x text-white text-end" style="cursor: pointer;"></i>
																		</div>
																	</div>
																</div>
																<form class="row g-3 w-100">
																	<div class="scrollable-div col-12 w-100 mt-2">
																		<div class="col-12 mb-2 w-100">
																			<div class="mb-3 d-flex align-items-center justify-content-between">
																				<h6 class="mb-0">Invite a new connection to contribute.</h6>
																			</div>


																			<div class="col-12 mb-2">
																				<span class="mb-2 text-white">Name? <sup>*</sup></span>
																				<input type="text" class="form-control border-white mb-2"
																					id="birthinputCollabFirstName" name="birthinputCollabFirstName"
																					placeholder="First name" required>
																				<input type="text" class="form-control border-white mb-2"
																					id="birthinputCollabLastName" name="birthinputCollabLastName"
																					placeholder="Last name" required>
																			</div>

																			<div class="col-12 mb-2">
																				<span class="mb-2 text-white"><i>Email <sup>*</sup></i></span>
																				<input type="email" class="form-control border-white mb-2"
																					id="birthinputCollabEmail" name="inputEmail"
																					placeholder="name@email.com" required>
																			</div>

																			<div class="col-12">
																				<span class="mb-2 text-white"><i>Relationship
																						<sup>*</sup></i></span>
																				<select class="form-select border-white mb-2 text-white bg-dark"
																					id="birthinputCollabRelation" name="birthinputCollabRelation"
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
																				<span class="mb-2 text-white" id="shortDescription">Share some
																					details.</span>
																				<textarea class="form-control border-white mb-2 text-white"
																					id="birthCollabDetails" name="birthCollabDetails"
																					placeholder="Optional" rows="3"></textarea>
																				<div class="text-end">
																					<span id="birthCollabDetailsCount" class="text-white">0 of
																						500</span>
																				</div>
																			</div>


																		</div>
																	</div>

																</form>
															</div>


															<div class="col-12">
																<p class="mb-0 text-danger" id="passwordError">
																	<?php echo $error; ?>
																</p>
																<p class="mb-0 text-success" id="txtMessage">
																	<?php echo $message; ?>
																</p>
															</div>
														</div>

														<div class="row mt-1 mx-2 my-2 position-relative">
															<div class="text-start col-6">
																<button type="button" class="btn btn-transparent border border-white text-white txt-hover-white" onclick="saveAndContinue('request')">
																	Reject
																</button>
															</div>
															<div class="text-end mb-2 col-6">
																<button type="button" class="btn btn-white" onclick="toggleDropdown('request')">
																	Save
																	<i class="bi bi-caret-down"></i>
																</button>
																<div id="saveDropdown-request"
																	class="dropdown-menu-tm position-absolute bg-white shadow"
																	style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
																	<button class="dropdown-item-tm" onclick="saveAndContinue('request')">Add as new memory</button>
																	<button class="dropdown-item-tm" onclick="saveAndClose()">Add to existing memory</button>
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
							<!-- End collaboration request -->

							<!-- Start specific request -->
							<div class="timeline-form-container totallyHide" id="specificRequest">
								<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 collaboration-form-container">
									<div class="card mb-0 cardClear w-100" style="height:100vH !important;">
										<div class="card-body">
											<div class="form-body">
												<form class="row g-3 builderForm " id="birthday">
													<div>

														<div class="row mt-1 mx-2 my-2">
															<div class="text-start mb-2 col-9">
																<h6 class="fw-normal fs-5">Collaboration</h6>
																<h6 class="fw-normal fs-6">Response</h6>
															</div>
															<div class="text-end mb-2 col-3">
																<i class="bi bi-trash text-white"></i>
															</div>
														</div>
														<div class="row mt-1 mx-2 my-2 memtabs">
															<div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active"
																onclick="activateTab(this)" tag="specificRequest-remember">
																<h6 class="fw-normal fs-6">Remember</h6>
															</div>
															<div class="text-center d-flex align-items-center justify-content-center col-4 memtab"
																onclick="activateTab(this)" tag="specificRequest-share">
																<h6 class="fw-normal fs-6">Share</h6>
															</div>
															<div class="text-end d-flex align-items-center justify-content-center col-4 memtab"
																onclick="activateTab(this)" tag="specificRequest-collab">
																<h6 class="fw-normal fs-6">Collaborate</h6>
															</div>
														</div>
														<div class="col-12 mb-2 tabgroup">
															<div class="tabsect specificRequest-remember">
																<span class="mb-2 text-white fs-6" id="name-label">Doug suggested the following addition to your memory.</span>
																<div class="mt-4 mb-4">
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Event:</b> Camping in Sarnia</span>
																	</div>
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Date:</b> 07/01/1990</span>
																	</div>
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Location:</b> Sarnia, Ontario (Canada)</span>
																	</div>
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Memory:</b></span>
																		<p>
																			About a month before Doug married Liz and I married Kim, he and I embarked on our last adventure as bachelors—a short camping trip to Sarnia, Ontario.
																		</p>
																	</div>
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Doug's memory:</b></span>
																		<p class="their-memory-box ">
																			About a month before Doug married Liz and I married Kim, he and I embarked on our last adventure as bachelors—a short camping trip to Sarnia, Ontario.
																		</p>
																	</div>
																	<div class="mb-4">
																		<span class="fs-6 text-start text-white"> <b class="">Doug's media:</b></span>
																		<div class="their-media">
																			<img src="assets/images/bg-themes/Winter.jpg" alt="Winter" class="square-image">
																		</div>
																	</div>
																</div>
															</div>

															<div class="tabsect specificRequest-share totallyHide">
																<span class="mb-2 text-white fs-6" id="name-label">Choose the audience for this
																	memory. Who can see it?</span>
																<div class="col-12 mb-3 mt-3">
																	<label class="cbContainer">
																		<input type="radio" name="audience" value="family" checked>
																		<span class="checkmark"></span>
																		<div class="text">
																			<div class="title">Only family (default)</div>
																		</div>
																	</label>

																</div>
																<div class="col-12 mb-3">
																	<label class="cbContainer">
																		<input type="radio" name="audience" value="connections">
																		<span class="checkmark"></span>
																		<div class="text">
																			<div class="title">All connections</div>
																		</div>
																	</label>

																</div>

																<div class="col-12 mb-3">
																	<label class="cbContainer">
																		<input type="radio" name="audience" value="anyone">
																		<span class="checkmark"></span>
																		<div class="text">
																			<div class="title">Anyone (public)</div>
																		</div>
																	</label>

																</div>

																<div class="col-12 mb-3">
																	<label class="cbContainer">
																		<input type="radio" name="audience" value="custom">
																		<span class="checkmark"></span>
																		<div class="text">
																			<div class="title">Custom</div>
																		</div>
																	</label>

																</div>
															</div>
															<div class="tabsect specificRequest-collab totallyHide">
																<span class="mb-2 text-white fs-6" id="name-label">Invite existing connections to
																	contribute to this memory.</span>
																<div class="row mt-1 mx-2 my-2 align-items-center">
																	<div class="text-start col-1 d-flex justify-content-center"
																		style="cursor: pointer;">
																		<i class="bi bi-search text-white fs-5"></i>
																	</div>
																	<div class="text-end col-11">
																		<input type="text" class="form-control border-white" id="searchParam"
																			name="searchParam" placeholder="Search" required>
																	</div>
																	<div class="col-12 mt-3">
																		<div class="collab-name d-flex justify-content-center gap-2 px-3 py-2">
																			<i class="bi bi-person text-white text-start"
																				style="cursor: pointer;"></i>
																			<div class="text-center">Name 1</div>
																			<i class="bi bi-x text-white text-end" style="cursor: pointer;"></i>
																		</div>
																	</div>
																</div>
																<form class="row g-3 w-100">
																	<div class="scrollable-div col-12 w-100 mt-2">
																		<div class="col-12 mb-2 w-100">
																			<div class="mb-3 d-flex align-items-center justify-content-between">
																				<h6 class="mb-0">Invite a new connection to contribute.</h6>
																			</div>


																			<div class="col-12 mb-2">
																				<span class="mb-2 text-white">Name? <sup>*</sup></span>
																				<input type="text" class="form-control border-white mb-2"
																					id="birthinputCollabFirstName" name="birthinputCollabFirstName"
																					placeholder="First name" required>
																				<input type="text" class="form-control border-white mb-2"
																					id="birthinputCollabLastName" name="birthinputCollabLastName"
																					placeholder="Last name" required>
																			</div>

																			<div class="col-12 mb-2">
																				<span class="mb-2 text-white"><i>Email <sup>*</sup></i></span>
																				<input type="email" class="form-control border-white mb-2"
																					id="birthinputCollabEmail" name="inputEmail"
																					placeholder="name@email.com" required>
																			</div>

																			<div class="col-12">
																				<span class="mb-2 text-white"><i>Relationship
																						<sup>*</sup></i></span>
																				<select class="form-select border-white mb-2 text-white bg-dark"
																					id="birthinputCollabRelation" name="birthinputCollabRelation"
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
																				<span class="mb-2 text-white" id="shortDescription">Share some
																					details.</span>
																				<textarea class="form-control border-white mb-2 text-white"
																					id="birthCollabDetails" name="birthCollabDetails"
																					placeholder="Optional" rows="3"></textarea>
																				<div class="text-end">
																					<span id="birthCollabDetailsCount" class="text-white">0 of
																						500</span>
																				</div>
																			</div>


																		</div>
																	</div>

																</form>
															</div>


															<div class="col-12">
																<p class="mb-0 text-danger" id="passwordError">
																	<?php echo $error; ?>
																</p>
																<p class="mb-0 text-success" id="txtMessage">
																	<?php echo $message; ?>
																</p>
															</div>
														</div>

														<div class="row mt-1 mx-2 my-2 position-relative">

															<?php if ($approved): ?>

																<div class="text-center col-12">
																	<button type="button" class="btn btn-white border border-white  txt-hover-white" onclick="saveAndContinue('specificRequest')">
																		Close <i class="bi bi-x fw-bold"></i>
																	</button>
																</div>

															<?php else: ?>
																<div class="text-start col-6">
																	<button type="button" class="btn btn-transparent border border-white text-white txt-hover-white" onclick="saveAndContinue('specificRequest')">
																		Reject
																	</button>
																</div>
																<div class="text-end mb-2 col-6">
																	<button type="button" class="btn btn-white" onclick="toggleDropdown('specificRequest')">
																		Add to Timeline
																		<i class="bi bi-caret-down"></i>
																	</button>
																	<!-- <div id="saveDropdown-specificRequest"
																	class="dropdown-menu-tm position-absolute bg-white shadow"
																	style="top: -80%; left: 50%; transform: translateX(-50%); display: none;">
																	<button class="dropdown-item-tm" onclick="saveAndContinue('specificRequest')">Add as new memory</button>
																	<button class="dropdown-item-tm" onclick="saveAndClose()">Add to existing memory</button>
																</div> -->
																</div>
															<?php endif; ?>
														</div>

													</div>
												</form>
											</div>
										</div>
									</div>

								</div>
							</div>
							<!-- End specific request -->

							<!-- Start invite collaborator -->
							<div class="timeline-form-container totallyHide " id="collaborationInvite">
								<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 collaboration-form-container">
									<div class="card mb-0 cardClear w-100" style="height:100vH !important;">
										<div class="card-body">
											<div class="form-body">
												<form class="row g-3 builderForm " id="birthday">
													<div>

														<div class="row mt-1 mx-2 my-2">
															<div class="text-start mb-2 col-9">
																<h6 class="fw-normal fs-5 mb-2">Collaborate</h6>
																<h6 class="fw-normal fs-6">Invite the following to collaborate on the timeline for this memoir for Brian.</h6>
															</div>
														</div>

														<div class="col-12 mb-2 tabgroup">


															<span class="mb-2 text-white fs-6" id="name-label">Invite an existing connection to collaborate.</span>
															<div class="row mt-1 mx-2 my-2 align-items-center">
																<div class="text-start col-1 d-flex justify-content-center"
																	style="cursor: pointer;">
																	<i class="bi bi-search text-white fs-5"></i>
																</div>
																<div class="text-end col-11">
																	<input type="text" class="form-control border-white" id="searchParam"
																		name="searchParam" placeholder="Search" required>
																</div>
																<div class="col-12 mt-3">
																	<div class="collab-name d-flex justify-content-center gap-2 px-3 py-2">
																		<i class="bi bi-person text-white text-start"
																			style="cursor: pointer;"></i>
																		<div class="text-center">Name 1</div>
																		<i class="bi bi-x text-white text-end" style="cursor: pointer;"></i>
																	</div>
																</div>
															</div>
															<form class="row g-3 w-100">
																<div class="scrollable-div col-12 w-100 mt-2">
																	<div class="col-12 mb-2 w-100">
																		<div class="mb-3 d-flex align-items-center justify-content-between">
																			<h6 class="mb-0">Invite a new connection to contribute.</h6>
																		</div>


																		<div class="col-12 mb-2">
																			<span class="mb-2 text-white">Name <sup>*</sup></span>
																			<input type="text" class="form-control border-white mb-2"
																				id="birthinputCollabFirstName" name="birthinputCollabFirstName"
																				placeholder="First name" required>
																			<input type="text" class="form-control border-white mb-2"
																				id="birthinputCollabLastName" name="birthinputCollabLastName"
																				placeholder="Last name" required>
																		</div>

																		<div class="col-12 mb-2">
																			<span class="mb-2 text-white">Email <sup>*</sup></span>
																			<input type="email" class="form-control border-white mb-2"
																				id="birthinputCollabEmail" name="inputEmail"
																				placeholder="name@email.com" required>
																		</div>

																		<div class="col-12">
																			<span class="mb-2 text-white">Relationship
																				<sup>*</sup></span>
																			<select class="form-select border-white mb-2 text-white bg-dark"
																				id="birthinputCollabRelation" name="birthinputCollabRelation"
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
																			<span class="mb-2 text-white" id="shortDescription">Message</span>
																			<textarea class="form-control border-white mb-2 text-white"
																				id="birthCollabDetails" name="birthCollabDetails"
																				placeholder="Optional" rows="3"></textarea>
																		</div>


																	</div>
																</div>

															</form>


															<div class="col-12">
																<p class="mb-0 text-danger" id="passwordError">
																	<?php echo $error; ?>
																</p>
																<p class="mb-0 text-success" id="txtMessage">
																	<?php echo $message; ?>
																</p>
															</div>
														</div>

														<div class="row mt-1 mx-2 my-2 position-relative">

															<div class="text-start col-6">
																<button type="button" class="btn btn-transparent border border-white text-white txt-hover-white" onclick="saveAndContinue('collaborationInvite')">
																	Cancel
																</button>
															</div>
															<div class="text-end mb-2 col-6">
																<button type="button" class="btn btn-white" onclick="saveAndContinue('collaborationInvite')">
																	Send <img src="assets/images/icons/send.png" alt="Back" style="margin-left:5px !important; ">
																</button>

															</div>
														</div>

													</div>
												</form>
											</div>
										</div>
									</div>

								</div>
							</div>
							<!-- End invite collaborator -->

							<div class="collaborations-header">
								<div class="row mt-1 mx-2 my-2">
									<div class="text-start mb-2 col-7">
										<h6 class="fw-normal fs-4"><img src="assets/images/icons/collaboration.png" alt="Back" style="height: 1em; vertical-align: middle; margin-right:10px;">Collaborations</h6>
									</div>
									<div class="d-flex justify-content-end text-end mb-2 col-5">
										<button type="button" onclick="showStepInvite(4);" class="btn btn-white text-black btn-next d-flex justify-content-center align-items-center"><img src="assets/images/icons/send.png" alt="Back" style="margin-left:5px !important; ">Invite </button>
									</div>
								</div>

								<div class="row mt-1 mx-2 my-2">
									<div class="text-start mb-2 col-6">
										<button type="button" id="btn-received" class="btn btn-white text-black btn-status border border-white" onclick="toggleButtonStyles(this)">Received </button>
									</div>
									<div class="text-end mb-2 col-6">
										<button type="button" id="btn-sent" class="btn btn-transparent text-white btn-status border border-white" onclick="toggleButtonStyles(this)">Sent </button>
									</div>
								</div>


								<div class="row mt-1 mx-2 my-2 align-items-center">
									<div class="text-start col-1 d-flex justify-content-center" style="cursor: pointer;">
										<i class="bi bi-search text-white fs-5"></i>
									</div>
									<div class="text-end col-11">
										<input type="text" class="form-control border-white" id="searchParam" name="searchParam" placeholder="Search" required>
									</div>
								</div>

							</div>

							<div id="stepinvite" class="totallyHide connection-container-form">
								<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
									<div class="form-body w-100">
										<form class="row g-3 w-100">
											<div class="scrollable-div w-100">
												<div class="col-12 mb-2 w-100">
													<div class="mb-2 d-flex align-items-center justify-content-between">
														<h6 class="mb-0">Edit connection</h6>
														<div>
															<i class="bi bi-send me-2" style="cursor: pointer;"></i>
															<i class="bi bi-trash" style="cursor: pointer;"></i>
														</div>
													</div>

													<h6 class="mb-0">Joined: 00/00/0000</h6>
													<div class="col-12 mb-2">
														<span class="mb-2 text-white">Name? <sup>*</sup></span>
														<input type="text" class="form-control border-white mb-2" id="inputGuestFirstName" name="inputGuestFirstName" placeholder="First name" required>
														<input type="text" class="form-control border-white mb-2" id="inputGuestLastName" name="inputGuestLastName" placeholder="Last name" required>
													</div>

													<div class="col-12 mb-2">
														<span class="mb-2 text-white"><i>Email <sup>*</sup></i></span>
														<input type="email" class="form-control border-white mb-2" id="inputEmail" name="inputEmail" placeholder="name@email.com" required>
													</div>

													<div class="col-12">
														<span class="mb-2 text-white"><i>Relationship <sup>*</sup></i></span>
														<select class="form-select border-white mb-2 text-white bg-dark" id="inputGuestRelation" name="inputGuestRelation" required>
															<option value="" disabled selected>Choose a relationship</option>
															<option value="sibling">Sibling</option>
															<option value="partner">Partner</option>
															<option value="other">Other</option>
														</select>
													</div>

													<div class="col-12 mb-3">
														<label class="cbContainer">
															<input type="radio" name="role" value="collaborator">
															<span class="checkmark"></span>
															<div class="text">
																<div class="title">Collaborator</div>
																<div class="description">Note: Collaborators can suggest memories for the timeline.</div>
															</div>
														</label>

													</div>

													<div class="col-12 mb-3">
														<label class="cbContainer">
															<input type="radio" name="role" value="guestAdmin">
															<span class="checkmark"></span>
															<div class="text">
																<div class="title">Guest administrator</div>
																<div class="description">Warning: Guest administrators have the ability to make changes to any data or setting in this application.</div>
															</div>
														</label>

													</div>
												</div>

												<div class="col-12">
													<p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
													<p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
												</div>
												<div class="row my-3">
													<div class="text-start col-6">
														<button type="button" onclick="validate(1);" class="btn btn-transpaernt text-white btn-next">Cancel</button>
													</div>

													<div class="text-end  col-6">
														<button type="button" onclick="validate(1);" class="btn btn-white btn-next">Save Changes</button>
													</div>
												</div>


											</div>

										</form>
									</div>

								</div>
							</div>
							<div id="received-tab">
								<div class="p-3 collaboration-card">
									<div class="row align-items-center">
										<div class="col-3">
											<img src="https://placehold.co/600x400/png" alt="Profile" class="rounded-circle" width="80" height="80">
										</div>
										<div class="col-9">
											<h5 class="mb-0">From: John Doe</h5>
											<small class="text-white fs-6">Memory: Our honeymoon</small>
											<div class="mt-2 d-flex align-items-center justify-content-between" style="width:80% !important;">

												<span class="btn btn-transparent border border-white rounded-pill text-white fw-bold  align-items-center justify-content-center" style="width: 150px;">
													Review
												</span>

												<span class="btn btn-transparent border border-white rounded-pill text-white fw-bold  align-items-center justify-content-center" style="width: 150px;">
													<img src="assets/images/icons/family.png" alt="Role Icon" class="rounded-circle me-2" style="width: 24px; height: 24px;">
													Family
												</span>
											</div>

										</div>
									</div>
								</div>

								<div class="p-3 collaboration-card">
									<div class="row align-items-center">
										<div class="col-3">
											<img src="https://placehold.co/600x400/png" alt="Profile" class="rounded-circle" width="80" height="80">
										</div>
										<div class="col-9">
											<h5 class="mb-0">From: Jake Doe</h5>
											<small class="text-white fs-6">Collaboration Request</small>
											<div class="mt-2 d-flex align-items-center justify-content-between" style="width:80% !important;">

												<span class="btn btn-transparent border border-white rounded-pill text-white fw-bold  align-items-center justify-content-center" style="width: 150px;">
													Reject
												</span>

												<span class="btn btn-white border border-white rounded-pill text-black fw-bold  align-items-center justify-content-center txt-hover-white" style="width: 150px;">
													Accept
												</span>

											</div>

										</div>
									</div>
								</div>

							</div>

							<div id="sent-tab" class="totallyHide">

							</div>

						</div>
					</div>
				</div>

			</div>
			<!--end row-->
		</div>
	</div>
	<!--end page wrapper -->

	<!-- Button trigger modal -->
	<!-- <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleDarkModal">Dark Modal</button> -->
	<!-- Modal -->
	<div class="modal fade" id="memoryModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content bg-dark">
				<div class="modal-header">
					<h5 class="modal-title text-white">Preview Memory</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body text-white">
					<p id="prev_location"></p>
					<img id="imagePreview" src="" alt="Image Preview" />
					<p id="prev_name"></p>
					<p id="prev_text"></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light" onclick="saveMemory();">Save changes</button>
					<button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!--start overlay-->
	<div class="overlay toggle-icon"></div>
	<!--end overlay-->
	<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
	<!--End Back To Top Button-->
	</div>
	<footer class="accent-group page-footer">
		<p class="mb-0">Copyright © <?php echo $year; ?>. All right reserved.</p>
	</footer>
	<!--end wrapper-->
	<!--start switcher-->
	<!-- <div class="switcher-wrapper">
		<div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
		</div>
		<div class="switcher-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-uppercase">Theme Customizer</h5>
				<button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
			</div>
			<hr />
			<p class="mb-0">Gaussian Texture</p>
			<hr>
			<ul class="switcher">
				<li id="theme1"></li>
				<li id="theme2"></li>
				<li id="theme3"></li>
				<li id="theme4"></li>
				<li id="theme5"></li>
				<li id="theme6"></li>
			</ul>
			<hr>
			<p class="mb-0">Gradient Background</p>
			<hr>
			<ul class="switcher">
				<li id="theme7"></li>
				<li id="theme8"></li>
				<li id="theme9"></li>
				<li id="theme10"></li>
				<li id="theme11"></li>
				<li id="theme12"></li>
				<li id="theme13"></li>
				<li id="theme14"></li>
				<li id="theme15"></li>
			</ul>
		</div>
	</div> -->
	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<link rel="stylesheet" href="assets/plugins/notifications/css/lobibox.min.css" />
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
	<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
	<script src="assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js"></script>
	<script src="assets/plugins/datetimepicker/js/legacy.js"></script>
	<script src="assets/plugins/datetimepicker/js/picker.js"></script>
	<script src="assets/plugins/datetimepicker/js/picker.time.js"></script>
	<script src="assets/plugins/datetimepicker/js/picker.date.js"></script>
	<script src="assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js"></script>
	<script src="assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js"></script>
	<!--notification js -->
	<script src="assets/plugins/notifications/js/lobibox.min.js"></script>
	<script src="assets/plugins/notifications/js/notifications.min.js"></script>
	<script src="assets/plugins/notifications/js/notification-custom-script.js"></script>
	<script>
		$(document).ready(function() {
			$('#Transaction-History').DataTable({
				lengthMenu: [
					[6, 10, 20, -1],
					[6, 10, 20, 'Todos']
				]
			});
		});
	</script>
	<script>
		function previewMemory() {
			var memoryname = $('#memoryname').val();
			var memorytext = $('#memorytext').val();
			var city = $('#city_').val();
			// var family = $('#family').val();
			// var state = $('#STATES').val();
			var state = $('#STATES option:selected').text();
			// var country = $('#COUNTRY').val();
			var country = $('#COUNTRY option:selected').text();

			if (memoryname == "" || memorytext == "" || city == "" || state == "Select Option" || country == "Select Option") {
				round_error_notification('Fill in all the fields');

			} else {
				$('#prev_location').text(city + ', ' + state + ', ' + country);
				$('#prev_name').text(memoryname);
				$('#prev_text').text(memorytext);

				// alert($('#prev_location').val());

				var file = document.getElementById('image-uploadify').files[0];
				if (file) {
					var reader = new FileReader();
					reader.onload = function(e) {
						$('#imagePreview').attr('src', e.target.result);
					};
					reader.readAsDataURL(file);

					$('#memoryModal').modal('show');
				} else {
					round_error_notification('Upload an image to proceed');
				}
			}
		}

		function saveMemory() {
			var city = $('#city_').val();
			var country = $('#COUNTRY').val();
			var state = $('#STATES').val();
			// var lifelayer = $('#LIFE_LAYERS').val();
			var viewability = $('#VIEW_RIGHT_CONNECTION').val();
			var date = $('#date_').val();
			var memoryname = $('#memoryname').val();
			var memorytext = $('#memorytext').val();
			// var family = $('#family').val();

			var form_data = new FormData();

			form_data.append('city_', city);
			form_data.append('COUNTRY', country);
			form_data.append('STATES', state);
			// form_data.append('LIFE_LAYERS', lifelayer);
			// form_data.append('VIEW_RIGHT_CONNECTION', viewability);
			form_data.append('date_', date);
			form_data.append('memoryname', memoryname);
			form_data.append('memorytext', memorytext);
			// form_data.append('taggedmember', family);
			form_data.append("fileToUpload", document.getElementById('image-uploadify').files[0]);

			$.ajax({
				type: 'POST',
				url: 'memory-builder.php',
				dataType: 'text', // what to expect back from the PHP script
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				success: function(response) {
					// console.log(response);
					location.reload();
				},
				error: function(xhr, status, error) {
					console.error('Error: ' + error);
				}
			});
		}
	</script>

	<script>
		$(document).ready(function() {
			$('#image-uploadify').imageuploadify();
		})
	</script>
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
	<script>
		function toggleButtonStyles(btn) {
			const btnReceived = document.getElementById('btn-received');
			const btnSent = document.getElementById('btn-sent');
			if (btn.id === 'btn-received') {
				btnReceived.classList.add('btn-white', 'text-black');
				btnReceived.classList.remove('btn-transparent', 'text-white');
				btnSent.classList.add('btn-transparent', 'text-white');
				btnSent.classList.remove('btn-white', 'text-black');
				document.getElementById('sent-tab').classList.add('totallyHide');
				document.getElementById('received-tab').classList.remove('totallyHide');
			} else if (btn.id === 'btn-sent') {
				btnSent.classList.add('btn-white', 'text-black');
				btnSent.classList.remove('btn-transparent', 'text-white');
				btnReceived.classList.add('btn-transparent', 'text-white');
				btnReceived.classList.remove('btn-white', 'text-black');
				document.getElementById('sent-tab').classList.remove('totallyHide');
				document.getElementById('received-tab').classList.add('totallyHide');
			}
		}

		function activateTab(selectedTab) {
			const tabs = document.querySelectorAll('.memtab');
			tabs.forEach(tab => tab.classList.remove('memtab-active'));
			selectedTab.classList.add('memtab-active');
			const tag = selectedTab.getAttribute('tag');
			const sections = document.querySelectorAll('.tabsect');
			sections.forEach(section => section.classList.add('totallyHide'));

			const targetSection = document.querySelector(`.${tag}`);
			if (targetSection) {
				targetSection.classList.remove('totallyHide');
			}
		}

		function toggleDropdown(formId) {
			const dropdown = document.getElementById("saveDropdown-" + formId);
			dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
		}

		function saveAndContinue(formId) {

			const formElement = document.getElementById(formId);
			if (formElement) {
				formElement.classList.add("totallyHide");
			}

			saveAndClose()
		}

		function saveAndClose() {

			const builderForms = document.getElementById("builderForms");
			if (builderForms) {
				builderForms.classList.add("totallyHide");
			}

			closeDropdown();
		}

		function showStepInvite() {
			document.getElementById("collaborationInvite").classList.remove("totallyHide");

		}
	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
	<script src="assets/js/index.js"></script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
	<script>
		new PerfectScrollbar('.product-list');
		new PerfectScrollbar('.customers-list');
	</script>
</body>

</html>