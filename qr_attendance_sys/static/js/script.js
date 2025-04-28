function showImage(event) {
  const uploadedImage = document.getElementById("uploadedImage");
  uploadedImage.src = URL.createObjectURL(event.target.files[0]);
  uploadedImage.style.display = "block";
  // Optionally hide the upload icon
  document.querySelector(".upload-icon").style.display = "none";
}

function resetForm() {
  var form = document.getElementById("registrationForm");
  form.reset();

  // Reset image preview
  var image = document.getElementById("uploadedImage");
  var uploadIcon = document.querySelector(".upload-icon");
  var uploadInput = document.getElementById("uploadImage");

  uploadInput.value = ""; // Clear the file input
  image.src = "https://via.placeholder.com/150"; // Reset to placeholder
  image.style.display = "none"; // Hide the image
  uploadIcon.style.display = "block"; // Show the upload icon
}
