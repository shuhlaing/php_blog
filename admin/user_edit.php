<?php
  session_start();
  require '../config/config.php';

  if($_POST) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    if(empty($_POST['role'])){
      $role=0;
    }else{
      $role = 1;
    }

    $stat = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
    $stat->execute(
      array(':email'=>$email,':id'=>$id)
    );
    $user = $stat->fetch(PDO::FETCH_ASSOC);

    if($user){
      echo "<script>alert('Email duplicated.')</script>";
    }else{
      $stmt=$pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id='$id'");
      $result= $stmt->execute();
      if($result){
        echo "<script>alert('Successfully updated.');window.location.href='user_list.php';</script>";
      }
    }
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
    $stmt->execute();

    $result = $stmt->fetchAll();
 ?>


 <?php
   include('header.html');
  ?>
     <div class="content">
       <div class="container-fluid">
         <div class="row">
           <div class="col-md-12">
             <div class="card">
               <div class="card-body">
                 <form class="" action="user_edit.php" method="post" enctype="multipart/form-data">
                   <div class="form-group">
                       <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">
                     <label for="">Name</label>
                     <input type="text" name="name" class="form-control" value="<?php echo $result[0]['name'] ?>">
                   </div>
                   <div class="form-group">
                     <label for="">Email</label>
                     <input type="email" name="email" class="form-control" value="<?php echo $result[0]['email'] ?>">
                   </div>
                   <div class="form-group">
                     <label for="">Admin</label>
                     <input type="checkbox" name="role" value="1">
                   </div>
                   <div class="form-group">
                     <input type="submit" class="btn btn-success" name="" value="SUBMIT">
                     <a href="user_list.php" class="btn btn-warning">Back</a>
                   </div>
                 </form>
               </div>
             </div>
           </div>

         </div>
         <!-- /.row -->
       </div><!-- /.container-fluid -->
     </div>
     <!-- /.content -->
  <?php include('footer.html'); ?>
