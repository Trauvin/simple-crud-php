<?php
$dsn = 'mysql:host=localhost;dbname=db_test;user=root;password=';

try {
    $conn = new PDO($dsn);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $e) {
    echo "Não foi possível conectar " . $e->getMessage();
}

$id = '';
$fname = '';
$lname = '';
$age = '';

function getPosts() 
{
    $posts = array();
    $posts[0] = $_POST['id'];
    $posts[1] = $_POST['fname'];
    $posts[2] = $_POST['lname'];
    $posts[3] = $_POST['age'];

    return $posts;
}
// Search and Display data
if(isset($_POST['search'])) 
{
    $data = getPosts();
    if(empty($data[0])) 
    {
        echo "Enter the user id to search";
    } else {
        $searchStmt = $conn->prepare('SELECT * FROM users WHERE id = :id');
        $searchStmt->execute(array(
            ':id' => $data[0]
        ));
        if($searchStmt) 
        {
            $user = $searchStmt->fetch();
            if(empty($user)) 
            {
                echo "No data for this ID";
            }

            $id     = $user[0];
            $fname  = $user[1];
            $lname  = $user[2];
            $age    = $user[3];
        }
    }
}
// Insert Data
if(isset($_POST['insert'])) 
{
    $data = getPosts();
    if(empty($data[1]) || empty($data[2]) || empty($data[3])) 
    {
        echo "Enter the user data to insert";
    } else {
        $result = $conn->query("SELECT max(id) as next FROM users");
        $row = $result->fetch();
        $data[0] = (int) $row['next'] + 1;
        $insertStmt = $conn->prepare('INSERT INTO users(id, fname, lname, age) VALUES(:id, :fname, :lname, :age)');
        $insertStmt->execute(array(
            ':id' => $data[0],
            ':fname' => $data[1],
            ':lname' => $data[2],
            ':age' => $data[3],
        ));
        if($insertStmt) 
        {
            echo "Data inserted";
        }
    }
}
// Update data
if(isset($_POST['update'])) 
{
    $data = getPosts();
    if(empty($data[0]) || empty($data[1]) || empty($data[2]) ||empty($data[3])) 
    {
        echo "Enter the user data to update";
    } else {
        $updateStmt = $conn->prepare('UPDATE users SET fname = :fname, lname = :lname, age = :age WHERE id = :id');
        $updateStmt->execute(array(
            ':id' => $data[0],
            ':fname' => $data[1],
            ':lname' => $data[2],
            ':age' => $data[3],
        ));
        if($updateStmt) 
        {
            echo "Data updated";
        }
    }
}

// Delete data
if(isset($_POST['delete'])) 
{
    $data = getPosts();
    if(empty($data[0])) 
    {
        echo "Enter the user id to delete";
    }
    else {
        $deleteStmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $deleteStmt->execute(array(
            ':id' => $data[0],
        ));
        if($deleteStmt) 
        {
            echo "Data deleted";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <form action="index.php" method="post">
        
        <input type="text" name="id" placeholder="id" value="<?php echo $id;?>"><br/><br/>
        <input type="text" name="fname" placeholder="First Name" value="<?php echo $fname;?>"><br/><br/>
        <input type="text" name="lname" placeholder="Last Name" value="<?php echo $lname;?>"><br/><br/>
        <input type="number" name="age" placeholder="Age" value="<?php echo $age;?>"><br/><br/>
     
     
        <input type="submit" name="insert" value="Insert">
        <input type="submit" name="update" value="Update">
        <input type="submit" name="delete" value="Delete">
        <input type="submit" name="search" value="Search"> 
       
    </form>
</body>
</html>