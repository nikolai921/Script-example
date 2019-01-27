<?php
include 'function.php';

headerUser ('/admin.php', 'Admin');

newLink ('/authorization.php', 'Login Page');
echo '</br>';
newLink ('/main.php', 'Main');
echo '</br>';

usersTable($link);




