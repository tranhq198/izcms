<?php
ini_set('session.use_only_cookies', true);
session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='UTF-8' />

    <title>izCMS My Home page</title>

    <link rel='stylesheet' href='../css/style.css' />
</head>

<body>
<div id="container">
    <div id="header">
        <h1><a href="http://localhost/icms/index.php">izCMS</a></h1>
        <p class="slogan">The iz Content Management System</p>
    </div>
    <div id="navigation">
        <ul>
            <li><a href='index.php'>Home</a></li>
            <li><a href='#'>About</a></li>
            <li><a href='#'>Services</a></li>
            <li><a href='contact.php'>Contact us</a></li>
        </ul>

        <p class="greeting">Xin chào bạn hiền</p>
    </div><!-- end navigation-->