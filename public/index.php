<?php

spl_autoload_register(function ($class_name) {
  include __DIR__ . '/../app/classes/' . $class_name . '.php';
});

require __DIR__ . '/../app/routes.php';