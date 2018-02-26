<html>
<head>
    <?php include "default_head_resource.html" ?>
</head>
<body>
<?php
    if(!isset($_GET['mobile'])){
        include "user_nav_bar.php";
    }
?>

<div class="container">
    <div class="col-md-12 text-center text-capitalize signup-title" >
<?php if(!isset($_GET['mobile'])) { ?>
        <h3 > SIGN UP </h3 >
<?php
    }
?>
    </div >
    <form role="form" class="form-horizontal register-form">
        <!-- Account Information -->
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">Account Information</h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="reg-username" class="control-label col-md-2">Username：</label>
                        <div class="col-md-4">
                            <input type="text" name="name" id="reg-username" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <p id="username-warning" style="margin-top: 12px;"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reg-password" class="control-label col-md-2">Password：</label>
                        <div class="col-md-4">
                            <input type="password" name="pwd" id="reg-password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password" class="control-label col-md-2">Password(Confirm)：</label>
                        <div class="col-md-4">
                            <input type="password" name="confirm-password" id="confirm-password" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <p id="pwd-warning" style="margin-top: 12px;"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="verification" class="col-md-2 control-label">CAPTCHA：</label>
                        <div class="col-md-3">
                            <input type="text" name="verification" id="verification" class="form-control">
                        </div>
                        <div class="col-md-1">
                            <img src="../services/checkcode.php" style="margin-top: 5px;"/>
                        </div>
                        <div class="col-md-5">
                            <p id="vef-warning" style="margin-top: 12px;"></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Contact -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">Contact Details</h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="gender" class="control-label col-md-2">Gender：</label>
                        <div class="col-md-3">
                            <select class="form-control" id="gender">
                                <option value="1" selected="selected">Male</option>
                                <option value="2">Female</option>
                                <option value="3">Third gender</option>
                                <option value="4">Prefer not to say</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email"  class="control-label col-md-2">E-mail：</label>
                        <div class="col-md-4">
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <p id="email-warning" style="margin-top: 12px;"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="control-label col-md-2">Mobile Phone：</label>
                        <div class="col-md-4">
                            <input type="tel" name="phone" id="phone" class="form-control" pattern="^\d{4}-\d{3}-\d{4}$" required >
                        </div>
                        <div class="col-md-5">
                            <p id="phone-warning" style="margin-top: 12px;"></p>
                        </div>
                    </div>
                </div>
            </div>
            <label>
                <input type="checkbox" name="checkbox" id="checkbox">&nbsp;&nbsp;&nbsp;I have read and agree to the <a href="#" style="color: #2aabd2">Terms & Conditions</a>
            </label>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="submit" class="btn btn-default btn-block disabled" name="submit" value="Sign Up" id="submit">
                </div>
            </div>
        </div>
    </form>
</div>
</body>
<?php include "footer.php"?>
<script>
    $(function(){
        $('#reg-username').on('input propertychange', function(){
            var username = $(this).val();
            if (username.trim()==''){
                $('#username-warning').empty();
            }else{
                $.ajax({
                    url: '../services/checkReg.php',
                    data: {
                        username : username ,
                        type : 1
                    },
                    success: function(result){
                        if(result=='true'){
                            $('#username-warning').empty().append('<span class="glyphicon glyphicon-ok" style="color: #00f077"></span>');
                        }else{
                            $('#username-warning').empty().append('<span class="glyphicon glyphicon-remove" style="color: #f30017"></span> Sorry, this username has already been used.');
                        }
                    },
                    error: function(){
                        alert('connection error')
                    }
                });
            }
        });
        $('#phone').on('input propertychange', function(){
            var phone = $(this).val();
            if (phone.trim()==''){
                $('#phone-warning').empty();
            }else{
                $.ajax({
                    url: '../services/checkReg.php',
                    data: {
                        phone : phone ,
                        type : 2
                    },
                    success: function(result){
                        if(result=='true'){
                            $('#phone-warning').empty().append('<span class="glyphicon glyphicon-ok" style="color: #00f077"></span>');
                        }else{
                            $('#phone-warning').empty().append('<span class="glyphicon glyphicon-remove" style="color: #f30017"></span> Sorry, this phone number has already been used.');
                        }
                    },
                    error: function(){
                        alert('connection error')
                    }
                });
            }
        });
        $('#email').on('input propertychange', function(){
            var email = $(this).val();
            if (email.trim()==''){
                $('#email-warning').empty();
            }else{
                $.ajax({
                    url: '../services/checkReg.php',
                    data: {
                        email : email ,
                        type : 3
                    },
                    success: function(result){
                        if(result=='true'){
                            $('#email-warning').empty().append('<span class="glyphicon glyphicon-ok" style="color: #00f077"></span>');
                        }else{
                            $('#email-warning').empty().append('<span class="glyphicon glyphicon-remove" style="color: #f30017"></span> Sorry, this email address has already been used.');
                        }
                    },
                    error: function(){
                        alert('connection error')
                    }
                });
            }
        });
        $('#checkbox').change(function(){
            var n = $('input:checked' ).length;
            if (n==1) {
                $('#submit').removeClass('disabled');
            } else {
                $('#submit').addClass('disabled');
            }
        });
//        $('#verification').on('input propertychange', function(){
//            if ($(this).val() == verifycode) {
//                $('#vef-warning').empty().append('<span class="glyphicon glyphicon-ok" style="color: #00f077"></span>');
//            }else{
//                $('#vef-warning').empty().append('<span class="glyphicon glyphicon-remove" style="color: #f30017"></span> CAPTCHA is not correct');
//            }
//        });
        $('#confirm-password').on('input propertychange', function(){
            var pwd = $('#reg-password').val();
            if ($(this).val().trim()==''){
                $('#confirm-password-warning').empty();
            }else{
                if ($(this).val() == pwd) {
                    $('#pwd-warning').empty().append('<span class="glyphicon glyphicon-ok" style="color: #00f077"></span>');
                }else{
                    $('#pwd-warning').empty().append('<span class="glyphicon glyphicon-remove" style="color: #f30017"></span> Password does not match the confirm password.');
                }
            }
        });
        $('.register-form').submit(function(){
            if($('input:checked' ).length==1){
                var submitReq = new Object();
                submitReq.username = $('#reg-username').val();
                submitReq.password = $('#reg-password').val();
                submitReq.gender = $('#gender').val();
                submitReq.email = $('#email').val();
                submitReq.phone = $( '#phone').val();
                console.log(submitReq);
                $.ajax({
                    method: 'POST',
                    data: submitReq,
                    url: '../services/register.php',
                    success: function(result){
                        var s_result = result.trim();
                        if(s_result=='success'){
                            alert('Thanks for you registration!');
                            window.location.href='../app/views/home/homepage.html.twig';
                        }else{
                            alert('Sorry, your registration is failed, please try again.');
                        }
                    },
                    error: function(){
                        alert('connection error')
                    }
                });
            }
        });
    });
</script>
</html>
