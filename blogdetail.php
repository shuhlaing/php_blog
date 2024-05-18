<?php

  require 'config/config.php';
  error_reporting (E_ALL ^ E_NOTICE);
  session_start();
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
  }
  // echo $_SESSION['user_id'];
  // exit();
  $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
  $stmt->execute();
  $result = $stmt->fetchAll();

  $blogId = $_GET['id'];
  $stmtcmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=$blogId");
  $stmtcmt->execute();
  $cmResult = $stmtcmt->fetchAll();
  //
  $authorId = $cmResult[0]['author_id'];
  $stmtau = $pdo->prepare("SELECT * FROM users WHERE id=$authorId");
  $stmtau->execute();
  $auResult = $stmtau->fetchAll();


  if($_POST) {
    $comment = $_POST['comment'];
    $stmt=$pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES (:content,:author_id,:post_id)");
    $result= $stmt->execute(
      array(':content'=>$comment,':author_id'=>$_SESSION['user_id'],':post_id'=>$blogId)
      );
    if($result){
      header('Location: blogdetail.php?id='.$blogId);
      }
    }



 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Widgets</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">


  <!-- Content Wrapper. Contains page content -->
  <div class="">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div class="card_title" style="text-align:center;">
                <h4><?php echo $result[0]['title'] ?></h4>
              </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <img class="img-fluid" src="admin/images/<?php echo $result[0]['image']?>" style="height:400px !important;width:100%;" alt="">
                <br><br>
                <p><?php echo $result[0]['content'] ?></p>
                <h3>Comment</h3><hr>
                <a href="/blog" type="button" class="btn btn-default">Go Home</a>
              </div>


              <!-- <div class="card-footer card-comments">
                <div class="card-comment">
                  <div class="comment-text" style="margin-left:0px !important;">
                    <span class="username">
                      <?php echo $cmResult[0]['name'];?>
                      <span class="text-muted float-right"><?php echo $cmResult[0]['created_at'];?></span>
                    </span>
                    <?php echo $cmResult[0]['content'];?>
                  </div>
                </div>
              </div> -->


              <?php
                if($cmResult){
                  foreach ($cmResult as $value){
                    ?>
                    <div class="card-footer card-comments">
                      <div class="card-comment">
                        <div class="comment-text" style="margin-left:0px !important;">
                          <span class="username">
                              <?php echo $auResult[0]['name'];?>
                            <span class="text-muted float-right"><?php echo $value['created_at'];?></span>
                          </span>
                          <?php echo $value['content'];?>
                        </div>
                      </div>
                    </div>
                    <?php
                  }
                }
              ?>
              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="" method="post">
                  <div class="img-push">
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>

  <footer class="main-footer" style="margin-left:0px !important;">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <a href="logout.php" type="button" class="btn btn-default">Logout</a>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2024 <a href="#">T&S Online Live Broadcasting</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
