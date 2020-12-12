<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = true;

$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['forgot-password'] = 'auth/forgot_password';
$route['recovery-password'] = 'auth/recovery_password';
$route['register'] = 'auth/register';
