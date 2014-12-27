<!DOCTYPE html>
<?php
if (!isset($_SESSION)){
  session_start();
  $_SESSION['email'] = '';
}
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">    
  </head>
  <body>

  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Brand</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">    
      <p id="lbl1" class="navbar-text"><?php if ($_SESSION["email"] == "") echo "Member sign in"; else echo htmlentities($_SESSION["member_name"]); ?></p>   
        <form class="navbar-form navbar-left">
          <div class="form-group">         
            <label class="sr-only" for="txt-email">Email address</label>
            <input id="txt-email" type="email" class="form-control" placeholder="Enter email" value="pk.manee@gmail.com">
          </div>
          <div class="form-group">
            <label class="sr-only" for="txt-password">Email address</label>
            <input id="txt-password" type="password" class="form-control" placeholder="Password" value="111111">
          </div>
          <input type="button" id="btn-sign-in" class="btn btn-default" value="Sign in">

        </form>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="signup.php">Sign up</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
              <li class="divider"></li>
              <li><a href="#">Separated link</a></li>
            </ul>
          </li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="./js/jquery-2.1.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/validator.js"></script>

    <script type="text/javascript">
    var btnSignin = document.getElementById('btn-sign-in');
    var txtEmail = document.getElementById('txt-email');
    var txtPassword = document.getElementById('txt-password');
    var lbl = document.getElementById('lbl1');

    btnSignin.onclick = function(){
      if (btnSignin.value === 'Sign in'){
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "./service/member_login_service.php",
          data: {email: txtEmail.value, password: txtPassword.value},
          success: function(data){     
            doLogin(data);
          }
        });
      } else {
        $.post("./service/member_session_service.php", {email: '', member_name: ''});
        bootbox.dialog({
                    title: '',
                    message : '<div class="alert alert-success" role="alert">Singing out...</div>'
        });  
        setTimeout(function(){bootbox.hideAll()}, 1000);   

        btnSignin.value = 'Sign in';
        lbl.innerHTML = 'Member sign in';
        txtEmail.removeAttribute('disabled');
        txtEmail.value = '';
        txtPassword.removeAttribute('disabled');
        txtPassword.value = '';
      }
    }

    function doLogin(data){
      if (data.length <= 0) {
        bootbox.dialog({
                    title: '',
                    message : '<div class="alert alert-warning" role="alert">You are not a member please <strong>sign up</strong>.</div>'
        });
        return;
      }
      
      bootbox.dialog({
                    title: '',
                    message : '<div class="alert alert-success" role="alert"><strong>Singing in...</strong></div>'
      });  
      setTimeout(function(){bootbox.hideAll()}, 1000);    

      var obj = data[0];      
      lbl.innerHTML = obj.member_name;

      var att1 = document.createAttribute('disabled');
      txtEmail.setAttributeNode(att1);
      var att2 = document.createAttribute('disabled');
      txtPassword.setAttributeNode(att2);
      btnSignin.value = 'Sign out';

      $.post("./service/member_session_service.php", {email: obj.email, member_name: obj.member_name});
    }
    </script>
  </body>
</html>

