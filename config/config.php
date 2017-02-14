<?php
/**
 * @Author: Dominik Ryńko
*/

// System configuration
$config['system']['default_timezone'] = 'Europe/Warsaw';
$config['system']['charset'] = 'UTF-8';
$config['system']['locale'] = 'pl_PL';
$config['system']['language'] = 'pl';
$config['system']['time'] = time();
$config['system']['error'] = ['E_ALL', 1];

// Database connection
$config['db']['driver'] = "mysql:";
$config['db']['host'] = "localhost";
$config['db']['user'] = "root";
$config['db']['password'] = "";
$config['db']['db_name'] = "todo";
$config['db']['db_table'] = "notes";

// Directories paths
$config['path']['template'] = 'template/';
$config['path']['subpages'] = 'template/subpages/';
$config['path']['css'] = 'template/css/';
$config['path']['js'] = 'template/js';
$config['path']['jsonPath'] = 'storage/';