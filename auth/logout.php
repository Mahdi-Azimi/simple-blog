<?php
require_once __DIR__ . '/../includes/functions.php';

$_SESSION = [];
session_destroy();

session_start();
set_flash('success', 'با موفقیت خارج شدید.');
redirect(BASE_URL . '/auth/login.php');
