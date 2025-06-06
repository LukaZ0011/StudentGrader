<?php

// Require authentication to view this page
requireAuth();

$heading = 'About Us';

require __DIR__ . "/../views/about.view.php";
