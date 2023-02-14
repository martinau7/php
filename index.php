<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <link href="assets/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Project PHP</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

</head>

<body>
<h1 style="text-align: center;margin: 0rem 2rem 2rem 0rem">Task</h1>
<button id="openModalButton" style="margin: 0 0 1rem 0;background-color: lightseagreen;font-family: Ebrima">Open Modal
</button>
<!-- Modal elements -->
<div id="modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Select an Image</h2>
            <div id="closeModalButton">Click outside the Modal to close it</div>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js">
    $(document).ready(function () {
        // Request data from the API
        function getData() {
            $.ajax({
                type: 'GET',
                url: 'https://api.baubuddy.de/dev/index.php/v1/tasks/select',
                success: function (data) {
                    updateTable(data);
                }
            });
        }

        // Update the table with the new data
        function updateTable(data) {
            $('#dataTable tr:not(:first)').remove();
            $.each(data, function (index, item) {
                var row = '<tr>' +
                    '<td>' + item.task + '</td>' +
                    '<td>' + item.title + '</td>' +
                    '<td>' + item.description + '</td>' +
                    '<td><div class="colorCode" style="background-color: ' + item.colorCode + '"></div>' + item.colorCode + '</td>' +
                    '</tr>';
                $('#dataTable').append(row);
            });
        }

        // Auto-refresh functionality
        setInterval(getData, 60 * 60 * 1000);
        getData();
    });
</script>
</body>

</html>