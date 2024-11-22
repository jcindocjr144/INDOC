function home() {
    document.getElementById("detailsSection").style.display = "none";
    document.getElementById("studentsSection").style.display = "none";
    document.getElementById("addClassSection").style.display = "none";
    document.getElementById("classesSection").style.display = "none";
    document.getElementById("grade").style.display = "none";
    document.getElementById("view-grades").style.display = "none";
}
function toggleGrades() {
    document.getElementById("grade").style.display = "block";
    document.getElementById("detailsSection").style.display = "none";
    document.getElementById("studentsSection").style.display = "none";
    document.getElementById("addClassSection").style.display = "none";
    document.getElementById("classesSection").style.display = "none";
    document.getElementById("view-grades").style.display = "none";
}

function toggleViewGrades() {
    document.getElementById("view-grades").style.display = "block";
    document.getElementById("grade").style.display = "none";
    document.getElementById("detailsSection").style.display = "none";
    document.getElementById("studentsSection").style.display = "none";
    document.getElementById("addClassSection").style.display = "none";
    document.getElementById("classesSection").style.display = "none";
}

function toggleDetails() {
    document.getElementById("detailsSection").style.display = "block";
    document.getElementById("studentsSection").style.display = "none";
    document.getElementById("addClassSection").style.display = "none";
    document.getElementById("classesSection").style.display = "none";
    document.getElementById("grade").style.display = "none";
    document.getElementById("view-grades").style.display = "none";
}

function toggleStudents() {
    document.getElementById("studentsSection").style.display = "block";
    document.getElementById("detailsSection").style.display = "none";
    document.getElementById("addClassSection").style.display = "none";
    document.getElementById("classesSection").style.display = "none";
    document.getElementById("grade").style.display = "none";
    document.getElementById("view-grades").style.display = "none";
}

function toggleAddClass() {
    document.getElementById("addClassSection").style.display = "block";
    document.getElementById("studentsSection").style.display = "none";
    document.getElementById("detailsSection").style.display = "none";
    document.getElementById("classesSection").style.display = "none";
    document.getElementById("grade").style.display = "none";
    document.getElementById("view-grades").style.display = "none";
}

function toggleClasses() {
    document.getElementById("classesSection").style.display = "block";
    document.getElementById("studentsSection").style.display = "none";
    document.getElementById("detailsSection").style.display = "none";
    document.getElementById("addClassSection").style.display = "none";
    document.getElementById("grade").style.display = "none";
    document.getElementById("view-grades").style.display = "none";
}
function viewStudents(classId) {
    $.ajax({
        url: 'fetch_students.php',
        method: 'POST',
        data: { class_id: classId },
        success: function(response) {
            $('#studentList').html(response); // Display student list in modal
            $('#viewStudentsModal').modal('show'); // Show modal
        }
    });
}

// Handle the form submission via AJAX for class updates
document.getElementById('updateClassForm').addEventListener('submit', function(e) {
    e.preventDefault();  // Prevent the form from submitting normally

    const formData = new FormData(this);

    // Send the form data via AJAX to the PHP script
    fetch('updateClass.php', {  // Change this URL if needed
        method: 'POST',
        body: formData
    })
    .then(response => response.json())  // Expecting a JSON response
    .then(data => {
        if (data.success) {
            // Close the modal
            $('#updateClassModal').modal('hide');

            // Display SweetAlert success message
            Swal.fire({
                title: 'Success!',
                text: 'Class updated successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();  // Reload the page to reflect the changes
            });
        } else {
            // Show error message if update failed
            Swal.fire({
                title: 'Error!',
                text: data.error || 'An error occurred while updating the class.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while updating the class.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        console.error('Error:', error);
    });
});

// Function to populate the update modal with class data
function showUpdateModal(classId) {
    // Fetch class details from the backend using AJAX
    $.ajax({
        url: 'getClassDetails.php',  // You need to create this file
        type: 'GET',
        data: { classId: classId },
        success: function(response) {
            var classData = JSON.parse(response);
            if (classData) {
                // Populate the modal with the existing class details
                $('#classId').val(classData.id);
                $('#class_name').val(classData.class_name);
                $('#subject').val(classData.subject);
                $('#room').val(classData.room);
                $('#instructor').val(classData.instructor);
                $('#start_date').val(classData.start_date);
                $('#end_date').val(classData.end_date);
                $('#schedule').val(classData.schedule);
                
                // Show the update modal
                $('#updateClassModal').modal('show');
            }
        }
    });
}

$(document).ready(function() {
    $('#updateClassForm').on('submit', function(event) {
        event.preventDefault();
        
        // Send AJAX request to update class
        $.ajax({
            url: 'updateClass.php', // The PHP script to update the class
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            success: function(response) {
                // Assuming response returns 'success' on success
                if(response === 'success') {
                    Swal.fire({
                        title: 'Class Updated!',
                        text: 'The class details have been updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then(() => {
                        location.reload(); // Reload the page to show updated class
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'There was an issue updating the class. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            }
        });
    });
});

function deleteClass(classId) {
    // SweetAlert confirmation dialog
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send AJAX request to delete the class
            $.ajax({
                url: 'deleteClass.php',  // Path to the PHP script that will handle the deletion
                type: 'POST',
                data: { classId: classId },
                success: function(response) {
                    if (response === 'success') {
                        // Show success alert and reload the page
                        Swal.fire(
                            'Deleted!',
                            'The class has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload();  // Refresh the page to remove the deleted class
                        });
                    } else {
                        // Show error alert
                        Swal.fire(
                            'Error!',
                            'Something went wrong while deleting the class.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the class.',
                        'error'
                    );
                }
            });
        }
    });
}

document.getElementById('updateClassForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Assume form submission is successful (for demo)
    Swal.fire({
        title: 'Class Updated!',
        text: 'The class has been successfully updated.',
        icon: 'success',
        confirmButtonText: 'Ok'
    }).then(() => {
        location.reload();
    });

});
// Function to set the grade value programmatically
function setGrade(value) {
    const gradeInput = document.getElementById('grade');
    if (value >= 1.0 && value <= 5.0) {
        gradeInput.value = value; // Set the grade value if it's valid
    } else {
        alert('Invalid grade. Please enter a value between 1.0 and 5.0.');
    }
}

// Function to validate the grade entered by the user
document.querySelector('form').addEventListener('submit', function(event) {
    var gradeInput = document.getElementById('grade');
    var gradeValue = parseFloat(gradeInput.value);

    // Check if the grade is within the range 1.0 to 5.0
    if (isNaN(gradeValue) || gradeValue < 1 || gradeValue > 5) {
        alert('Please enter a valid grade between 1.0 and 5.0.');
        event.preventDefault();  // Prevent form submission
    }
});
