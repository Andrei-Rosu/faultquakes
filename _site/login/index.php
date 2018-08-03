---
layout: default
---



<a href="login.php"><button class="loginbutton" type="submit">Se connecter</button></a>
<div id="section">
    <h1>Inscription</h1>
    <form method="post" action="index.php">
        <div class="form-row">
            <div class="form-group col-md-6">
        <input type="text" name="name" placeholder="nom"/>
            </div>
            <div class="form-group col-md-6">
        <input type="text" name="username" placeholder="pseudo"/>
            </div>
            <div class="form-group col-md-6">
        <input type="email" name="email" placeholder="email"/>
            </div>
            <div class="form-group col-md-6">
        <input type="password" name="password" placeholder="mot de passe" />
            </div>
        </div>
        <button type="submit" name="submit">S'inscrire</button><br>
        <span class="text"><?php if(isset($message)) {echo $message;}?></span>
        <span class="text"><?php if(isset($error)){ echo $error ;}?></span>
    </form>
</div>
---
title: "Failles et Siesmes"
layout: login
excerpt: "Failles et Siesmes @ ENS"
sitemap: false
permalink: /login.php
---


{% raw %}
<?php
session_start();
$error="";
if(isset($_POST['submit']))
{
include('lib/connection.php');
if(empty($_POST['name']) || empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']))
{
	$error="Please enter all the details first";
}
$name=mysqli_escape_string($conn,filter_var(strip_tags($_POST['name']),FILTER_SANITIZE_STRIPPED));
$username=mysqli_escape_string($conn,filter_var(strip_tags($_POST['username']),FILTER_SANITIZE_STRIPPED));
$password=mysqli_escape_string($conn,filter_var(strip_tags($_POST['password']),FILTER_SANITIZE_STRIPPED));
$email=mysqli_escape_string($conn,filter_var(strip_tags($_POST['email']),FILTER_VALIDATE_EMAIL));
$hash_password = hash('sha256', $password);
$activation_code = hash('sha256',rand(0,1000));
    $sql="SELECT UserName FROM users WHERE UserName='$username'";
	$result=mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0)
{
$error="UserName already Exists";
}
$sql="SELECT Email FROM users WHERE Email='$email'";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)>0)
{
$error.="And Email already exists";
}
if(empty($error))
{
$sql="INSERT INTO users (Name,UserName,Email,Activation_Code,Password) VALUES('$name','$username','$email','$activation_code','$hash_password')";
$result=mysqli_query($conn,$sql);
if($result)
{
$_SESSION['email']=$email;
$_SESSION['username']=$username;
$_SESSION['password']=$password;
$_SESSION['hash_password']=$hash_password;
$_SESSION['activation_code']=$activation_code;
include('activateemail.php');
$message="Please check your email to activate your account";
}

}
}
?>
{% endraw %}


