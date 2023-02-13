<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Project PHP</title>
    <script>
        function refreshTable() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    document.getElementById("table_body").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "get_data.php", true);
            xhttp.send();
        }
    </script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            width: 80%;
        }

        .modal-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .modal-body {
            text-align: center;
        }

        .modal-footer {
            text-align: center;
            margin-top: 20px;
        }

        .image-preview {
            width: 200px;
            height: 200px;
            object-fit: cover;
            margin: 20px auto;
            border: 1px solid #ccc;
        }

        h5{
            margin: 0rem 0rem 2rem 0rem;
        }
    </style>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <script>
        function searchTable() {
            // Get the search term
            var input = document.getElementById("searchInput").value;
            // Check if the search term is empty
            if (input.trim() === "") {
                // Clear any previous highlights
                var rows = document.querySelectorAll("table tbody tr");
                for (var i = 0; i < rows.length; i++) {
                    rows[i].style.backgroundColor = "";
                }
                return;
            }
            // Get all the rows in the table
            var rows = document.querySelectorAll("table tbody tr");
            // Loop through each row
            for (var i = 0; i < rows.length; i++) {
                // Get the cells in the current row
                var cells = rows[i].getElementsByTagName("td");
                // Check if any of the cells match the search input
                var found = false;
                for (var j = 0; j < cells.length; j++) {
                    if (cells[j].innerHTML.toLowerCase().indexOf(input.toLowerCase()) > -1) {
                        found = true;
                        break;
                    }
                }
                // If a match is found, highlight the row
                if (found) {
                    rows[i].style.backgroundColor = "lightsalmon";
                } else {
                    rows[i].style.backgroundColor = "";
                }
            }
        }
    </script>
</head>

<body onload="setInterval(refreshTable, 60 * 60 * 1000)"><!-- Implementing auto-refresh functionality -->
<h1 style="text-align: center;margin: 0rem 2rem 2rem 0rem">Task</h1>
<button id="openModalButton" style="margin: 0 0 1rem 0;background-color: lightseagreen;font-family: Ebrima">Open Modal
</button>
<!-- Modal elements -->
<div id="modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Select an Image</h2>
            <button id="closeModalButton">Close</button>
        </div>
        <div class="modal-body">

            <img id="imagePreview" class="image-preview" alt="">
            <br>
            <input type="file" id="imageInput" accept="image/*">
        </div>
        <div class="modal-footer">
            <button id="selectImageButton">Select Image</button>
        </div>
    </div>
</div>

<script>
    // Get the modal
    var modal = document.getElementById("modal");

    // Get the open modal button
    var openModalButton = document.getElementById("openModalButton");

    // Get the image input
    var imageInput = document.getElementById("imageInput");

    // Get the select image button
    var selectImageButton = document.getElementById("selectImageButton");

    // Get the image preview
    var imagePreview = document.getElementById("imagePreview");

    // When the open modal button is clicked, show the modal
    openModalButton.addEventListener("click", function () {
        modal.style.display = "block";
    });

    // When the select image button is clicked, open the file selector
    selectImageButton.addEventListener("click", function () {
        imageInput.click();
    });

    // When a file is selected, display the preview
    imageInput.addEventListener("change", function () {
        var reader = new FileReader();
        reader.onload = function (e) {
            imagePreview.src = e.target.result;
        };
        reader.readAsDataURL(imageInput.files[0]);
    });
    // Get the close modal button
    var closeModalButton = document.getElementById("closeModalButton");

    // When the close modal button is clicked, close the modal
    closeModalButton.addEventListener("click", function () {
        modal.style.display = "none";
        imagePreview.src = "";
    });

    // Close the modal when the user clicks outside of it
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
            imagePreview.src = "";
        }
    });

</script>

<h5 style="margin: 0 0 1rem 0">Search table</h5>
<input type="text" id="searchInput" onkeyup="searchTable()" style="margin: 0 0 1rem 0;width: 40rem">
<?php
// Get the access token
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.baubuddy.de/index.php/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\"username\":\"365\", \"password\":\"1\"}",
    CURLOPT_HTTPHEADER => [
        "Authorization: Basic QVBJX0V4cGxvcmVyOjEyMzQ1NmlzQUxhbWVQYXNz",
        "Content-Type: application/json"
    ],
]);
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $data = json_decode($response, true);
    $access_token = $data['oauth']['access_token'];
}

// Request the data from the API
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.baubuddy.de/dev/index.php/v1/tasks/select",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $access_token"
    ],
]);
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $data = json_decode($response, true);
}
// Display the data in a table
echo "<table border='1' id='mytable' style='border:2rem'>";
echo "<tr>";
echo "<th style='background-color: lightseagreen'>Task</th>";
echo "<th style='background-color: lightseagreen'>Title</th>";
echo "<th style='background-color: lightseagreen'>Description</th>";
echo "<th style='background-color: lightseagreen'>Color Code</th>";
echo "</tr>";
foreach ($data as $row) {
    echo "<tr>";
    echo "<td>" . $row['task'] . "</td>";
    echo "<td>" . $row['title'] . "</td>";
    echo "<td>" . $row['description'] . "</td>";
    echo "<td style='background-color:" . $row['colorCode'] . ";'>" . $row['colorCode'] . "</td>";
    // Row 229 changes the background color to the color code mentioned in the table row colorCode
}
?>
<script src="app.js"></script>

</body>

</html>