<?php

$host = 'localhost';
  $database = 'firstSiteBd';
  $user = 'root';
  $pass = '';

  $dsn = "mysql:host=$host;dbname=$database;";
  $options = array(
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  );
  $pdo = new PDO($dsn, $user, $pass, $options);
  $pdo->exec("set names utf8");