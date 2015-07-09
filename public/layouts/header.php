
<!DOCTYPE html>
<html>
<head>
  <title>To Do Aplikacija</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<div class="navbar">
  <div class="navbar-inner">
    <div class="container">
      <div class="navbar-inner">
        <div class="container">
          <a data-target=".navbar-responsive-collapse" data-toggle="collapse" class="btn btn-navbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a href="#" class="brand">To Do</a>
          <div class="nav-collapse collapse navbar-responsive-collapse">
            <ul class="nav">
              <li class="active"><a href="#">Korisnici</a></li>
              <li><a href="#">Zadaci</a></li>
              <?php if(!$session->is_logged_in()){?><li><a href="/admin">Login</a></li> <?php
              } else { ?><li><a href="admin/logout.php">Logout</a></li><?php } ?>
            </ul>
            <form action="" class="navbar-search pull-left">
              <input type="text" placeholder="Search" class="search-query span2">
            </form>
            <ul class="nav pull-right">
              <li class="divider-vertical"></li>
              <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">Dropdown <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li><a href="#">Separated link</a></li>
                </ul>
              </li>
            </ul>
          </div><!-- /.nav-collapse -->
        </div>
      </div>
    </div>
  </div>
</div>
