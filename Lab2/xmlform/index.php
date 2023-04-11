<?php
session_start();

$xml=simplexml_load_file("employee.xml") or die("Error: Cannot Find xml File");

$count = count($xml);

if (isset($_SESSION["index"])) {
    $index = intval($_SESSION['index']);
} else {
    $index = 0;
    $_SESSION["index"] = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "prev" && $index>0) {
        $index = $index - 1;
    }

    if ($_POST["action"] == "next" && $index<($count)) {
        $index = $index + 1;
    }
    /////////////////////////////////////////////////
    if ($_POST["action"] == "delete") {
        $employeeToDelete = null;

        $check = 0;
        foreach ($xml->employee as $employee) {
            if ($check == intval($_SESSION["index"])) {
                $employeeToDelete = $employee;
                break;
            }
            $check++;
        }
        unset($employeeToDelete[0]);
        $xml->asXML('employee.xml');
    }
    ///////////////////////////////////////////////
    if ($_POST["action"] == "update") {
        $check = 0;
        foreach ($xml->employee as $employee) {
            if ($check == intval($_SESSION["index"])) {
                $employee->name = $_POST['name'];
                $employee->phones->phone = $_POST["phone"];
                $employee->email = $_POST['email'];
                $employee->addresses[0]->address->street = $_POST['street'];
                $employee->addresses[0]->address->buildingNum = $_POST['buildingNum'];
                $employee->addresses[0]->address->region = $_POST['region'];
                $employee->addresses[0]->address->city = $_POST['city'];
                $employee->addresses[0]->address->country = $_POST['country'];
                break;
            }
            $check++;
        }
        $xml->asXML('employee.xml');
    }
    //////////////////////////////////////////////////////////
    if ($_POST["action"] == "insert") {
        $index = $count;
    }
    //////////////////////////////////////////////////////////
    if ($_POST["action"] == "save") {
        $fields = array("name", "phone", "email", "street", "buildingNum", "region", "city", "country");
        foreach ($fields as $field) {
            if (empty($_POST[$field])) {
                if ($_POST[$field] == "buildingNum") {
                    $formError = "Building Number can't be empty";
                    exit;
                }
                $formError = $field . " can't be empty";
                exit;
            }
        }

        $employee = $xml->addChild('employee');
        $employee->addChild('name', $_POST['name']);
        $employee->addChild('email', $_POST['email']);
        $employee->addChild('phones');
        $employee->addChild('addresses');
        $employee->phones->addChild("phone", $_POST["phone"]);
        $employee->addresses->addChild("address");
        $employee->addresses->address->addChild("street", $_POST["street"]);
        $employee->addresses->address->addChild("buildingNum", $_POST["buildingNum"]);
        $employee->addresses->address->addChild("region", $_POST["region"]);
        $employee->addresses->address->addChild("city", $_POST["city"]);
        $employee->addresses->address->addChild("country", $_POST["country"]);
        $xml->asXML('employee.xml');
    }
    /////////////////////////////////////////////////////////////////
    if ($_POST["action"]=="search") {
        $email = $_POST["searchEmail"];
        $searchCounter = 0;
        foreach ($xml->employee as $employee) {
            if ($email == $employee->email) {
                $index = $searchCounter;
            }
            $searchCounter++;
        }
        $formError = "Email Not Found";
    }
    $_SESSION["index"] = $index;
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>PHP FORM</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
	<link href="./assets/main.css" rel="stylesheet">
</head>

