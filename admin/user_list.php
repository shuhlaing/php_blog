<?php
  session_start();
  require '../config/config.php';
  require '../config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
  }
  if($_SESSION['role'] != 1){
    header('Location: login.php');
  }

  if(!empty($_POST['search'])){
    setcookie('search',$_POST['search'],time() * (86400 * 30), "/");
  }else{
    if(empty($_GET['pageno'])){
      unset($_COOKIE['search']);
      setcookie('search', null, -1, "/");
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
              <div class="card-header">
                <h3 class="card-title">User Listing</h3>
              </div>

              <?php
                if (!empty($_GET['pageno'])) {
                  $pageno = $_GET['pageno'];
                }else{
                  $pageno = 1;
                }
                $sumOfrecs = 1;
                $offset = ($pageno -1) * $sumOfrecs;

                if(empty($_POST['search']) && empty($_COOKIE['search'])){
                  $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                  $stmt->execute();
                  $rawResult = $stmt->fetchAll();

                  $total_pages = ceil(count($rawResult) / $sumOfrecs);

                  $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$sumOfrecs");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }else{
                  $searchKey = $_POST['search'];
                  $searchKey = $_POST['search'] ? $_POST['search'] : $_COOKIE['search'];
                  $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                  //print_r($stmt);exit();
                  $stmt->execute();
                  $rawResult = $stmt->fetchAll();

                  $total_pages = ceil(count($rawResult) / $sumOfrecs);

                  $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$sumOfrecs");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }



              ?>
              <!-- /.card-header -->
              <div class="card-body">
                  <div style="padding-bottom:10px;">
                    <a href="user_add.php" type="button" class="btn btn-success">Create New User</a>
                  </div>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th style="width: 10px">Name</th>
                      <th style="width: 10px">Email</th>
                      <!-- <th style="width: 10px">Password</th> -->
                      <th style="width: 10px">Role</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if($result){
                        $i = 1;
                        foreach ($result as $value){
                          ?>
                          <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo escape($value['name']) ?></td>
                            <td><?php echo escape($value['email']) ?></td>
                              <!-- <td><?php echo $value['password'] ?></td> -->
                            <td><?php echo $value['role'] == 1 ? 'admin':'user'; ?></td>
                            <td>
                            <div class="btn-group">
                              <div class="container">
                                <a href="user_edit.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-primary">Edit</a>
                              </div>

                                <div class="container">
                                  <a href="user_delete.php?id=<?php echo $value['id']?>"
                                    onClick="return confirm('Are you sure you want ot delete theis item')"
                                    type="button" class="btn btn-danger">Delete</a>
                                </div>
                            </div>
                            </td>
                          </tr>
                          <?php
                          $i++;
                        }
                      }
                    ?>
                  </tbody>
                </table><br>
                <div>
                <nav aria-label="Page navigation example" style="float:right;">
                <ul class="pagination">
                  <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                  <li class="page-item <?php if($pageno <= 1){echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno <=1) {echo '#';}else{ echo "?pageno=".($pageno-1);}?>">Previous</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                  <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno >= $total_pages) {echo '#';}else{echo "?pageno=".($pageno+1);} ?>">Next</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a></li>
                </ul>
              </nav>
            </div>
              </div>
            </div>
          </div>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
 <?php include('footer.html'); ?>
