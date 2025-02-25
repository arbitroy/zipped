<?php

include 'global.php';

$tr = '';

$response = makeGetAPICall('getConnections', $_SESSION["token"]);

$resp_json = json_decode($response, true);

if (sizeof($resp_json) > 0 && isset($resp_json[0]['connectionType'])) {
	foreach ($resp_json as $item) {
		$item['connectionType'] == 'Family' ?
			$tr .= '<tr>
		<td>' . $item['PersonName'] . '</td>
		<td>' . $item['Relationship'] . '</td>
		<td><a class="list-inline-item" onclick="deleteConnection(' . $item['RecordId'] . ',\'' . $item['PersonName'] . '\')"><i class="bx bx-trash"></i></a></td>
		</tr>' : $tr .= '';
	}
}

$viewability = mapOptionSets('VIEW_RIGHT_CONNECTION');

$relationship = mapOptionSets('RELATIONSHIP');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$title = $_POST['RELATIONSHIP'];
	$view = $_POST['VIEW_RIGHT_CONNECTION'];
	$famName = $_POST['famName'];
	$email = $_POST['famEmail'];
	$connectionType = getOptionID('CONNECTION_TYPE', 'Family');

	/*
		"personname": "Jeremiah",
    "familytitle": "1031",
    "viewabilityright": "1128",
    "connectiontype": "1097",
    "emailaddress": "jerrydindi@gmail.com",
    "username": "Dede Dindi"
	*/

	$body = json_encode(
		array(
			"personname" => $famName,
			"familytitle" => $title,
			"viewabilityright" => $view,
			"connectiontype" => $connectionType,
			"emailaddress" => $email,
			"username" => $name
		)
	);

	$response = makePostAPIcall('addConnection', $body, $_SESSION["token"]);

	// $resp_json = json_decode($response, true);

	// echo '<script type="text/JavaScript">  
	// round_success_noti(); 
	//  </script>';

	// if (array_key_exists("error", $resp_json)) {
	// 	// echo '<script type="text/JavaScript">  
	// 	// alert("' . $resp_json['error'] . '"); 
	// 	//  </script>';
	// 	echo '<script type="text/JavaScript">  
	// 	round_error_notification("' . $resp_json['error'] . '");
	// 	 </script>';
	// } else {
	// 	echo '<script type="text/JavaScript">  
	// 	round_success_notification("Connection added successfully");
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
	<link rel="stylesheet" href="assets/plugins/notifications/css/lobibox.min.css" />
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
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
		<!--sidebar wrapper -->
		<?php include 'nav/sidebar.php'; ?>
		<!--end sidebar wrapper -->
		<!--start header -->
		<?php include 'nav/header.php'; ?>
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Dignitrees</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Family Tree</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				<!--end breadcrumb-->

				<div class="container">
					<div class="main-body">
						<div class="row">
							<div class="col-lg-6">
								<div class="card">
									<div class="card-body">
										<h5 class="mb-1">Add Family Members</h5>
										</br>
										<form class="row g-3">
											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Full Name</h6>
												</div>
												<div class="col-sm-9">
													<input type="text" class="form-control" name="famName" id="famName" />
												</div>
											</div>

											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Relationship</h6>
												</div>
												<div class="col-sm-9">
													<?php echo $relationship; ?>
												</div>
											</div>

											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Email</h6>
												</div>
												<div class="col-sm-9">
													<input type="text" class="form-control" name="famEmail" id="famEmail" />
												</div>
											</div>

											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Viewability</h6>
												</div>
												<div class="col-sm-9">
													<?php echo $viewability; ?>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-3"></div>
												<div class="col-sm-9">
													<input type="button" onclick="addConnection();" class="btn btn-light px-4" value="Add Member" />
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="card">
									<div class="card-body">
										<div class="d-flex align-items-center">
											<div>
												<h5 class="mb-1">Family Members</h5>
											</div>
										</div>
										<div class="table-responsive mt-4">
											<table class="table align-middle mb-0 table-hover" id="Transaction-History">
												<thead class="table-light">
													<tr>
														<th>Name</th>
														<th>Relationship</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php echo $tr; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!--end row-->
			</div>
		</div>
		<!--end page wrapper -->
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
			<hr/>
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
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
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
	<script src="assets/js/index.js"></script>
	<!--notification js -->
	<script src="assets/plugins/notifications/js/lobibox.min.js"></script>
	<script src="assets/plugins/notifications/js/notifications.min.js"></script>
	<script src="assets/plugins/notifications/js/notification-custom-script.js"></script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
	<script>
		new PerfectScrollbar('.product-list');
		new PerfectScrollbar('.customers-list');
	</script>
	<!--custom -->
	<script>
		function addConnection() {
			// alert(id);
			var famEmail = $('#famEmail').val();
			var famName = $('#famName').val();
			var connection = $('#VIEW_RIGHT_CONNECTION').val();
			var relationship = $('#RELATIONSHIP').val();

			if (famEmail == "" || famName == "" || connection == "Select Option" || relationship == "Select Option") {
				round_error_notification("Fill in all the fields");
			} else {
				var form_data = new FormData();
				form_data.append('RELATIONSHIP', relationship);
				form_data.append('VIEW_RIGHT_CONNECTION', connection);
				form_data.append('famName', famName);
				form_data.append('famEmail', famEmail);

				$.ajax({
					type: 'POST',
					url: 'family-tree.php',
					dataType: 'text', // what to expect back from the PHP script
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,
					success: function(response) {
						console.log(response);
						round_success_notification("Connection added successfully");
						location.reload();
					},
					error: function(xhr, status, error) {
						console.error('Error: ' + error);
						round_error_notification(error);
					}
				});
			}
		}

		function deleteConnection(id, name) {
			// alert(id);
			if (confirm("Detele " + name + "?")) {
				var form_data = new FormData();
				form_data.append('id', id);

				$.ajax({
					type: 'POST',
					url: 'delete-connection.php',
					dataType: 'text', // what to expect back from the PHP script
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,
					success: function(response) {
						console.log(response);
						round_success_notification("Connection deleted successfully");
						location.reload();
					},
					error: function(xhr, status, error) {
						console.error('Error: ' + error);
					}
				});

			}
		}
	</script>
</body>

</html>