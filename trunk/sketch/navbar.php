
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
     
      <a class="navbar-brand" href="index.php">Brand</a>
    </div>
   
    <p id="lbl1" class="navbar-text"><?php if ($_SESSION["email"] == "") echo "สมาชิก ล็อกอิน"; 
                                    else echo htmlentities($_SESSION["member_name"]);?></p>

      <form class="navbar-form navbar-left">
        <div class="form-group">         
          <label class="sr-only" for="txt-email">อีเมล์</label>
          <input id="txt-email" type="email" class="form-control" placeholder="อีเมล์" value="pk.manee@gmail.com">
        </div>
        <div class="form-group">
          <label class="sr-only" for="txt-password">รหัสผ่าน</label>
          <input id="txt-password" type="password" class="form-control" placeholder="รหัสผ่าน" value="111111">
        </div>
        <button type="button" id="btn-sign-in" class="btn btn-primary">ล็อกอิน</button>
      </form>

      <ul class="nav navbar-nav">
        <li><a href="signup.php">สมัครสมาชิก</a></li>
      </ul>

      <ul id="member-menu" class="nav navbar-nav navbar-right">       
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">ข้อมูลสมาชิก<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="EditProfile.php">จัดการข้อมูลสมาชิก</a></li>
            <li class="divider"></li>
            <li><a href="#">เกี่ยวกับ</a></li>
          </ul>
        </li>
      </ul>
      <ul id="admin-menu" class="nav navbar-nav navbar-right" >
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">จัดการระบบ<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="listshirttype.php">แบบเสื้อ</a></li>
            <li><a href="listmaterialtype.php">ประเภทผ้า</a></li>
            <li><a href="listshirtsize.php">ขนาดเสื้อ</a></li>
            <li><a href="listshirtcolor.php">สีเสื้อ</a></li>
            <li><a href="ListShirts.php">เสื้อ</a></li>
            <li class="divider"></li>
            <li><a href="#">Cell</a></li>
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
var lblText = 'สมาชิก ล็อกอิน';
var btnSigninText = 'ล็อกอิน';
var btnSignoutText= 'ล็อกเอาท์';

btnSignin.onclick = function(){

  //sign in
  if (btnSignin.innerHTML === btnSigninText){
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

    bootbox.confirm({
                  title: '', 
                  message: '<div class="alert alert-info" role="alert">ท่านต้องการ <strong>ล็อกเอาท์</strong> หรือไม่?</div>',
                  callback: function(result) {                   
                    if (result) {
                       //sign out
                      $.post("member_session.php", {email: '', member_name: ''});
                      window.location = "index.php";
                    }
                  }
    }); 
  }  
}

function doLogin(data){
  if (data.length <= 0) {
    bootbox.dialog({
                title: '',
                message : '<div class="alert alert-warning" role="alert">ไม่พบข้อมูลสมาชิก กรุณา <strong>สมัครสมาชิก</strong>.</div>'
    });
    return;
  }

  var obj = data[0];      
  lbl.innerHTML = obj.member_name;

  Toast.init({
      "selector": ".alert-success"
  });
  Toast.show("ยินดีต้อนรับ คุณ <strong>" + obj.member_name + "</strong>");
 
  txtEmail.disabled = true;
  txtPassword.disabled =true;
  btnSignin.innerHTML = btnSignoutText;
  dropdown.style.display = 'block';

  if (txtEmail.value = 'pk.manee@gmail.com') {
    adminMenu.style.display = 'block';
  }

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
    txtEmail.disabled = true;
    txtPassword.disabled =true;
    btnSignin.innerHTML = btnSignoutText;
    dropdown.style.display = 'block';

    if (txtEmail.value === 'pk.manee@gmail.com') {
      adminMenu.style.display = 'block';
    }
  }
  else{
     dropdown.style.display = 'none';
     adminMenu.style.display =  'none';
  }
}
init();

</script>