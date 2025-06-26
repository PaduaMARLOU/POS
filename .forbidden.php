<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>403 Forbidden</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 4rem;
            margin-bottom: 10px;
            color: #e74c3c;
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        p {
            font-size: 1rem;
            color: #666;
        }

        a {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.2s ease;
        }

        a:hover {
            background-color: #0056b3;
        }

        @media (max-width: 600px) {
            h1 { font-size: 3rem; }
            h2 { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>403</h1>
        <h2>Access Forbidden</h2>
        <p>You do not have permission to access this page.<br>
           Please contact the administrator if you believe this is an error.</p>
        <a href="logout.php">Return to Login</a>
    </div>
</body>
</html>
