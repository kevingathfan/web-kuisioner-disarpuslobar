<?php
// Route client to public entry point if URL rewriting is not fully configured
header('Location: public/');
exit;
