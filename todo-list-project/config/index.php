<?php
    session_start();
    include('config/database.php');
    include('config/tasks.php');
    $obj = new Tasks;
    
    if(isset($_POST['submit'])) {
        // Insert Data in the Table
        $task = $_POST['task'];
        $id = $_POST['id'];
        $created_at = $updated_at = date("Y-m-d H:i:s");

        //Update
        if(!empty($id)) {
            $sql = "UPDATE todolists set task = '".$task."', updated_at = '".$updated_at."' where id = ".$id;
            $res = $obj->executeQuery($sql);
            if($res) {
                $_SESSION['success'] = "Task has been update successfully";
            }
            else {
                $_SESSION['error'] = "Something went wrong, please try again later";
            }
        }   
        else {
            $sql = "INSERT INTO todolists (task, created_at, updated_at) VALUES ('".$task."', '".$created_at."', '".$updated_at."')";
            $res = $obj->executeQuery($sql);

            if($res) {
                $_SESSION['success'] = "Task has been created successfully";
            }
            else {
                $_SESSION['error'] = "Something went wrong, please try again later";
            }
        }
        
        session_write_close();
        header("LOCATION:index.php");
    }

    //Get all Tasks
    $tasks = $obj->getAllTasks();

    //Get Task
    $editing = false;
    if(isset($_GET['action']) && $_GET['action']  === 'edit') {
        $taskData = $obj->getTask($_GET['id']);
        $editing = true;
    }

    //Delete Task
    if(isset($_GET['action']) && $_GET['action']  === 'delete') {
        $sql = "DELETE FROM todolists WHERE id = ".$_GET['id'];
        $res = $obj->executeQuery($sql);
        if($res) {
            $_SESSION['success'] = "Task has been deleted successfully";
        }
        else {
            $_SESSION['error'] = "Something went wrong, please try again later";
        }

        session_write_close();
        header("LOCATION:index.php");
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-zvt3+nr9CVgJIBG6W0BlXz3y2ZJSKG17LwN9t+M89W7IwwBqUzHpgOJ5i6fW6cpe8/wfENxE7+QQs+uayIg21Q==" crossorigin="anonymous" />

    <link rel="stylesheet" href="assets/css/style.css"/>
    <title>Todo List</title>
</head>
<body>
    <!--Step 1: Basic structure of Todo List-->
    <div class="container">
      <!--Step 2: Create input place and button-->
      <div id="newtask">
        <?php include('include/alert.php') ?>


        <h3>Todo List</h3>
        <form action="index.php" method="post" id="taskform">
            <input type="hidden" name="id" value="<?php if($editing) { echo $taskData['id']; } ?>"  >
            <input type="text" name="task" id="task" placeholder="Task to be done..." value="<?php if($editing) { echo $taskData['task']; } ?>" />
            <button type="submit" name="submit" id="add"><?php if($editing) { echo "Update"; } else { echo "Add" ; } ?></button>
        </form>
      </div>

      <div id="tasks">
        <?php
            if(!empty($tasks)) {
                foreach($tasks as $t) {
        ?>
        <div class="task">
            <span><?php echo $t['task'] ?></span>
            <a href="index.php?action=edit&id=<?php echo $t['id'] ?>" class="edit button"><i class="fa fa-edit"></i></a>
            <a onclick="return confirm('Do you want to delete this record?')" href="index.php?action=delete&id=<?php echo $t['id'] ?>" class="delete button"><i class="fa fa-trash-alt"></i></a>
        </div>
        <?php }} ?>
      </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>