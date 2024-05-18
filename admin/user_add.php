<?php
  session_start();
  require '../config/config.php';

  if($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(empty($_POST['role'])){
      $role=0;
    }else{
      $role = 1;
    }

    $stat = $pdo->prepare("SELECT * FROM users WHERE email=:email");

    $stat->bindValue(':email',$email);
    $stat->execute();
    $user = $stat->fetch(PDO::FETCH_ASSOC);

    if($user){
      echo "<script>alert('Email duplicated.')</script>";
    }else{
      $stmt=$pdo->prepare("INSERT INTO users(name,email,role,password) VALUES (:name,:email,:role,:password)");
      $result= $stmt->execute(
        array(':name'=>$name,':email'=>$email,':role'=>$role,':password'=>$password)
      );
      if($result){
        echo "<script>alert('Successfully added.');window.location.href='user_list.php';</script>";
      }
    }
    }
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
                 <form class="" action="user_add.php" method="post" enctype="multipart/form-data">
                   <div class="form-group">
                     <label for="">Name</label>
                     <input type="text" name="name" class="form-control" value="" required>
                   </div>
                   <div class="form-group">
                     <label for="">Email</label>
                     <input type="email" name="email" class="form-control" value="" required>
                   </div>
                   <div class="form-group">
                     <label for="">Password</label>
                     <input type="password" name="password" class="form-control" value="" required>
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
