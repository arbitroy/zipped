<?php
include 'global.php';

if ($_SESSION["token"]) {
	header("location: timeline.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$email = $_POST['email'];
	$pwd = $_POST['password'];
	$rem = $_POST['remember'];

	$body = json_encode(
		array(
			"emailaddress" => $email,
			"password" => $pwd
		)
	);

	$response = makePostAPIcall('login', $body);



	$resp_json = json_decode($response, true);

	if (array_key_exists("token", $resp_json)) {

		session_start();

		$_SESSION["token"] = $resp_json['token'];
		$_SESSION["loggedin"] = true;
		$_SESSION["rem"] = $rem == 'on' ? true : false;
		$_SESSION["background"] = $resp_json['background'] == null ? "Summer" : $resp_json['background'];
		$_SESSION['accent'] = getAccentByValue(getValueByText($resp_json['accentcolor']));



		$tr = '';

		$response = makeGetAPICall('getDictionaries', $_SESSION["token"]);

		$resp_json = json_decode($response, true);

		if (isset($resp_json)) {
			$_SESSION["CONNECTION_TYPE"] = $resp_json['CONNECTION_TYPE'];
			$_SESSION["CONTENT_APPROVAL"] = $resp_json['CONTENT_APPROVAL'];
			$_SESSION["COUNTRY"] = $resp_json['COUNTRY'];
			$_SESSION["LIFE_LAYERS"] = $resp_json['LIFE_LAYERS'];
			$_SESSION["OCCUPATION_TYPES"] = $resp_json['OCCUPATION_TYPES'];
			$_SESSION["RELATIONSHIP"] = $resp_json['RELATIONSHIP'];
			$_SESSION["SEARCH_CRITERIA"] = $resp_json['SEARCH_CRITERIA'];
			$_SESSION["STATES"] = $resp_json['STATES'];
			$_SESSION["TITLE"] = $resp_json['TITLE'];
			$_SESSION["VIEW_RIGHT"] = $resp_json['VIEW_RIGHT'];
			$_SESSION["VIEW_RIGHT_CONNECTION"] = $resp_json['VIEW_RIGHT_CONNECTION'];
			$_SESSION["VIEW_RIGHT_DEPRECATED"] = $resp_json['VIEW_RIGHT_DEPRECATED'];
		}

		// Lets get profile details
		$detailsResponse = makeGetAPICall('getProfile', $_SESSION["token"]);
		$detailsJson = json_decode($detailsResponse, true);
		if (isset($detailsJson)) {
			$_SESSION["city"] = $detailsJson[0]['city'];
			$_SESSION["country"] = $detailsJson[0]['country'];
			$_SESSION["dateofbirth"] = $detailsJson[0]['dateofbirth'];
			$_SESSION["emailaddress"] = $detailsJson[0]['emailaddress'];
			$_SESSION["fullname"] = $detailsJson[0]['fullname'];
			$_SESSION["profileviewability"] = $detailsJson[0]['profileviewability'];
			$_SESSION["shortbio"] = $detailsJson[0]['shortbio'];
			$_SESSION["state"] = $detailsJson[0]['state'];
			$_SESSION["PROFILE_PIC"] = 'data:image/' . $detailsJson[0]['docType'] . ';base64,' . $detailsJson[0]['documentdata'];
			$name = $_SESSION["username"] = $detailsJson[0]['fullname'];
			$_SESSION['activities'] = $detailsJson[0]['memoryOptions'];
			$_SESSION['guestadmin'] = $detailsJson[0]['guestadmin'];
			$_SESSION['guestemailaddress'] = $detailsJson[0]['guestemailaddress'];
		}
		header("location: timeline.php");
	} else {
		$error = 'Incorrect email or password. Please try again.';
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
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<title>Dignitrees - Family App</title>
</head>

<body class="bg-theme bg-theme2">
	<!--wrapper-->
	<div class="wrapper">
		<div class="container  login-container">
			<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
				<div class="col">
					<div class="card-body login-card ">
						<div class="p-3">
							<div class="text-center logo_login">
								<img src="assets/images/logo-icon.png" width="40" alt="" />
							</div>
							<div class="text-center mb-4">
								<h5 class="">Dignitrees Family App</h5>
								<p class="mb-0 text-white opacity-90">Please sign in to your account.</p>
							</div>
							<div class="form-body">
								<form class="row g-3 login-form" action="index.php" method="POST">
									<div class="col-12">
										<label for="inputEmailAddress" class="form-label text-white">Email</label>
										<input type="email" class="form-control border-white" id="inputEmailAddress" name="email" placeholder="john@example.com">
									</div>
									<div class="col-12">
										<label for="inputChoosePassword" class="form-label text-white">Password</label>
										<div class="input-group" id="show_hide_password">
											<input type="password" class="form-control border-end-0 border-white" id="inputChoosePassword" name="password" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent border-white"><i class='bx bx-hide'></i></a>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-check form-switch gap-3 d-flex align-items-center">
											<input class="form-check-input border-white" type="checkbox" name="remember" id="flexSwitchCheckChecked">
											<label class="form-check-label text-white" for="flexSwitchCheckChecked">Remember Me</label>
										</div>
									</div>


									<div class="col-12">
										<p class="mb-0 error_message"><?php echo $error; ?></p>
										<div class="col-md-12 col-12">
											<div class="col-12"></div>
											<div class="text-center mb-2">
												<div class="d-flex align-items-center justify-content-center mt-3 py-2" style="border-top:3px solid white;">
													<button id="btnSignin" class="btn btn-white d-flex align-items-center justify-content-center btn-hover-white">Sign in</button>
												</div>
												<div id="loading" style="display:none;" class="spinner-border" role="status"> <span class="visually-hidden">Loading...</span>
												</div>
											</div>

											<div class="text-center mb-2">
												<p class="mb-0">

													<a href="forgot-password.php"><u>Forgot Password ?</u></a>

												</p>
											</div>
											<div class="text-center ">
												<p class="mb-0">
													<a href="signup.php"><u>Create an account</u></a>

												</p>
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
	</div>
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
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
		});
	</script>


	<script>
		function showLoader() {
			$('#loading').show();
			$('#btnSignin').prop('disabled', true)
			// $('#div_company_add').hide();
		};

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
		const token = <?php echo json_encode($_SESSION["token"]); ?>;
		const rememberMe = <?php echo json_encode($_SESSION["rem"]); ?>;
		const username = <?php echo json_encode($_SESSION["username"]); ?>;
		sessionStorage.setItem("token", token);
		sessionStorage.setItem("remember", rememberMe);
		sessionStorage.setItem("username", username);

		console.log("Token set in Session Storage:", sessionStorage.getItem("token"));
	</script>


</body>

</html>