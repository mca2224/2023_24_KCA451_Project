<?php
 
include "nav.php";

include "db.php";
session_start();
$mentor_id = $_SESSION['username'];
// echo $mentor_id;
// Fetch data from the 'students' table
$sql = "SELECT id, name, section, contact, department, attendance FROM students WHERE mentor_id='$mentor_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $menteeData = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $menteeData = array(); // If no mentees found, set to an empty array
}


// $unread_notifications_query = "SELECT COUNT(*) AS unread_count FROM req_res WHERE mentor_id = '$mentor_id' and status='unread'";
// $unread_notifications_result = mysqli_query($conn, $unread_notifications_query);
// $unread_notifications_row = mysqli_fetch_assoc($unread_notifications_result);
// $unread_count = $unread_notifications_row['unread_count'];


$unread_counts = array(); // Initialize an array to store unread counts for each mentee

// Fetch unique mentee IDs
$mentee_ids_query = "SELECT DISTINCT id FROM req_res WHERE mentor_id = '$mentor_id'";
$mentee_ids_result = mysqli_query($conn, $mentee_ids_query);

while ($mentee_ids_row = mysqli_fetch_assoc($mentee_ids_result)) {
    $mentee_id = $mentee_ids_row['id'];
    
    // Query unread count for the current mentee ID
    $unread_count_query = "SELECT COUNT(*) AS unread_count 
                           FROM req_res 
                           WHERE mentor_id = '$mentor_id' AND id = '$mentee_id' AND status = 'unread'";
    $unread_count_result = mysqli_query($conn, $unread_count_query);
    $unread_count_row = mysqli_fetch_assoc($unread_count_result);
    $unread_count = $unread_count_row['unread_count'];
    
    // Store the unread count in the array with the mentee ID as the key
    $unread_counts[$mentee_id] = $unread_count;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="menteedetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Mentee Information</title>
    <style>
        .footer{
            margin-bottom: 1px;
        }
        .notification-dot {
            position: absolute;
            top: 0;
            left: 90%;
            width: 10px;
            height: 10px;
            background-color: yellow;
            border-radius: 50%;
            color:yellow;   
}
        </style>
</head>
<body>
    <header>
    <div class="col-md-6">
    <img src="logo-no-background.jpg" alt="MentorU Logo" class="mentoru-logo img-fluid">
</div>
<div class="col-md-6 text-right">
    <img src="collegeimg.webp" alt="College Logo" class="college-logo img-fluid">
</div>
    </header>
    <br>
    <hr>
    <main>
        <div class="container">
            <h2>Your Mentees:</h2>
            <!-- Rest of your HTML content -->

            <h5>Mentee Details</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Section</th>
                        <th>Contact</th>
                        <th>Department</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menteeData as $mentee) : ?>
                        <?php
    // Initialize $unread_count
    $unread_count = isset($unread_counts[$mentee['id']]) ? $unread_counts[$mentee['id']] : 0;
    ?>
                        <tr>
                            <td><?php echo $mentee['id']; ?></td>
                            <td><?php echo $mentee['name']; ?></td>
                            <td><?php echo $mentee['section']; ?></td>
                            <td><?php echo $mentee['contact']; ?></td>
                            <td><?php echo $mentee['department']; ?></td>
                            <td><a href="view_students.php?id=<?php echo $mentee['id']; ?>" class="btn btn-primary">View Students</a></td>
                            <td>
                <a href="req_rep.php?id=<?php echo $mentee['id']; ?>" class="btn btn-info">See Messages</a>
            </td>
           
            <!-- <td><a href="view_notifications.php" style="text-decoration: none; display: flex; align-items: center;">
        <i class="fa fa-bell" style="font-size:34px; color:red;"></i> 
        <span style="background-color: black; color: white; border-radius: 50%; padding: 5px 10px; font-size: 10px; margin-left: -10px;">
        <?php echo $unread_count; ?></span>
    </a>
</td> -->

<!-- <td>
    <a href="req_rep.php?id=<?php echo $mentee['id']; ?>" style="text-decoration: none; display: flex; align-items: center;">
        <i class="fa fa-bell" style="font-size:34px; color:red;"></i> 
        <?php if ($unread_count > 0): ?>
            <span style="background-color: black; color: white; border-radius: 50%; padding: 5px 10px; font-size: 10px; margin-left: -10px;">
                <?php echo $unread_count; ?>
            </span>
        <?php else: ?>
            <span>No Notification</span>
        <?php endif; ?>
    </a>
</td> -->

<td>
    <a href="req_rep.php?id=<?php echo $mentee['id']; ?>" style="text-decoration: none; display: flex; align-items: center;">
        <i class="fa fa-bell" style="font-size:34px; color:red;"></i> 
        <?php if ($unread_count > 0): ?>
            <span style="background-color: black; color: white; border-radius: 50%; padding: 5px 10px; font-size: 10px; margin-left: -10px;">
                <?php echo $unread_count; ?>
            </span>
        <?php else: ?>
            <span>No Notification</span>
        <?php endif; ?>
    </a>
</td>


          
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </main>

    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.js delivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- Include your custom JavaScript file if needed -->
    <!-- <script src="mentorhome.js"></script> -->
    <?php
    include "footer.php";
    ?>
</body>
</html>
