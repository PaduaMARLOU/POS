<?php
echo password_hash("admin123", PASSWORD_DEFAULT);
// This will output a hashed password that you can use to store in your database.
// Make sure to store this hash in your database for the user 'admin'. 