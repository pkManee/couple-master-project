
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">     
      <a class="navbar-brand" href="index.php"><img src="img/logo-1.png" style="height: 30px"></a>
    </div>
   
    <p id="lbl1" class="navbar-text" hidden-email="<?php echo (($_SESSION['email'] == '') ? '' : $_SESSION['email']); ?>"><?php if ($_SESSION["email"] == "") echo "สมาชิก ล็อกอิน"; 
                                    else echo htmlentities($_SESSION["member_name"]);?></p>

      <form class="navbar-form navbar-left">
        <div class="form-group">         
          <label class="sr-only" for="txt-email">อีเมล์</label>
          <input id="txt-email" type="email" class="form-control" placeholder="อีเมล์" autocomplete="off">
        </div>
        <div class="form-group">
          <label class="sr-only" for="txt-password">รหัสผ่าน</label>
          <input id="txt-password" type="password" class="form-control" placeholder="รหัสผ่าน" autocomplete="off">
        </div>
        <button type="button" id="btn-sign-in" class="btn btn-success">ล็อกอิน</button>
      </form>

      <ul class="nav navbar-nav" id="sign-up-link">
        <li><a href="signup.php">สมัครสมาชิก</a></li>
      </ul>

      <ul id="member-menu" class="nav navbar-nav navbar-right hidden">       
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">ข้อมูลสมาชิก<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="EditProfile.php">จัดการข้อมูลสมาชิก</a></li>
            <li><a href="ConfirmPayment.php">แจ้งชำระเงิน</a></li>
            <li class="divider"></li>
            <li><a href="HowTo.php">วิธีใช้งาน</a></li>
          </ul>
        </li>
      </ul>
      <ul id="admin-menu" class="nav navbar-nav navbar-right hidden" >
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">จัดการระบบ<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="listshirttype.php" class="hidden">แบบเสื้อ</a></li>
            <li><a href="listmaterialtype.php">ประเภทผ้า</a></li>
            <li><a href="listshirtsize.php">ขนาดเสื้อ</a></li>
            <li><a href="listshirtcolor.php">สีเสื้อ</a></li>
            <li><a href="ListShirts.php">เสื้อ</a></li>
            <li><a href="ListSizePrice.php">ราคาลายสกรีน</a></li>
            <li><a href="SystemInit.php">ค่าตั้งต้น</a></li>
          </ul>
        </li>
      </ul>
      <ul id="order-menu" class="nav navbar-nav navbar-right hidden">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">รายการสั่งซื้อ<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">           
            <li><a href="ListOrder.php">แสดงรายการสั่งซื้อ</a></li>
          </ul>
        </li>
      </ul>
  </div>  
</nav>
<script src="js/bootbox.js"></script>
<script type="text/javascript">
var btnSignin = document.getElementById('btn-sign-in');
var txtEmail = document.getElementById('txt-email');
var txtPassword = document.getElementById('txt-password');
var lbl = document.getElementById('lbl1');
var dropdown = document.getElementById('member-menu');
var adminMenu = document.getElementById('admin-menu');
var orderMenu = document.getElementById('order-menu');
var lblText = 'สมาชิก ล็อกอิน';
var btnSigninText = 'ล็อกอิน';
var btnSignoutText= 'ล็อกเอาท์';

btnSignin.onclick = function(event, data){

  var email = '', password = '', redirectPage = '';

  if (typeof data != 'undefined') {
    email = data.email;
    password = data.password;
    redirectPage = data.redirectPage;
  } else {
    email = txtEmail.value;
    password = txtPassword.value;
  }

  //sign in
  if (btnSignin.innerHTML === btnSigninText){
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "service/servie",
      data: {method: "memberLogin", email: email, password: password},
      success: function(data){
        doLogin(data);
      }
    });
  } else {   

    bootbox.confirm({
                  title: 'ยืนยันออกจากระบบ', 
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

  if (redirectPage != '') {
    setTimeout(function() {
      window.location = redirectPage;
    }, 500);
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
  lbl.setAttribute('hidden-email', obj.email);
  txtEmail.value = obj.email;
  txtPassword.value = '';

  Toast.init({
      "selector": ".alert-success"
  });
  Toast.show("ยินดีต้อนรับ คุณ <strong>" + obj.member_name + "</strong>");
 
  txtEmail.disabled = true;
  txtPassword.disabled =true;
  btnSignin.innerHTML = btnSignoutText;
  btnSignin.className = 'btn btn-danger';
  $('#sign-up-link').addClass('hidden');
  $(dropdown).removeClass('hidden');

  if (txtEmail.value === 'pk.manee@gmail.com') {
    //adminMenu.style.display = 'block';
    $(adminMenu).removeClass('hidden');
    $(orderMenu).removeClass('hidden');
  }

  $.post("member_session.php", {email: obj.email, member_name: obj.member_name});
  $('a[href="design.php"]').removeClass('hidden');
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

$(window).load(function(){
  //member signed in
  if (lbl.innerHTML !== lblText){
    txtEmail.value = lbl.getAttribute('hidden-email');
    txtEmail.disabled = true;
    txtPassword.disabled =true;
    btnSignin.innerHTML = btnSignoutText;
    btnSignin.className = 'btn btn-danger';    
    $(dropdown).removeClass('hidden');
    $('#sign-up-link').addClass('hidden');

    $('a[href="design.php"]').removeClass('hidden');

    if (txtEmail.value === 'pk.manee@gmail.com') {
      //adminMenu.style.display = 'block';
      $(adminMenu).removeClass('hidden');
      $(orderMenu).removeClass('hidden');
    } else {
      //adminMenu.style.display =  'none';
      $(adminMenu).addClass('hidden');
      $(orderMenu).addClass('hidden');

    }    
  }
  //member not signed in
  else{
    // dropdown.style.display = 'none';
    // adminMenu.style.display =  'none';
    $(dropdown).addClass('hidden');
    $(adminMenu).addClass('hidden');

    $('#sign-up-link').removeClass('hidden');
  }
});

</script>