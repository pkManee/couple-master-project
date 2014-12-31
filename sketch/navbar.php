
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
     
      <a class="navbar-brand" href="index.php">Brand</a>
    </div>
   
    <p id="lbl1" class="navbar-text"><?php if ($_SESSION["email"] == "") echo "Member sign in"; 
                                    else echo htmlentities($_SESSION["member_name"]);?></p>

      <form class="navbar-form navbar-left">
        <div class="form-group">         
          <label class="sr-only" for="txt-email">Email address</label>
          <input id="txt-email" type="email" class="form-control" placeholder="Enter email" value="pk.manee@gmail.com">
        </div>
        <div class="form-group">
          <label class="sr-only" for="txt-password">Email address</label>
          <input id="txt-password" type="password" class="form-control" placeholder="Password" value="111111">
        </div>
        <input type="button" id="btn-sign-in" class="btn btn-primary" value="Sign in">
      </form>

      <ul class="nav navbar-nav">
        <li><a href="signup.php">Sign up</a></li>
      </ul>

      <ul id="member-menu" class="nav navbar-nav navbar-right">       
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">ข้อมูลสมาชิก<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="editprofile.php">ปรับแต่งแอคเคาน์</a></li>            
            <li class="divider"></li>
            <li><a href="#">เกี่ยวกับ</a></li>
          </ul>
        </li>
      </ul>
      <ul id="admin-menu" class="nav navbar-nav navbar-right" >
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">จัดการระบบ<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="editprofile.php">Account Settings</a></li>
            <li><a href="listshirttype.php">แบบเสื้อ</a></li>
            <li class="divider"></li>
            <li><a href="#">เกี่ยวกับ</a></li>
          </ul>
        </li>
      </ul>
  </div>  
</nav>

<script type="text/javascript">
var btnSignin = document.getElementById('btn-sign-in');
var txtEmail = document.getElementById('txt-email');
var txtPassword = document.getElementById('txt-password');
var lbl = document.getElementById('lbl1');
var dropdown = document.getElementById('member-menu');
var adminMenu = document.getElementById('admin-menu');
var lblText = 'Member sign in';
var btnSigninText = 'Sign in';
var btnSignoutText= 'Sign out';

btnSignin.onclick = function(){
  //sign in
  if (btnSignin.value === "Sign in"){
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "service/servie",
      data: {method: "memberLogin", email: txtEmail.value, password: txtPassword.value},
      success: function(data){
        doLogin(data);
      }
    });
  } else {
    //sign out
    $.post("member_session.php", {email: '', member_name: ''});

    bootbox.confirm({
                  title: '', 
                  message: '<div class="alert alert-info" role="alert">Are you sure to <strong>sign out</strong>?</div>',
                  callback: function(result) {
                    if (result) window.location = "index.php";     
                  }
    }); 

    // bootbox.dialog({
    //             title: '',
    //             message : '<div class="alert alert-success" role="alert">Singing out...</div>'
    // });  
    // setTimeout(function(){bootbox.hideAll()}, 1000);   

    // btnSignin.value = btnSigninText;
    // lbl.innerHTML = lblText;
    // txtEmail.removeAttribute('disabled');
    // txtEmail.value = '';
    // txtPassword.removeAttribute('disabled');
    // txtPassword.value = '';
    //window.location = "index.php";
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
  
  // bootbox.dialog({
  //               title: '',
  //               message : '<div class="alert alert-success" role="alert"><strong>Singing in...</strong></div>'
  // });  
  // setTimeout(function(){bootbox.hideAll()}, 1000);    
  Toast.init({
      "selector": ".alert-success"
  });
  Toast.show("<strong>Success</strong> you are signed in...");

  var obj = data[0];      
  lbl.innerHTML = obj.member_name;

  var att1 = document.createAttribute('disabled');
  txtEmail.setAttributeNode(att1);
  var att2 = document.createAttribute('disabled');
  txtPassword.setAttributeNode(att2);
  btnSignin.value = btnSignoutText;
  dropdown.style.display = 'block';

  $.post("member_session.php", {email: obj.email, member_name: obj.member_name});
}

var Toast = (function() {
    "use strict";

    var elem,
        hideHandler,
        that = {};

    that.init = function(options) {
        elem = $(options.selector);
    };

    that.show = function(text) {
        clearTimeout(hideHandler);

        elem.find("span").html(text);
        elem.delay(200).fadeIn().delay(3000).fadeOut();
    };

    return that;
}());

function init(){
  if (lbl.innerHTML !== lblText){
    var att1 = document.createAttribute('disabled');
    txtEmail.setAttributeNode(att1);
    var att2 = document.createAttribute('disabled');
    txtPassword.setAttributeNode(att2);
    btnSignin.value = btnSignoutText;
    dropdown.style.display = 'block';
  }
  else{
     dropdown.style.display = 'none';
  }  
}
init();

</script>