<body>

	<div class="container">
		<form method="post" id="mainForm" class="">
			<div class="row">

				<div class="offset-xl-3 col-xl-8 col-lg-12">
					<div class="w-75 px-5  form-container" id="makeMaxWidth">

						<h3 class="main-title">Employee</h3>
						<p class="text-danger fw-bold">
							<?php $formError ?>
						</p>
						<label for="exampleInputUser1" class="form-label fw-bold">Name</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-user fa-lg"></i></span>
							</div>
							<input type="text" class="form-control" id="exampleInputUser1" name="name"
								value="<?php echo $xml->employee[$index]->name ?? ""; ?>">
						</div>

						<label for="exampleInput2" class="form-label fw-bold">Phone</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa-solid fa-phone fa-shake fa-lg"></i></span>
							</div>
							<input type="text" class="form-control" id="exampleInput2" name="phone"
								value="<?php  echo $xml->employee[$index]->phones->phone ?? ""; ?>">
						</div>

						<label for="exampleInput3" class="form-label fw-bold">Email</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa-solid fa-envelope fa-beat fa-lg"></i></span>
							</div>
							<input type="text" class="form-control" id="exampleInput3" name="email"
								value="<?php echo $xml->employee[$index]->email ?? ""?>">
						</div>

						<h5 class="form-label fw-bold text-center">Address</h5>
						<hr />
						<div class="row">

							<div class="col-6 text-center">
								<label for="exampleInput4" class="form-label">Street</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i
												class="fa-sharp fa-solid fa-address-card fa-beat fa-lg"></i></span>
									</div>
									<input type="text" class="form-control" name="street" id="exampleInput4"
										value="<?php echo $xml->employee[$index]->addresses->address->street ?? ""?>">
								</div>
							</div>

							<div class="col-6 text-center">
								<label for="exampleInput5" class="form-label">Building Number</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i
												class="fa-sharp fa-solid fa-address-card fa-beat fa-lg"></i></span>
									</div>
									<input type="text" class="form-control" name="buildingNum" id="exampleInput5"
										value="<?php echo $xml->employee[$index]->addresses->address->buildingNum ?? ""?>">
								</div>
							</div>
						</div>

						<div class="row">

							<div class="col-4">
								<label for="exampleInput6" class="form-label">Region</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i
												class="fa-sharp fa-solid fa-address-card fa-beat fa-lg"></i></span>
									</div>
									<input type="text" class="form-control" name="region" id="exampleInput6"
										value="<?php echo $xml->employee[$index]->addresses->address->region ?? ""?>">
								</div>
							</div>

							<div class="col-4">
								<label for="exampleInput7" class="form-label">City</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i
												class="fa-sharp fa-solid fa-address-card fa-beat fa-lg"></i></span>
									</div>
									<input type="text" class="form-control" name="city" id="exampleInput7"
										value="<?php echo $xml->employee[$index]->addresses->address->city ?? ""?>">
								</div>
							</div>
							<div class="col-4">
								<label for="exampleInput8" class="form-label">Country</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i
												class="fa-sharp fa-solid fa-address-card fa-beat fa-lg"></i></span>
									</div>
									<input type="text" class="form-control" name="country" id="exampleInput8"
										value="<?php echo $xml->employee[$index]->addresses->address->country ?? ""?>">
								</div>
							</div>
						</div>
						<hr />
						<div class="actions-container">
							<button class="arrows" type="submit" name="action" value="prev"><i
									class="fa-sharp fa-solid fa-left-long fa-lg"></i></button>

							<button class="login-button" type="submit" name="action" value="insert">Insert</button>

							<button class="login-button" type="button" id="search">Search</button>

							<button class="login-button" type="submit" name="action" value="update">Update</button>

							<button class="actions" type="submit" name="action" value="delete"><i
									class="fa-solid fa-trash"></i></button>

							<button class="actions" type="submit" name="action" value="save"><i
									class="fa-solid fa-floppy-disk"></i></button>

							<button class="arrows" type="submit" name="action" value="next"><i
									class="fa-solid fa-right-long fa-lg"></i></button>
						</div>
					</div>
				</div>

			</div>
		</form>

		<div class="search-container col-8 offset-2 text-center hide" id="popSearch">
			<form method="post">
				<button type="button" id="close" class="close"><span>&times;</span></button>
				<h3>Search Employee by Email</h3>
				<div class="row">
					<div class="col-8 offset-2 my-3">
						<input type="text" class="form-control search-input" name="searchEmail"
							placeholder="Employee Email" required />
					</div>
				</div>
				<button class="search-button" type="submit" name="action" value="search">Search</button>
			</form>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
	</script>

	<script>
		var search = document.getElementById("search");
		var close = document.getElementById("close");
		var form = document.getElementById("mainForm");
		var popSearch = document.getElementById("popSearch");

		search.addEventListener("click", () => {
			popSearch.classList.remove("hide");
			popSearch.classList.add("search-container");
		});

		close.addEventListener("click", () => {
			popSearch.classList.remove("search-container");
			popSearch.classList.add("hide");
		});
	</script>

	<script src="https://kit.fontawesome.com/212d832ea4.js" crossorigin="anonymous"></script>
</body>

</html>