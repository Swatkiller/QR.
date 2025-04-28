<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="icon" class="image/x-icon" href="./images/logo.png">
    <?php require './header.php'?>
    <style>
        /* Basic styling for validation messages */
        input:invalid {
            border-color: red;
        }
        input:valid {
            border-color: green;
        }
        .uploaded-image {
            cursor: pointer;
            width: 150px; /* Set width for the uploaded image */
            height: auto; /* Maintain aspect ratio */
        }
    </style>
    <script>
        function showImage(event) {
            const uploadedImage = document.getElementById('uploadedImage');
            uploadedImage.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
</head>

<body>
    <div class="brand-container">
        <a class="logo-brand" href="./dashboard.php">
            <img class="logo" src="./images/logo.png" alt="logo" />
        </a>
    </div>

    <div class="attendance_topic">
        <h1>STUDENT REGISTRATION PORTAL</h1>
    </div>

    <div class="details_container">
        <section class="h-100">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col">
                        <div class="card card-registration my-4">
                            <div class="row g-0">
                                <div class="col-xl-10 mx-auto">
                                    <div class="card-body p-md-5 text-black">
                                        <form id="registrationForm" method="post" action="./backend/reg_form.php" enctype="multipart/form-data">
                                            <div class="row align-items-start">
                                                <div class="col-md-4 mb-2">
                                                    <div class="name-form-outline">
                                                        <input type="text" id="firstname" class="form-control form-control-lg" name="firstname" required />
                                                        <label class="form-label" for="firstname">First name</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-0">
                                                    <div class="name-form-outline">
                                                        <input type="text" id="lastname" class="form-control form-control-lg" name="lastname" required />
                                                        <label class="form-label" for="lastname">Last name</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-2 position-relative">
                                                    <div class="upload-container">
                                                        <input type="file" id="uploadImage" class="form-control form-control-lg file-input" name="profile_image" accept="image/*" required onchange="showImage(event)" />
                                                        <label class="custom-upload" for="uploadImage">
                                                            <img src="https://via.placeholder.com/150" alt="Upload Icon" class="upload-icon" />
                                                        </label>
                                                        <img id="uploadedImage" src="https://via.placeholder.com/150" alt="Uploaded Image" class="uploaded-image" onclick="document.getElementById('uploadImage').click();" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-8 mb-0">
                                                    <div class="address-form-outline">
                                                        <input type="text" id="address" class="form-control form-control-lg" name="address" required />
                                                        <label class="form-label" for="address">Address</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-outline">
                                                        <input type="text" id="fathers_name" class="form-control form-control-lg" name="fathers_name" required />
                                                        <label class="form-label" for="fathers_name">Father's name</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-outline">
                                                        <input type="text" id="mothers_name" class="form-control form-control-lg" name="mothers_name" required />
                                                        <label class="form-label" for="mothers_name">Mother's name</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-outline">
                                                        <input type="text" id="grade" class="form-control form-control-lg" name="grade" placeholder="1-10" required pattern="([1-9]|10)" title="Grade must be a number between 1 and 10." />
                                                        <label class="form-label" for="grade">Grade</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-outline mb-2">
                                                        <input type="text" id="section" class="form-control form-control-lg" name="section" placeholder="A-B-C-D" required pattern="([A-D]|[a-d])" title="Section must be A, B, C, or D." oninput="validateSection()" />
                                                        <label class="form-label" for="section">Section</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row align-items-center mb-4">
                                                <div class="col-md-6">
                                                    <h6 class="mb-3 me-2">Gender:</h6>
                                                    <div class="form-check form-check-inline mb-0 me-4">
                                                        <input class="form-check-input" type="radio" name="gender" id="maleGender" value="Male" required />
                                                        <label class="form-check-label" for="maleGender">Male</label>
                                                    </div>

                                                    <div class="form-check form-check-inline mb-0 me-5">
                                                        <input class="form-check-input" type="radio" name="gender" id="femaleGender" value="Female" />
                                                        <label class="form-check-label" for="femaleGender">Female</label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-0">
                                                        <input class="form-check-input" type="radio" name="gender" id="otherGender" value="Other" />
                                                        <label class="form-check-label" for="otherGender">Other</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-outline mb-2">
                                                        <input type="text" id="dob" class="form-control form-control-lg" name="dob" placeholder="YYYY-MM-DD" required pattern="\d{4}-\d{2}-\d{2}" title="Date must be in the format YYYY-MM-DD." />
                                                        <label class="form-label" for="dob">Date of Birth</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-start mb-4">
                                                <div class="col-md-6">
                                                    <div class="form-outline">
                                                        <input type="tel" id="mobile_no" class="form-control form-control-lg" name="mobile_no" placeholder="9800000000" pattern="98[0-9]{8}" required title="Mobile number should start with 98 and be exactly 10 digits long." />
                                                        <label class="form-label" for="mobile_no">Mobile No</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                <div class="form-outline mb-2">
                                                    <input type="email" 
                                                           id="email" 
                                                           class="form-control form-control-lg" 
                                                           name="email" 
                                                           placeholder="******@gmail.com" 
                                                           required 
                                                           pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" 
                                                           title="Please enter a valid email address." />
                                                    <label class="form-label" for="email">Email ID</label>
                                                </div>

                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end pt-3">
                                                <button type="reset" value="reset" class="btn btn-light btn-lg mx-2">Reset all</button>
                                                <button type="submit" class="btn btn-warning btn-lg ms-2">Submit form</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="reg-footer">
        <?php require './footer.php'?>
    </div>
</body>
<script src="../static/js/script.js"></script>


</html>
