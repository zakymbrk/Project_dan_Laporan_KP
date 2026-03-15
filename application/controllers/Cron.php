<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Allow access without authentication for cron jobs
        // You might want to secure this with IP restrictions or keys in production
    }

    }