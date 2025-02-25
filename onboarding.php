<?php

include_once 'global.php';
session_start();
$error = $message = '';

$response = makeGetAPICall('getDictionaries');

$country = mapOptionSets('COUNTRY');

$state = mapOptionSets('STATES');

$viewability = mapOptionSets('VIEW_RIGHT');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$purpose = $_POST["purpose"];
	$firstName = $_POST["inputFirstName"];
	$middleName = $_POST["inputMiddleName"];
	$lastName = $_POST["inputLastName"];
	$DOB = $_POST["inputDOB"];
	$city = $_POST["inputCity"];
	$state = $_POST["inputState"];
	$country = $_POST["inputCountry"];
	$details = $_POST["inputDetails"];
	$guestFirstName = $_POST["inputGuestFirstName"];
	$guestLastName = $_POST["inputGuestLastName"];
	$guestEmail = $_POST["inputGuestEmail"];
	$guestRelation = $_POST["inputGuestRelation"];
	$guestMessage = $_POST["inputGuestMessage"];
	$background = $_POST["background"];
	$accent = $_POST['accent'];
	if (isset($_POST['activity']) && !empty($_POST['activity'])) {
		$selectedActivities = $_POST['activity'];
		$_SESSION['activities'] = [];

		foreach ($selectedActivities as $activity) {
			$key = getKeyByValue($activity, $memoryOptions);
			if ($key !== null) {
				$_SESSION['activities'][] = $key;
			}
		}
	} else {
		$selectedActivities = [];
		$_SESSION['activities'] = [];
	}


	$imageExtension = $image = '';
	$_SESSION['background'] = $background;
	$_SESSION['accent'] = getAccentByValue($accent);

	if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] == UPLOAD_ERR_OK) {
		$image = base64_encode(file_get_contents($_FILES["profile_pic"]["tmp_name"]));
		$imageExtension = end((explode(".", $_FILES["profile_pic"]["name"])));
		$fileType = $_FILES['profile_pic']['type'];
		$dataUri = 'data:' . $fileType . ';base64,' . $image;
		$_SESSION["PROFILE_PIC"] = $dataUri;
	}

	$body = json_encode(array(
		"onboardingtype" => (int) $purpose,
		"firstname" => $firstName,
		"middlename" => $middleName,
		"lastname" => $lastName,
		"profilepic" => $dataUri,
		"pictype" => $imageExtension,
		"shortbio" => $details,
		"dateofbirth" => $DOB,
		"country" => (int) $country,
		"state" => (int) $state,
		"city" => $city,
		"theme" => true,
		"accentcolor" => $accent,
		"background" => $background,
		"memorycreateoptions" => array_values($selectedActivities),
		"guestadmin" => array(
			"firstname" => $guestFirstName,
			"lastname" => $guestLastName,
			"emailaddress" => $guestEmail,
			"relationship" => (int) $guestRelation,
			"message" => $guestMessage
		)
	));


	$response = makePostAPIcall('updateProfile', $body, $_SESSION['token']);
	$responseData = json_decode($response, true);
	if (isset($responseData["success"]) && $responseData["success"] === "true") {
		$_SESSION["username"] = $firstName . ' ' . $lastName;

		$_SESSION['guestadmin'] = $guestFirstName . ' ' . $guestLastName;
		$_SESSION['guestemailaddress'] = $guestEmail;

		header("location: timeline.php");
		exit();
	} else {
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
	<link rel="stylesheet" href="assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css">
	<link rel="stylesheet" href="assets/plugins/notifications/css/lobibox.min.css" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
	<link href="assets/css/icons.css" rel="stylesheet">
	<title>Dignitrees - Family App</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100..900&display=swap" rel="stylesheet">
</head>

<body class="bg-theme bg-theme2">
	<!--wrapper-->
	<div class="wrapper">
		<div class="container login-container">
			<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
				<div class="col">
					<div class="card mb-0 cardClear">
						<div class="card-body onboarding-card">


							<div class="form-body">
								<form class="row g-3" action="onboarding.php" method="POST" enctype="multipart/form-data">
									<!-- display:none -->
									<div id="step1" style="">
										<div class="mb-2 text-center d-flex align-items-center justify-content-center spacing-20">
											<div class="text-center logo_login d-flex align-items-center justify-content-center">
												<img src="assets/images/logo-icon.png" width="30" alt="" />
											</div>
											<h5 class="mb-0 name-title text-element">Dignitrees Family App</h5>
										</div>
										<div class="onboard-window-1">
											<div class="col-12 mb-0">
												<div class="d-flex align-items-center gap-2">
													<p class="main-label text-element">What is the purpose of this account? <br> Please choose one of the following: </p>
												</div>
											</div>
											<div class="col-12 spacing-20">
												<label class="cbContainer">
													<input type="radio" name="purpose" value="1137" checked>
													<span class="checkmark"></span>
													<div class="text">
														<div class="title text-element">Personal memoir</div>
														<div class="description text-element">I want to share my own life stories.</div>
													</div>
												</label>

											</div>
											<div class="col-12 spacing-20">
												<label class="cbContainer">
													<input type="radio" name="purpose" value="1138">
													<span class="checkmark"></span>
													<div class="text">
														<div class="title text-element">Tribute</div>
														<div class="description text-element">I want to honor a living loved one.</div>
													</div>
												</label>

											</div>
											<div class="col-12 spacing-20">
												<label class="cbContainer">
													<input type="radio" name="purpose" value="1139">
													<span class="checkmark"></span>
													<div class="text">
														<div class="title text-element">Memorial</div>
														<div class="description text-element">I want to remember a loved one who has passed.</div>
													</div>
												</label>

											</div>


											<div class="col-12">
												<p class="mb-0 text-danger" id="txtError"><?php echo $error; ?></p>
												<p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
											</div>
											</br>
										</div>

										<div class="col-12 d-flex justify-content-center btn-box">
											<div class="text-center mb-2">
												<button type="button" onclick="validate(1);" class="btn slim-next " onmouseover="changeIcon(this, 'assets/images/icons/right-white.svg')"
													onmouseout="changeIcon(this, 'assets/images/icons/right.svg')">Next <img src="assets/images/icons/right.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
											</div>
										</div>

									</div>
									<div id="step2" style="display:none; padding-top:20px;">
										<div class="col-12 onboard-window">
											<div class="text-start mb-4">
												<p class="mb-2 text-white text-element" id="step2-text">OK, you want to create a personal memoir. We'll focus on your memories and life events then.</p>
												<p class="mb-0 text-white text-element">Let`s set up the account profile.</p>
											</div>
											<div class="d-flex align-items-center gap-2 mb-2">
												<div class="numberCircle">
													<span>1</span>
												</div>
												<span class="onboardngStepTitle text-white text-element" id="name-instructions">Enter your name and basic facts about your life.</span>
											</div>
											<div class="invite-guest">
												<div class="col-12 mb-4">
													<span class="mb-2 text-white text-element" id="name-label">What is your name? <sup>*</sup></span>
													<input type="text" class="form-control border-white mb-3" id="inputFirstName" name="inputFirstName" placeholder="First name" required value="<?php echo $_SESSION['firstname']; ?>">
													<input type="text" class="form-control border-white mb-3" id="inputMiddleName" name="inputMiddleName" placeholder="Middle name">
													<input type="text" class="form-control border-white mb-3" id="inputLastName" name="inputLastName" placeholder="Last name" required value="<?php echo $_SESSION['lastname']; ?>">
													<div class="text-end">
														<span id="invite" style="display:none; cursor:pointer;" class="text-white"><u>Invite</u></span>
													</div>
												</div>

												<div class="col-12 mb-4">
													<span class="mb-2 text-white text-element" id="birth-label">When were you born? <sup>*</sup></span>
													<input type="date" class="form-control border-white mb-3" id="inputDOB" name="inputDOB" placeholder="Select a date" required>
												</div>

												<div class="col-12 mb-4">
													<span class="mb-2 text-white text-element">Where? <sup>*</sup></span>
													<input type="text" class="form-control border-white mb-3" id="inputCity" name="inputCity" placeholder="City" required>
													<!-- <input type="text" class="form-control border-white mb-3" id="inputState" name="inputState" placeholder="State/Province/Territory"> -->

													<select class="form-select border-white mb-3" id="inputState" name="inputState" placeholder="State/Province/Territory">
														<option>State/Province/Territory</option>
														<option value="1039">Alabama</option>
														<option value="1040">Alaska</option>
														<option value="1041">Arizona</option>
														<option value="1042">Arkansas</option>
														<option value="1043">California</option>
														<option value="1044">Colorado</option>
														<option value="1045">Connecticut</option>
														<option value="1046">Delaware</option>
														<option value="1047">Florida</option>
														<option value="1048">Georgia</option>
														<option value="1049">Hawaii</option>
														<option value="1050">Idaho</option>
														<option value="1051">Illinois</option>
														<option value="1052">Indiana</option>
														<option value="1053">Iowa</option>
														<option value="1054">Kansas</option>
														<option value="1055">Kentucky</option>
														<option value="1056">Louisiana</option>
														<option value="1057">Maine</option>
														<option value="1058">Maryland</option>
														<option value="1059">Massachusetts</option>
														<option value="1060">Michigan</option>
														<option value="1061">Minnesota</option>
														<option value="1062">Mississippi</option>
														<option value="1063">Missouri</option>
														<option value="1064">Montana</option>
														<option value="1065">Nebraska</option>
														<option value="1066">Nevada</option>
														<option value="1067">New Hampshire</option>
														<option value="1068">New Jersey</option>
														<option value="1069">New Mexico</option>
														<option value="1070">New York</option>
														<option value="1071">North Carolina</option>
														<option value="1072">North Dakota</option>
														<option value="1073">Ohio</option>
														<option value="1074">Oklahoma</option>
														<option value="1075">Oregon</option>
														<option value="1076">Pennsylvania</option>
														<option value="1077">Rhode Island</option>
														<option value="1078">South Carolina</option>
														<option value="1079">South Dakota</option>
														<option value="1080">Tennessee</option>
														<option value="1081">Texas</option>
														<option value="1082">Utah</option>
														<option value="1083">Vermont</option>
														<option value="1084">Virginia</option>
														<option value="1085">Washington</option>
														<option value="1086">West Virginia</option>
														<option value="1087">Wisconsin</option>
														<option value="1088">Wyoming</option>
													</select>
													<!-- <input type="text" class="form-control border-white mb-3" id="inputCountry" name="inputCountry" placeholder="Country" required> -->

													<select class="form-select border-white" id="inputCountry" name="inputCountry" required>
														<option>Country</option>
														<option value="190">United States of America</option>
														<option value="1154">Afghanistan</option>
														<option value="1155">Albania</option>
														<option value="1156">Algeria</option>
														<option value="1157">Andorra</option>
														<option value="1158">Angola</option>
														<option value="1159">Antigua and Barbuda</option>
														<option value="1160">Argentina</option>
														<option value="1161">Armenia</option>
														<option value="1162">Australia</option>
														<option value="1163">Austria</option>
														<option value="1164">Azerbaijan</option>
														<option value="1165">Bahamas</option>
														<option value="1166">Bahrain</option>
														<option value="1167">Bangladesh</option>
														<option value="1168">Barbados</option>
														<option value="1169">Belarus</option>
														<option value="1170">Belgium</option>
														<option value="1171">Belize</option>
														<option value="1172">Benin</option>
														<option value="1173">Bhutan</option>
														<option value="1174">Bolivia</option>
														<option value="1175">Bosnia and Herzegovina</option>
														<option value="1176">Botswana</option>
														<option value="1177">Brazil</option>
														<option value="1178">Brunei</option>
														<option value="1179">Bulgaria</option>
														<option value="1180">Burkina Faso</option>
														<option value="1181">Burundi</option>
														<option value="1182">Cabo Verde</option>
														<option value="1183">Cambodia</option>
														<option value="1184">Cameroon</option>
														<option value="1185">Canada</option>
														<option value="1186">Central African Republic</option>
														<option value="1187">Chad</option>
														<option value="1188">Chile</option>
														<option value="1189">China</option>
														<option value="1190">Colombia</option>
														<option value="1191">Comoros</option>
														<option value="1192">Congo (Congo-Brazzaville)</option>
														<option value="1193">Costa Rica</option>
														<option value="1194">Croatia</option>
														<option value="1195">Cuba</option>
														<option value="1196">Cyprus</option>
														<option value="1197">Czechia (Czech Republic)</option>
														<option value="1198">Democratic Republic of the Congo</option>
														<option value="1199">Denmark</option>
														<option value="1200">Djibouti</option>
														<option value="1201">Dominica</option>
														<option value="1202">Dominican Republic</option>
														<option value="1203">East Timor (Timor-Leste)</option>
														<option value="1204">Ecuador</option>
														<option value="1205">Egypt</option>
														<option value="1206">El Salvador</option>
														<option value="1207">Equatorial Guinea</option>
														<option value="1208">Eritrea</option>
														<option value="1209">Estonia</option>
														<option value="1210">Eswatini (fmr. "Swaziland")</option>
														<option value="1211">Ethiopia</option>
														<option value="1212">Fiji</option>
														<option value="1213">Finland</option>
														<option value="1214">France</option>
														<option value="1215">Gabon</option>
														<option value="1216">Gambia</option>
														<option value="1217">Georgia</option>
														<option value="1218">Germany</option>
														<option value="1219">Ghana</option>
														<option value="1220">Greece</option>
														<option value="1221">Grenada</option>
														<option value="1222">Guatemala</option>
														<option value="1223">Guinea</option>
														<option value="1224">Guinea-Bissau</option>
														<option value="1225">Guyana</option>
														<option value="1226">Haiti</option>
														<option value="1227">Honduras</option>
														<option value="1228">Hungary</option>
														<option value="1229">Iceland</option>
														<option value="1230">India</option>
														<option value="1231">Indonesia</option>
														<option value="1232">Iran</option>
														<option value="1233">Iraq</option>
														<option value="1234">Ireland</option>
														<option value="1235">Israel</option>
														<option value="1236">Italy</option>
														<option value="1237">Ivory Coast</option>
														<option value="1238">Jamaica</option>
														<option value="1239">Japan</option>
														<option value="1240">Jordan</option>
														<option value="1241">Kazakhstan</option>
														<option value="1242">Kenya</option>
														<option value="1243">Kiribati</option>
														<option value="1244">Kuwait</option>
														<option value="1245">Kyrgyzstan</option>
														<option value="1246">Laos</option>
														<option value="1247">Latvia</option>
														<option value="1248">Lebanon</option>
														<option value="1249">Lesotho</option>
														<option value="1250">Liberia</option>
														<option value="1251">Libya</option>
														<option value="1252">Liechtenstein</option>
														<option value="1253">Lithuania</option>
														<option value="1254">Luxembourg</option>
														<option value="1255">Madagascar</option>
														<option value="1256">Malawi</option>
														<option value="1257">Malaysia</option>
														<option value="1258">Maldives</option>
														<option value="1259">Mali</option>
														<option value="1260">Malta</option>
														<option value="1261">Marshall Islands</option>
														<option value="1262">Mauritania</option>
														<option value="1263">Mauritius</option>
														<option value="1264">Mexico</option>
														<option value="1265">Micronesia</option>
														<option value="1266">Moldova</option>
														<option value="1267">Monaco</option>
														<option value="1268">Mongolia</option>
														<option value="1269">Montenegro</option>
														<option value="1270">Morocco</option>
														<option value="1271">Mozambique</option>
														<option value="1272">Myanmar (formerly Burma)</option>
														<option value="1273">Namibia</option>
														<option value="1274">Nauru</option>
														<option value="1275">Nepal</option>
														<option value="1276">Netherlands</option>
														<option value="1277">New Zealand</option>
														<option value="1278">Nicaragua</option>
														<option value="1279">Niger</option>
														<option value="1280">Nigeria</option>
														<option value="1281">North Korea</option>
														<option value="1282">North Macedonia (formerly Macedonia)</option>
														<option value="1283">Norway</option>
														<option value="1284">Oman</option>
														<option value="1285">Pakistan</option>
														<option value="1286">Palau</option>
														<option value="1287">Palestine State</option>
														<option value="1288">Panama</option>
														<option value="1289">Papua New Guinea</option>
														<option value="1290">Paraguay</option>
														<option value="1291">Peru</option>
														<option value="1292">Philippines</option>
														<option value="1293">Poland</option>
														<option value="1294">Portugal</option>
														<option value="1295">Qatar</option>
														<option value="1296">Romania</option>
														<option value="1297">Russia</option>
														<option value="1298">Rwanda</option>
														<option value="1299">Saint Kitts and Nevis</option>
														<option value="1300">Saint Lucia</option>
														<option value="1301">Saint Vincent and the Grenadines</option>
														<option value="1302">Samoa</option>
														<option value="1303">San Marino</option>
														<option value="1304">Sao Tome and Principe</option>
														<option value="1305">Saudi Arabia</option>
														<option value="1306">Senegal</option>
														<option value="1307">Serbia</option>
														<option value="1308">Seychelles</option>
														<option value="1309">Sierra Leone</option>
														<option value="1310">Singapore</option>
														<option value="1311">Slovakia</option>
														<option value="1312">Slovenia</option>
														<option value="1313">Solomon Islands</option>
														<option value="1314">Somalia</option>
														<option value="1315">South Africa</option>
														<option value="1316">South Korea</option>
														<option value="1317">South Sudan</option>
														<option value="1318">Spain</option>
														<option value="1319">Sri Lanka</option>
														<option value="1320">Sudan</option>
														<option value="1321">Suriname</option>
														<option value="1322">Sweden</option>
														<option value="1323">Switzerland</option>
														<option value="1324">Syria</option>
														<option value="1325">Tajikistan</option>
														<option value="1326">Tanzania</option>
														<option value="1327">Thailand</option>
														<option value="1328">Togo</option>
														<option value="1329">Tonga</option>
														<option value="1330">Trinidad and Tobago</option>
														<option value="1331">Tunisia</option>
														<option value="1332">Turkey</option>
														<option value="1333">Turkmenistan</option>
														<option value="1334">Tuvalu</option>
														<option value="1335">Uganda</option>
														<option value="1336">Ukraine</option>
														<option value="1337">United Arab Emirates</option>
														<option value="1338">United Kingdom</option>
														<option value="1339">Uruguay</option>
														<option value="1340">Uzbekistan</option>
														<option value="1341">Vanuatu</option>
														<option value="1342">Vatican City</option>
														<option value="1343">Venezuela</option>
														<option value="1344">Vietnam</option>
														<option value="1345">Yemen</option>
														<option value="1346">Zambia</option>
														<option value="1347">Zimbabwe</option>
													</select>
												</div>
											</div>

											<div class="col-12">
												<p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
												<p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
											</div>
										</div>
										<div class="row d-flex justify-content-center btn-box">
											<div class="text-start mb-2 col-6">
												<button type="button" onmouseover="changeIcon(this, 'assets/images/icons/left-white.svg')"
													onmouseout="changeIcon(this, 'assets/images/icons/left.svg')" onclick="back(2);" class="btn btn-white btn-next-arrow"><img src="assets/images/icons/left.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
											</div>
											<div class="text-end mb-2 col-6">
												<button type="button" onmouseover="changeIcon(this, 'assets/images/icons/right-white.svg')"
													onmouseout="changeIcon(this, 'assets/images/icons/right.svg')" onclick="validate(2);" class="btn btn-white btn-next-arrow"><img src="assets/images/icons/right.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
											</div>
										</div>


									</div>
									<div id="step3" style="display:none; padding-top:20px;">
										<div class="onboard-window">
											<div class="col-12 mb-2">
												<div class="d-flex align-items-center gap-2 mb-2">
													<div class="numberCircle">
														<span>2</span>
													</div>
													<span class="onboardngStepTitle text-white text-element">Create a short bio.</span>
												</div>
												<div class="col-12 mb-3">
													<span class="mb-2 text-white text-element" id="shortDescription">Please write a short description of yourself.</span>
													<textarea class="form-control border-white mb-2 text-white" id="inputDetails" name="inputDetails" placeholder="(Optional) In 3-5 sentences, share a few details about yourself." rows="10" required></textarea>
													<div class="text-end">
														<span id="charCount" class="text-white">0/500</span>
													</div>
												</div>

												<div class="col-12 mb-3">
													<span class="mb-2 text-white text-element" id="uploadPicture">Upload a picture of yourself (jpg or png under 8MB)</span>
													<div class="col-12">
														<input id="profile_pic" class="form-control border-white" name="profile_pic" type="file" accept=".jpg, .jpeg, .png" name="" onchange="previewImage(this);">
														<div class="mt-3 text-start d-flex justify-content-center align-items-center gap-3">
															<img id="previewProfile" src="<?php echo $_SESSION["PROFILE_PIC"]; ?>" alt="Profile Preview" <?php if (!empty($_SESSION["PROFILE_PIC"])) echo 'style="display:block;"'; ?>>
															<span id="fileName" class="text-white"></span>
														</div>
													</div>
												</div>
											</div>

											<div class="col-12">
												<p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
												<p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
											</div>
										</div>
										<div class="row  btn-box">
											<div class="text-start mb-2 col-6">
												<button type="button" onclick="back(3);" onmouseover="changeIcon(this, 'assets/images/icons/left-white.svg')"
													onmouseout="changeIcon(this, 'assets/images/icons/left.svg')" class="btn btn-white btn-next-arrow"><img src="assets/images/icons/left.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
											</div>
											<div class="text-end mb-2 col-6">
												<button type="button" onclick="validate(3);" onmouseover="changeIcon(this, 'assets/images/icons/right-white.svg')"
													onmouseout="changeIcon(this, 'assets/images/icons/right.svg')" class="btn btn-white btn-next-arrow"><img src="assets/images/icons/right.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
											</div>
										</div>


									</div>
									<div id="step4" style="display:none; padding-top:20px;">
										<div class="onboard-window">
											<div class="col-12 mb-2">
												<div class="d-flex align-items-center gap-2 mb-2">
													<div class="numberCircle">
														<span>3</span>
													</div>
													<span class="onboardngStepTitle text-white text-element">Customize your experience.</span>
												</div>
												<div class="col-12 mb-3">
													<span class="mb-2 text-white text-element">Do you prefer a light or dark theme?</span>

													<div class="toggle-switch-container mt-2">
														<!-- <div class="toggle-switch">
															<input type="checkbox" id="themeToggle" class="toggle-checkbox">
															<label for="themeToggle" class="toggle-label">
																<span class="toggle-slider"></span>
															</label>
														</div> -->
														<div class="col-md-6 d-flex align-items-center gap-3">
															<span class="toggle-text-left text-element">Light</span>
															<div class="form-check form-switch gap-3 d-flex align-items-center">
																<input class="form-check-input border-white" type="checkbox" name="theme" id="flexSwitchCheckChecked">
															</div>
															<span class="toggle-text-right text-element">Dark</span>
														</div>
													</div>

													<div class="col-12 mt-3">
														<p class="text-white text-element">Choose a background for your app from the options below.</p>
														<div class="square-container">
															<div class="square">
																<input type="checkbox" name="background" value="1150" hidden checked>
																<img src="assets/images/bg-themes/Summer.jpg" alt="Summer" class="square-image">
															</div>
															<div class="square">
																<input type="checkbox" name="background" value="1151" hidden>
																<img src="assets/images/bg-themes/Autumn.jpg" alt="Autumn" class="square-image">
															</div>
															<div class="square">
																<input type="checkbox" name="background" value="1152" hidden>
																<img src="assets/images/bg-themes/Winter.jpg" alt="Winter" class="square-image">
															</div>
															<div class="square">
																<input type="checkbox" name="background" value="1153" hidden>
																<img src="assets/images/bg-themes/Spring.jpg" alt="Spring" class="square-image">
															</div>
														</div>
													</div>


													<div class="col-12 mt-3">
														<p class="text-white text-element">Choose an accent color for your app from the options below.</p>

														<div class="row toggle-switch-container">
															<div class="col-2 accent">

																<input type="radio" name="accent" hidden value="1150" checked>
																<div class="letter-square text-white accent selected" mode="light" tag="rgba(0,0,0,0.7)" style="background-color:rgba(0, 0, 0, 0.7);">A</div>
															</div>
															<div class="col-2 accent">

																<input type="radio" name="accent" hidden value="1142">
																<div class="letter-square  text-white accent" mode="light" tag="rgba(140, 69, 21, 0.80)" style="background-color: #8C4515;">A</div>
															</div>
															<div class="col-2 accent">

																<input type="radio" name="accent" hidden value="1143">
																<div class="letter-square  text-white accent" mode="light" tag="rgba(13, 99, 62, 0.80)" style="background-color: #0D633E;">A</div>
															</div>
															<div class="col-2 accent">

																<input type="radio" name="accent" hidden value="1144">
																<div class="letter-square  text-white accent" mode="light" tag=" rgba(34, 68, 191, 0.80)" style="background-color: #2244BF;">A</div>
															</div>
															<div class="col-2 accent">

																<input type="radio" name="accent" hidden value="1145">
																<div class="letter-square  text-white accent" mode="light" tag="rgba(120, 47, 193, 0.80)" style="background-color: #782FC1;">A</div>
															</div>
															<div class="col-2 accent">

																<input type="radio" name="accent" hidden value="1146">
																<div class="letter-square text-black accent" mode="dark" tag="rgba(255, 228, 189, 0.70)" style="background-color: rgb(255, 228, 189);">A</div>
															</div>
															<div class="col-2 accent">

																<input type="radio" name="accent" hidden value="1150">
																<div class="letter-square  text-white accent" mode="dark" tag="rgba(255, 228, 189, 0.70)" style="background-color: #FFC87A;">A</div>
															</div>
															<div class="col-2 accent">

																<input type="radio" name="accent" hidden value="1146">
																<div class="letter-square text-dark accent" mode="dark" tag="rgba(182, 255, 202, 0.70)" style="background-color: #83FBA5;">A</div>
															</div>
															<div class="col-2 accent">

																<input type="radio" name="accent" hidden value="1148">
																<div class="letter-square  text-white accent" mode="dark" tag="rgba(185, 234, 255, 0.70)" style="background-color: #89CAE6;">A</div>
															</div>
															<div class="col-2 accent">

																<input type="radio" name="accent" hidden value="1149">
																<div class="letter-square  text-white accent" mode="dark" tag="rgba(227, 199, 255, 0.70)" style="background-color: #D6B7F5;">A</div>
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

										<div class="row  btn-box">
											<div class="text-start mb-2 col-6">
												<button type="button" onclick="back(4);" onmouseover="changeIcon(this, 'assets/images/icons/left-white.svg')"
													onmouseout="changeIcon(this, 'assets/images/icons/left.svg')" class="btn btn-white btn-next-arrow"><img src="assets/images/icons/left.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
											</div>
											<div class="text-end mb-2 col-6">
												<button type="button" onclick="validate(4);" onmouseover="changeIcon(this, 'assets/images/icons/right-white.svg')"
													onmouseout="changeIcon(this, 'assets/images/icons/right.svg')" class="btn btn-white btn-next-arrow"><img src="assets/images/icons/right.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
											</div>
										</div>


									</div>
									<div id="step5" style="display:none; padding-top:20px;">
										<div class="onboard-window">
											<div class="col-12 ">
												<div class="d-flex align-items-center gap-2 mb-2">
													<div class="numberCircle">
														<span>4</span>
													</div>
													<span class="onboardngStepTitle main-label text-element">Choose a guest administrator.</span>
												</div>
												<div class="text-start mb-3">
													<p class="mb-2 main-label text-element"> You may designate a guest administrator to access and manage the account should you become unable to do so.
														<br><br>Warning: Guest administrators have the ability to make changes to any data or setting in this application.
													</p>
													<div class="col-12 mt-3">
														<label class="cbContainer">
															<input type="checkbox" name="agreeGuest" value="agree" id="agreeGuestCheckbox">
															<div class="text">
																<div class="description text-element">I want to invite a friend or family member to be my guest administrator now.</div>
															</div>
														</label>

													</div>

												</div>
												<div id="invite-guest" class="inviteguest totallyHide">
													<div class="col-12 mb-3">
														<span class="mb-2 text-white">Name <sup>*</sup></span>
														<input type="text" class="form-control border-white mb-3" id="inputGuestFirstName" name="inputGuestFirstName" placeholder="First name">
														<input type="text" class="form-control border-white mb-3" id="inputGuestLastName" name="inputGuestLastName" placeholder="Last name">
													</div>

													<div class="col-12 mb-3">
														<span class="mb-2 text-white">Email <sup>*</sup></span>
														<input type="email" class="form-control border-white mb-3" id="inputGuestEmail" name="inputGuestEmail" placeholder="name@email.com">
													</div>

													<div class="col-12">
														<span class="mb-2 text-white">Relationship <sup>*</sup></span>
														<select class="form-select border-white mb-3 text-white bg-dark" id="inputGuestRelation" name="inputGuestRelation">
															<option value="" disabled selected>Choose a relationship</option>
															<option value="sibling">Sibling</option>
															<option value="partner">Partner</option>
															<option value="other">Other</option>
														</select>
													</div>

													<div class="col-12 mb-3">
														<span class="mb-2 text-white">Message</span>
														<input type="text" class="form-control border-white mb-2" id="inputGuestMessage" name="inputGuestMessage" placeholder="Message">

													</div>
												</div>
											</div>

											<div class="col-12">
												<p class="mb-0 text-danger" id="passwordError"><?php echo $error; ?></p>
												<p class="mb-0 text-success" id="txtMessage"><?php echo $message; ?></p>
											</div>
										</div>
										<div class="row btn-box">
											<div class="text-start col-3">
												<button type="button" onclick="back(5);" onmouseover="changeIcon(this, 'assets/images/icons/left-white.svg')"
													onmouseout="changeIcon(this, 'assets/images/icons/left.svg')" class="btn btn-white btn-next-arrow"><img src="assets/images/icons/left.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
											</div>
											<div class="text-end col-9 " id="noguest">
												<button type="button" onclick="validate(5);" onmouseover="changeIcon(this, 'assets/images/icons/right-white.svg')"
													onmouseout="changeIcon(this, 'assets/images/icons/right.svg')" class="btn btn-white btn-next-arrow"><img src="assets/images/icons/right.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
											</div>
											<div class="text-end  col-9 totallyHide" id="guest">
												<button type="button" onclick="validate(5);" class="btn btn-white btn-arrow border-white SContinue">Send & Continue</button>
											</div>
										</div>


									</div>

									<div id="step6" style="display:none; padding-top:20px;">
										<div class="onboard-window">

											<div class="col-12 mb-2">
												<div class="d-flex align-items-center gap-2 mb-2">
													<div class="numberCircle">
														<span>5</span>
													</div>
													<span class="onboardngStepTitle text-white text-element">Start your timeline.</span>
												</div>
											</div>
											<div class="col-12">
												<p id="startTimeline" class="text-white text-element">OK, you are ready to add some memories to your timeline! Please choose the life events that you want to record today.</p>
												<div class="invite-guest">
													<div class=" timeilne-div" onclick="selectActivity(this)"><input type="checkbox" class="activity-checkbox" name="activity[]" value="Started School"><span class="text-white text-element">Started school</span>
													</div>
													<div class="timeilne-div" onclick="selectActivity(this)"><input type="checkbox" class="activity-checkbox" name="activity[]" value="Made a friend"> <span class="text-white text-element">Made a friend</span> </div>
													<div class="timeilne-div" onclick="selectActivity(this)"><input type="checkbox" class="activity-checkbox" name="activity[]" value="Graduated"> <span class="text-white text-element">Graduated</span> </div>
													<div class="timeilne-div" onclick="selectActivity(this)"><input type="checkbox" class="activity-checkbox" name="activity[]" value="Moved to a new home"><span class="text-white text-element">Moved into a new home</span> </div>
													<div class="timeilne-div" onclick="selectActivity(this)"><input type="checkbox" class="activity-checkbox" name="activity[]" value="Began first job"> <span class="text-white text-element">Began first job</span> </div>
													<div class="timeilne-div" onclick="selectActivity(this)"><input type="checkbox" class="activity-checkbox" name="activity[]" value="Fell in love"> <span class="text-white text-element">Fell in love</span> </div>
													<div class="timeilne-div" onclick="selectActivity(this)"><input type="checkbox" class="activity-checkbox" name="activity[]" value="Got Married"> <span class="text-white text-element">Got married</span> </div>
													<div class="timeilne-div" onclick="selectActivity(this)"><input type="checkbox" class="activity-checkbox" name="activity[]" value="Became a parent"><span class="text-white text-element">Became a parent</span> </div>
													<div class="timeilne-div" onclick="selectActivity(this)"><input type="checkbox" class="activity-checkbox" name="activity[]" value="Adopted a special pet"><span class="text-white text-element">Adopted a special pet</span> </div>
													<div class="timeilne-div" onclick="selectActivity(this)"><input type="checkbox" class="activity-checkbox" name="activity[]" value="Reached a faith milestone"><span class="text-white text-element">Reached a faith milestone</span></div>

												</div>
											</div>
										</div>
										<div class="row btn-box">
											<div class="text-start mb-2 col-3">
												<button type="button" onclick="back(6);" onmouseover="changeIcon(this, 'assets/images/icons/left-white.svg')"
													onmouseout="changeIcon(this, 'assets/images/icons/left.svg')" class="btn btn-white btn-next-arrow"><img src="assets/images/icons/left.svg" alt="Back" style="height: 1em; vertical-align: middle;"></button>
											</div>
											<div class="text-end mb-2 col-9">
												<button type="submit" class="btn btn-white btn-arrow border-white SContinue">Save & Close</button>
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
	</div>
	<footer class="accent-group page-footer" style="position:absolute;width:100vW; bottom:0;">
		<p class="mb-0">Copyright © <?php echo $year; ?>. All right reserved.</p>
	</footer>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
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
	<!--Password show & hide js -->
	<script>
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

			$("#show_hide_confirm_password a").on('click', function(event) {
				event.preventDefault();
				if ($('#show_hide_confirm_password input').attr("type") == "text") {
					$('#show_hide_confirm_password input').attr('type', 'password');
					$('#show_hide_confirm_password i').addClass("bx-hide");
					$('#show_hide_confirm_password i').removeClass("bx-show");
				} else if ($('#show_hide_confirm_password input').attr("type") == "password") {
					$('#show_hide_confirm_password input').attr('type', 'text');
					$('#show_hide_confirm_password i').removeClass("bx-hide");
					$('#show_hide_confirm_password i').addClass("bx-show");
				}
			});
		});
	</script>

	<script>
		function checkPassword(password) {
			const hasUpperCase = /[A-Z]/.test(password);
			const hasLowerCase = /[a-z]/.test(password);
			const hasNumber = /\d/.test(password);
			const hasMinLength = password.length >= 8;

			let errorMessage = [];

			if (!hasMinLength) {
				errorMessage.push("at least 8 characters");
			}
			if (!hasUpperCase) {
				errorMessage.push("a capital letter");
			}
			if (!hasLowerCase) {
				errorMessage.push("a lowercase letter");
			}
			if (!hasNumber) {
				errorMessage.push("a number");
			}

			return {
				isValid: errorMessage.length === 0,
				message: errorMessage.length > 0 ? "Password must contain " + errorMessage.join(", ") : ""
			};
		}

		function validate(step) {
			var firstname = $('#inputFirstName').val();
			// var lastname = $('#inputLastName').val();
			// var inputEmailAddress = $('#inputEmailAddress').val();
			// var inputBackupEmail = $('#inputBackupEmail').val();
			// var password = $('#inputChoosePassword').val();
			// var confirmPassword = $('#inputChooseConfirmPassword').val();
			if (step == 1) {
				let errorMessage = [];
				// $('#txtError').text('');
				// if (firstname == "") {
				// 	errorMessage.push("First name is required");
				// }
				// if (lastname == "") {
				// 	errorMessage.push("Last name is required");
				// }
				// if (inputEmailAddress == "") {
				// 	errorMessage.push("Email address is required");
				// }
				if (errorMessage.length == 0) {
					$('#step1').hide();
					$('#step2').show();
					$('#step3').hide();
				} else {
					$('#txtError').text(errorMessage);
				}

			} else if (step == 2) {
				$('#step1').hide();
				$('#step2').hide();
				$('#step3').show();


			} else if (step == 3) {

				var firstname = $('#inputFirstName').val();
				$('#step1').hide();
				$('#step2').hide();
				$('#step3').hide();
				$('#step4').show();
			} else if (step == 4) {
				$('#step1').hide();
				$('#step2').hide();
				$('#step3').hide();
				$('#step4').hide();
				$('#step5').show();
			} else if (step == 5) {
				$('#step1').hide();
				$('#step2').hide();
				$('#step3').hide();
				$('#step4').hide();
				$('#step5').hide();
				$('#step6').show();
			}

		}

		function back(step) {
			if (step == 2) {
				$('#step1').show();
				$('#step2').hide();
				$('#step3').hide();
			} else if (step == 3) {
				$('#step1').hide();
				$('#step2').show();
				$('#step3').hide();
			}
		}

		function signup() {
			var viewability = $('#VIEW_RIGHT').val();
			var firstname = $('#inputMemoir').val();
			var lastname = $('#inputLastName').val();
			var email = $('#inputEmailAddress').val();
			var backupemail = $('#inputBackupEmail').val();
			var password = $('#inputChoosePassword').val();

			var form_data = new FormData();

			form_data.append('firstname', firstname);
			form_data.append('lastname', lastname);
			form_data.append('email', email);
			form_data.append('backupemail', backupemail);
			form_data.append('password', password);
			form_data.append('VIEW_RIGHT', viewability);
			form_data.append("fileToUpload", document.getElementById('profile_pic').files[0]);

			$.ajax({
				type: 'POST',
				url: 'signup.php',
				dataType: 'text', // what to expect back from the PHP script
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				success: function(response) {
					console.log(response);
					round_success_notification("Signup successful");
					location.reload();
					// if(response.indexOf('"error"') > 0){
					// 	round_error_notification("Something went wrong, please try again.");
					// }else{
					// 	round_success_notification("Signup successful");
					// 	location.reload();
					// }

				},
				error: function(xhr, status, error) {
					console.error('Error: ' + error);
					round_error_notification(error);
				}
			});
		}
	</script>

	<script>
		$(".switcher-btn").on("click", function() {
				$(".switcher-wrapper").toggleClass("switcher-toggled")
			}), $(".close-switcher").on("click", function() {
				$(".switcher-wrapper").removeClass("switcher-toggled")
			}),


			$('#theme1').click(theme1);
		$('#theme2').click(theme2);
		$('#theme3').click(theme3);
		$('#theme4').click(theme4);
		$('#theme5').click(theme5);
		$('#theme6').click(theme6);
		$('#theme7').click(theme7);
		$('#theme8').click(theme8);
		$('#theme9').click(theme9);
		$('#theme10').click(theme10);
		$('#theme11').click(theme11);
		$('#theme12').click(theme12);
		$('#theme13').click(theme13);
		$('#theme14').click(theme14);
		$('#theme15').click(theme15);

		function theme1() {
			$('body').attr('class', 'bg-theme bg-theme1');
		}

		function theme2() {
			$('body').attr('class', 'bg-theme bg-theme2');
		}

		function theme3() {
			$('body').attr('class', 'bg-theme bg-theme3');
		}

		function theme4() {
			$('body').attr('class', 'bg-theme bg-theme4');
		}

		function theme5() {
			$('body').attr('class', 'bg-theme bg-theme5');
		}

		function theme6() {
			$('body').attr('class', 'bg-theme bg-theme6');
		}

		function theme7() {
			$('body').attr('class', 'bg-theme bg-theme7');
		}

		function theme8() {
			$('body').attr('class', 'bg-theme bg-theme8');
		}

		function theme9() {
			$('body').attr('class', 'bg-theme bg-theme9');
		}

		function theme10() {
			$('body').attr('class', 'bg-theme bg-theme10');
		}

		function theme11() {
			$('body').attr('class', 'bg-theme bg-theme11');
		}

		function theme12() {
			$('body').attr('class', 'bg-theme bg-theme12');
		}

		function theme13() {
			$('body').attr('class', 'bg-theme bg-theme13');
		}

		function theme14() {
			$('body').attr('class', 'bg-theme bg-theme14');
		}

		function theme15() {
			$('body').attr('class', 'bg-theme bg-theme15');
		}
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

		const textarea = document.getElementById('inputDetails');
		const charCount = document.getElementById('charCount');

		textarea.addEventListener('input', function() {
			const currentLength = textarea.value.length;

			charCount.textContent = `${currentLength}/500`;

			if (currentLength >= 500) {
				textarea.value = textarea.value.substring(0, 500);
			}
		});


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

		document.querySelectorAll('.letter-square').forEach((img) => {
			img.addEventListener('click', function() {
				const color = this.getAttribute('tag');

				document.querySelector('.onboarding-card').style.background = color;
				// document.querySelector('.login-container').style.background = color;

				const elementsToChange = document.querySelectorAll('.text-element');
				elementsToChange.forEach((el) => {
					el.style.color = 'black';
				});
			});
		});
	</script>
	<script>
		let selectedPurpose = "";

		function validate(step) {
			var firstname = $('#inputFirstName').val();

			if (step == 1) {

				updateStepContent();
				let errorMessage = [];

				if (errorMessage.length == 0) {
					$('#step1').hide();
					$('#step2').show();
					$('#step3').hide();
				} else {
					$('#txtError').text(errorMessage);
				}

			} else if (step == 2) {
				updateStepContent();
				$('#step1').hide();
				$('#step2').hide();
				$('#step3').show();

			} else if (step == 3) {
				// updateStepContent();
				$('#step1').hide();
				$('#step2').hide();
				$('#step3').hide();
				$('#step4').show();

			} else if (step == 4) {
				// updateStepContent();
				$('#step1').hide();
				$('#step2').hide();
				$('#step3').hide();
				$('#step4').hide();
				$('#step5').show();

			} else if (step == 5) {
				// updateStepContent();
				$('#step1').hide();
				$('#step2').hide();
				$('#step3').hide();
				$('#step4').hide();
				$('#step5').hide();
				$('#step6').show();
			}
		}

		function back(step) {
			// updateStepContent();
			for (let i = 1; i <= 6; i++) {
				$(`#step${i}`).hide();
			}
			$(`#step${step-1}`).show();

		}

		document.querySelectorAll('input[name="purpose"]').forEach((radio) => {
			radio.addEventListener('change', function() {
				selectedPurpose = this.value;
				updateStepContent();
			});
		});

		function updateStepContent() {
			const step2Text = document.getElementById("step2-text");
			const nameLabel = document.getElementById("name-label");
			const birthLabel = document.getElementById("birth-label");
			const nameInstructions = document.getElementById("name-instructions");
			const shortDescription = document.getElementById("shortDescription");
			const uploadPicture = document.getElementById("uploadPicture");
			const startTimeline = document.getElementById("startTimeline");
			const firstname = $('#inputFirstName').val();
			const inviteBtn = document.getElementById("invite");
			const profileImage = document.getElementById('previewProfile');

			if (selectedPurpose === "1137") {
				step2Text.textContent = "OK, you want to create a personal memoir. We'll focus on your memories and life events then.";
				nameLabel.textContent = "What is your name?";
				birthLabel.textContent = "When were you born?";
				nameInstructions.textContent = "Enter your name and basic facts about your life.";
				shortDescription.textContent = "Please write a short description of yourself.";

				uploadPicture.textContent = `Upload a picture of yourself (jpg or png under 8MB)`;
				startTimeline.textContent = "OK, you are ready to add some memories to your timeline! Please choose the life events that you want to record today.";
				inviteBtn.style.display = "none";
				profileImage.style.display = 'block';
			} else if (selectedPurpose === "1138") {
				step2Text.textContent = "OK, you want to create a tribute for a living loved one. We'll focus on their memories and life events.";
				nameLabel.textContent = "What is your loved one's name?";
				birthLabel.textContent = "When were they born?";
				nameInstructions.textContent = "Enter their name and basic facts about their life.";
				shortDescription.textContent = `Please write a short description of ${firstname}`;
				uploadPicture.textContent = `Upload a picture of ${firstname} (jpg or png under 8MB)`;
				startTimeline.textContent = `
				OK, you are ready to add some memories to $ {
					firstname
				}’
				s timeline!Please choose the life events that you want to record today.
				`;
				inviteBtn.style.display = "block";
				profileImage.style.display = 'none';
			} else if (selectedPurpose === "1139") {
				step2Text.textContent = "OK, you want to create a memorial for a loved one who has passed away. We'll focus on their memories and life events then.";
				nameLabel.textContent = "What is your loved one's name?";
				birthLabel.textContent = "When were they born?";
				nameInstructions.textContent = "Enter their name and basic facts about their life.";
				uploadPicture.textContent = `Upload a picture of ${firstname} (jpg or png under 8MB)`;
				shortDescription.textContent = `
				Please write a short description of $ {
					firstname
				}.
				`;
				startTimeline.textContent = `
				OK, you are ready to add some memories to $ {
					firstname
				}’
				s timeline!Please choose the life events that you want to record today.
				`;
				inviteBtn.style.display = "none";
				profileImage.style.display = 'none';
			}
		}

		document.getElementById('agreeGuestCheckbox').addEventListener('change', function() {
			const inviteGuestDiv = document.querySelector('#invite-guest');
			const guest = document.querySelector('#guest');
			const noguest = document.querySelector('#noguest');


			if (this.checked) {
				inviteGuestDiv.classList.remove('totallyHide');
				guest.classList.remove('totallyHide');
				noguest.classList.add('totallyHide');
			} else {
				inviteGuestDiv.classList.add('totallyHide');
				guest.classList.add('totallyHide');
				noguest.classList.remove('totallyHide');
			}
		});

		function selectActivity(element) {
			const allActivities = document.querySelectorAll('.timeilne-div');
			element.classList.toggle('selected-activity');
			const checkbox = element.querySelector('input[type="checkbox"]');
			if (checkbox) {
				checkbox.checked = element.classList.contains('selected-activity');
			}

		}
		document.querySelectorAll('.square').forEach(square => {
			square.addEventListener('click', () => {
				const checkbox = square.querySelector('input[type="checkbox"]');
				document.querySelectorAll('.square input[type="checkbox"]').forEach(cb => cb.checked = false);
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
		// document.querySelectorAll('.timeilne-div').forEach(square => {
		// 	square.addEventListener('click', () => {
		// 		const checkbox = square.querySelector('input[type="checkbox"]');
		// 		checkbox.checked = !checkbox.checked;
		// 		alert("Here");
		// 	});
		// });

		document.querySelectorAll('.letter-square').forEach((img) => {
			img.addEventListener('click', function() {
				const color = this.getAttribute('tag');
				const mode = this.getAttribute('mode');

				document.querySelector('.onboarding-card').style.background = color;
				// document.querySelector('.login-container').style.background = color;
				if (mode == 'dark') {
					const elementsToChange = document.querySelectorAll('.text-element');
					elementsToChange.forEach((el) => {
						el.style.setProperty('color', '#333', 'important');
					});
				} else {
					const elementsToChange = document.querySelectorAll('.text-element');
					elementsToChange.forEach((el) => {
						el.style.setProperty('color', 'white', 'important');
					});
				}

			});
		});

		function changeIcon(button, newSrc) {
			const img = button.querySelector("img");
			if (img) {
				img.src = newSrc;
			}
		}
	</script>





</body>

</html>