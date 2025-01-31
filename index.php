<?php
// The data you want to encode in the QR code
$data = 'localhost/form_page.php';
// $data = 'https://forms.office.com/Pages/ResponsePage.aspx?id=00dqnpUnl0ueUnixBgYp8QNaw1i-Q7BAvo2qfTNr5Q9UQ01OWEVHTzg3U1AyWUlFRzVHM05KWE9QVy4u';


// The API endpoint with the data and size parameters
$qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($data);

// Use file_get_contents to fetch the image data
$qrImage = file_get_contents($qrApiUrl);

// Encode the image data into base64
$qrBase64 = base64_encode($qrImage);

if (isset($_GET['action']) && $_GET['action'] === 'getNames') {
    // Path to the text file that stores names
    $namesFile = 'names.txt';

    // Load the names already submitted
    $names = file_exists($namesFile) ? file($namesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

    // Output the names as JSON
    header('Content-Type: application/json');
    echo json_encode($names);
    exit; // Make sure to terminate the script so it doesn't run the HTML part
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
    <style>
        /* Center the content on the page */
		body {
			height: 100%;
			width: 100%;
			padding: 0;
            margin: 0;
			background-image: url("https://cdn.eduadvisor.my/institution/xiamen/inst-profile-header-xiamen.jpg");
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
		}
		.banner {
			display: flex;
			block-size: 70px;
			background-color: #00008B;
			align-items: left;
			position: sticky;
			backdrop-filter: blur(14px);
			
		}
		.page {
			padding-top: 0;
			margin-top: 0;
			height: 100%;
			width: 100%;
            flex-direction: column;
			
		}
		.base-component {
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
			gap: 90px;
			justify-content: center;
			margin-block-start: 90px;
			align-items: stretch;
			margin-top: 90px;
			margin-bottom: 90px;
			margin-left: auto;
			margin-right: auto;
			margin-inline: 30px;
			inline-size: auto;
			position: relative;
			
			
		}
		.item-component {
			height: auto;
			block-size: auto;
			text-align: center;
			width: 650px;
			border-radius: 16px;
			box-shadow: 17px 20px 40px rgba(0,0,0,0.21);
			display: flex;
			flex-direction: column;
			padding: 40px;
			inline-size: 450px;
			background-color: rgba(255,255,255,0.5);
			backdrop-filter: blur(14px);
			
		}
		
		.qr {
			block-size: 300px;
			margin-block-start: 20px;/*
			
            align-items: center;
            */ /* Ensure text is centered within the flex item */
			
			
		}
		

    </style>
</head>
<body>
	<header>
		<div class = "banner">
			<img src = "https://www.xmu.edu.my/_upload/tpl/08/9f/2207/template2207/htmlRes/xxde_022.png" alt = "XMUM Logo" >
		</div>
	</header>
	<div class = "page">
		<div class = "base-component">
			<div class = "item-component">
				<div class = "qr">
					<h1>Student, Please Scan Here for Attendance!</h1>
					<!-- Display the QR code -->
					<img src="data:image/png;base64, <?php echo $qrBase64; ?>" alt="QR Code">
				</div>
			</div>
			<div class = "item-component">
				<!-- The board where names will be displayed -->
				<div id="board">
					<h2>Attendance Board</h2>
					<ul id="nameList"></ul>
				</div>
			</div>
		</div>
	</div>



    <script>
    // Function to update the board
    function updateBoard() {
        const xhr = new XMLHttpRequest();
        // Assuming the PHP code to return names is part of index.php, we add a query string to distinguish the request
        xhr.open('GET', 'index.php?action=getNames', true);
        xhr.onload = function() {
            if (this.status === 200) {
                // Parse the JSON response
                const names = JSON.parse(this.responseText);
                const nameList = document.getElementById('nameList');
                nameList.innerHTML = ''; // Clear the current list
                names.forEach(function(name) {
                    const li = document.createElement('li');
                    li.textContent = name;
                    nameList.appendChild(li);
                });
            }
        };
        xhr.send();
    }

    // Call updateBoard every 5 seconds to get new names
    setInterval(updateBoard, 5000);
    updateBoard(); // Also update when the page loads
	</script>
</body>
</html>