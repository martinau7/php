// Get the modal and open modal button
var modal = document.getElementById("modal");
var btn = document.getElementById("openModal");

// Get the close button
var close = document.getElementsByClassName("close")[0];

// Get the image input and preview elements
var imageInput = document.getElementById("imageInput");
var preview = document.getElementById("preview");

// Open the modal when the button is clicked
btn.onclick = function () {
    modal.style.display = "block";
}

// Close the modal when the close button is clicked
close.onclick = function () {
    modal.style.display = "none";
}

// When the user selects an image file, display the image in the preview
imageInput.onchange = function () {
    var file = imageInput.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
        preview.src = e.target.result;
    }
    reader.readAsDataURL(file);
}

// Close the modal when the user clicks outside of it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

