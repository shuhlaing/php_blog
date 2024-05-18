<?php
  session_start();
  require '../config/config.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
  }

  if($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stat = $pdo->prepare("SELECT * FROM users WHERE email=:email");

    $stat->bindValue(':email',$email);
    $stat->execute();
    $user = $stat->fetch(PDO::FETCH_ASSOC);

    //print_r($user);
    if($user) {
      echo "<script>alert('Email duplicated.')</script>";
    }else{
      $stmt=$pdo->prepare("INSERT INTO users(name,email,password) VALUES (:name,:email,:password)");
      $result= $stmt->execute(
        array(':name'=>$name,':email'=>$email,':password'=>$password)
      );
      if($result){
        echo "<script>alert('Successfully added.');window.location.href='userList.php';</script>";
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
                <form class="" action="userAdd.php" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" class="form-control" value="" required>
                  </div>
                  <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" class="form-control" value="" required>
                  </div>
                  <div class="form-group">
                    <!-- <label for="">Admin</label><br> -->
                    <strong>Admin</strong>&nbsp;<input type="checkbox" name="role" value="">
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="SUBMIT">
                    <a href="userList.php" class="btn btn-warning">Back</a>
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
