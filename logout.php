<?php
require_once __DIR__ . '/config/auth.php';
session_start(); session_destroy();
header('Location: ' . appUrl('login.php')); exit;
