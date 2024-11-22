function home() {
    document.getElementById("homeSection").style.display = "block";
    document.getElementById("classesSection").style.display = "none";
    document.getElementById("detailsSection").style.display = "none";
    document.getElementById("studentsSection").style.display = "none";
    document.getElementById("addClassSection").style.display = "none";
}


function toggleDetails() {
    document.getElementById("detailsSection").style.display = "block";
    document.getElementById("homeSection").style.display = "none";
    document.getElementById("studentsSection").style.display = "none";
    document.getElementById("addClassSection").style.display = "none";
    document.getElementById("classesSection").style.display = "none";
}

function toggleStudents() {
    document.getElementById("studentsSection").style.display = "block";
    document.getElementById("homeSection").style.display = "none";
    document.getElementById("detailsSection").style.display = "none";
    document.getElementById("classesSection").style.display = "none";
    document.getElementById("addClassSection").style.display = "none";
}

function toggleAddClass() {
    document.getElementById("addClassSection").style.display = "block";
    document.getElementById("homeSection").style.display = "none";
    document.getElementById("studentsSection").style.display = "none";
    document.getElementById("detailsSection").style.display = "none";
    document.getElementById("classesSection").style.display = "none";
}

function toggleClasses() {
    document.getElementById("classesSection").style.display = "block";
    document.getElementById("homeSection").style.display = "none";
    document.getElementById("studentsSection").style.display = "none";
    document.getElementById("detailsSection").style.display = "none";
    document.getElementById("addClassSection").style.display = "none";
}
function viewStudents(classId) {
    // Send an AJAX request to fetch students in the class
    $.ajax({
        url: 'fetch_students.php', // This PHP file should return students in JSON format based on classId
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
    // Fetch class data using AJAX (if needed) or pass values directly
    var classRow = document.getElementById('class-' + classId);
    var className = classRow.cells[1].innerText;
    var subject = classRow.cells[2].innerText;
    var room = classRow.cells[3].innerText;
    var teacher = classRow.cells[4].innerText;
    var startDate = classRow.cells[5].innerText;
    var endDate = classRow.cells[6].innerText;
    var schedule = classRow.cells[7].innerText;

    // Populate the modal fields with the class data
    document.getElementById('classId').value = classId;
    document.getElementById('class_name').value = className;
    document.getElementById('subject').value = subject;
    document.getElementById('room').value = room;
    document.getElementById('teacher').value = teacher;
    document.getElementById('start_date').value = startDate;
    document.getElementById('end_date').value = endDate;
    document.getElementById('schedule').value = schedule;

    // Show the modal
    $('#updateClassModal').modal('show');
}

// Function to handle class deletion
function deleteClass(classId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send an AJAX request to delete the class
            $.ajax({
                url: 'deleteClass.php', // PHP file to handle the deletion
                method: 'POST',
                data: { class_id: classId },
                success: function(response) {
                    if (response.success) {
                        // Display success message
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Class has been deleted.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            document.getElementById('class-' + classId).remove();  // Remove the class row from the table
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while deleting the class.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while deleting the class.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}
function openAddStudentModal(classId) {
    // Set the class ID in the hidden field of the modal
    document.getElementById('classId').value = classId;
    $('#addStudentModal').modal('show'); // Open the modal
}

