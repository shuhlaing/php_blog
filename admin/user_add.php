<?php
  session_start();
  require '../config/config.php';
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
  }

  if($_SESSION['role'] != 1){
    header('Location: login.php');
  }


  if($_POST) {
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4) {
      if(empty($_POST['name'])) {
        $nameError = 'Name cannot be null';
      }
      if(empty($_POST['email'])) {
        $emailError = 'Email cannot be null';
      }
    if(empty($_POST['password'])) {
      $passwordError = 'Password cannot be null';
    }
    if(strlen($_POST['password']) < 4){
      $passwordError = "Password should be 4 characters at least";
    }
  }else{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if(empty($_POST['role'])){
      $role=0;
    }else{
      $role = 1;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");

    $stmt->bindValue(':email',$email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

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
}
?>

 <?php
   include('header.php');
  ?>
     <div class="content">
       <div class="container-fluid">
         <div class="row">
           <div class="col-md-12">
             <div class="card">
               <div class="card-body">
                 <form class="" action="user_add.php" method="post" enctype="multipart/form-data">
                   <div class="form-group">
                     <label for="">Name</label><p style="color:red;"><?php echo empty($nameError) ? '' : '*'. $nameError; ?></p>
                     <input type="text" name="name" class="form-control" value="" >
                   </div>
                   <div class="form-group">
                     <label for="">Email</label><p style="color:red;"><?php echo empty($emailError) ? '' : '*'. $emailError; ?></p>
                     <input type="email" name="email" class="form-control" value="" >
                   </div>
                   <div class="form-group">
                     <label for="">Password</label><p style="color:red;"><?php echo empty($passwordError) ? '' : '*'. $passwordError; ?></p>
                     <input type="password" name="password" class="form-control" value="" >
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
       </div>
     </div>
  <?php include('footer.html'); ?>
