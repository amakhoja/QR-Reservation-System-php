<?php
session_start();
include("config.php");
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    if (isset($_POST['add_room'])) {
        $hotel_name =$_POST['hotel_name'];
        $room_name = $_POST['room_name'];
        $size = $_POST['size'];
        $price = $_POST['price'];
        $sql="INSERT INTO rooms(  user_id, hotel_name, room_name, size, price) VALUES ($_SESSION[id],'$hotel_name','$room_name','$size','$price');";
        mysqli_query($con,$sql);
         

        
        
    }
}
   // delete room
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];
        $sqlDelete = "DELETE FROM rooms WHERE id = $deleteId";

        if ($con->query($sqlDelete) === TRUE) {
            echo "Room deleted successfully";
        } else {
            echo "Error deleting room: " . $con->error;
        }
    }
    if (isset($_POST['size_id'])) {
        $size_id = $_POST['size_id'];
        $size_edit =$_POST['size_edit'];
        $sqlupdate = "update rooms SET size = '$size_edit' WHERE rooms.id= '$size_id' ";

        if ($con->query($sqlupdate) === TRUE) {
            echo "Room update successfully";
        } else {
            echo "Error updateing room: " . $con->error;
        }
    }
    if (isset($_POST['price_id'])) {
        $price_id = $_POST['price_id'];
        $price_edit =$_POST['price_edit'];
        $sqlupdate = "update rooms SET price = '$price_edit' WHERE rooms.id= '$price_id' ";

        if ($con->query($sqlupdate) === TRUE) {
            echo "Room update successfully";
        } else {
            echo "Error updateing room: " . $con->error;
        }
    }
}






?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Admin</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }
        .header a{
            color: #fff;
            text-decoration: none;
            padding: 0 15px;
        }
        .header a:hover{
            text-decoration: underline;
        }
        .addRoomForm{
     width: 310px;
    height: inherit;
    padding: 20px 20px;
 
    display: flex;
    justify-content: space-around;
    flex-direction: column;
    border: 1px solid #a6a6a6;
    border-radius: 4px;
    margin: 20px auto 10px auto;

        }
        .tableRoom{
            padding: 10px;
        }

        .tableRoom td{
            text-align: center;

        }
        .tableRoom td input[type="number"]{
            padding: 7px;
            margin-top: 5px;
            width: 80px;
        }
        .tableRoom td  input[type="text"]{
            padding: 7px;
            margin-top: 5px;
            width: 80px;
        }
       
        .deletebtn{
            background-color: #FF0000;
             color: #FFFFFF;
              padding: 7px;
              margin-top: 5px;
               cursor: pointer;

        }
        .edit{
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<header>
<div class="header">
        <a href="home_page.php">Home</a>
      
        
    </div>
</header>
<body>

    <h2><center>Add a New Room</center></h2>
    <form method="POST" class="addRoomForm" action="">
    <label for="hotel_name">Hotel name:</label>
        <input type="text" name="hotel_name" required><br>

        <label for="room_name">Room name:</label>
        <input type="text" name="room_name" required><br>

        <label for="size">Size:</label>
        <input type="text" name="size" required><br>

        <label for="price">Price:</label>
        <input type="number" name="price" required><br>

        <input type="submit" value="Add Room" name="add_room">
    </form>

   
   
    <?php 
    // SQL query to retrieve data
$sqlSelect = "SELECT * FROM rooms where user_id = $_SESSION[id]";

// Execute the SELECT query
$result = mysqli_query($con,$sqlSelect);

// Display the rooms data
if ($result->num_rows > 0) {
    echo"<center> <h2>Existing Rooms</h2>";
    echo "<table class='tableRoom' ><tr><th>Hotel name</th><th>Room Name</th><th>Size</th><th>Price</th><th>Delete</th><th>Size</th><th>price</th></tr>";


    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['hotel_name']}</td><td>{$row['room_name']}</td><td>{$row['size']}</td><td>{$row['price']}</td>";
        echo "<td>
                <form method='POST' action=''>
                    <input type='hidden' name='delete_id' value='{$row['id']}'>
                    <input type='submit' value='Delete' class='deletebtn'>
                </form>
              
              </td>
              <td>
                <form method='POST' action='' class='edit'>
                    <input type='text' name='size_edit'>
                    <input type='hidden' name='size_id' value='{$row['id']}'>
                    
                </form>
              
              </td>
              <td>
              <form method='POST' action='' class='edit'>
                  <input type='number' name='price_edit'>
                  <input type='hidden' name='price_id' value='{$row['id']}'>
                  
              </form>
            
            </td>";
        echo "</tr>";
    }

    echo "</table></center>";
} 

$sqlSelect = "SELECT * FROM bookings where user_id = $_SESSION[id]";

// Execute the SELECT query
$result = mysqli_query($con,$sqlSelect);

// Display the rooms data
if ($result->num_rows > 0) {
    echo"<center> <h2>booking details</h2>";
    echo "<table class='tableRoom' ><tr><th>ID</th><th>Name</th><th>Room Name</th><th>Date</th></tr>";


    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['room']}</td><td>{$row['date']}</td>";
      
        echo "</tr>";
    }

    echo "</table></center>";
} 
    ?>

</body>

</html>