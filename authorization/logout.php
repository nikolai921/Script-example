<?php
include 'function.php';

session_destroy();

redir('/authorization.php');
