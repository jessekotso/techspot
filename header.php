<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Spot | Home</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Reset basic elements */
        body, h1, h2, h3, h4, h5, h6, p, ul {
            margin: 0;
            padding: 0;
        }

        /* Global font and background */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }

        /* Header styling inspired by Behance */
        header {
            background-color: #0057ff;
            padding: 10px 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between; /* Adjusts spacing of elements */
            align-items: center;
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            margin-left: 100px; /* This pushes the logo to the right */
        }

        .logo img {
            height: 70px;
            margin-right: 100px;
        }

        /* Navigation styling */
        nav {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        nav ul {
            list-style-type: none;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-left: auto;
        }

        nav ul li {
            margin: 0 1px;
        }

        nav ul li a {
            color: #ffffff;
            text-decoration: none;
            font-size: 15px;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 20px;
            transition: background-color 0.3s, color 0.3s;
            position: relative;
            overflow: hidden;
        }

        nav ul li a:hover {
            color: #fff;
        }

        nav ul li a:hover,
        nav ul li a.active {
            color: #ffffff;
            background-color: #003bb5;
        }

        /* Search bar */
        .search-bar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-left: 20px;
            position: relative;
        }

        .search-bar input[type="text"] {
            padding: 8px 20px;
            border: 1px solid #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 250px;
            font-size: 14px;
            background-color: #ffffff;
            transition: border 0.3s ease;
        }

        .search-bar input[type="text"]::placeholder {
            color: #999;
        }

        .search-bar input[type="text"]:focus {
            outline: none;
            border: 1px solid #003bb5;
        }



        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px 20px;
            }

            nav ul {
                margin-left: 0;
                margin-top: 10px;
                justify-content: center;
            }

            nav ul li {
                margin: 5px;
            }

            .search-bar {
                margin-top: 10px;
                width: 100%;
            }

            .social-icons {
                margin-top: 10px;
                justify-content: center;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">
            <img src="img/logo.png" alt="Tech Spot Logo">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="training.php">Training</a></li>
                <li><a href="services.php">Services</a></li>
                
                <li><a href="about.php">About Us</a></li>
                

                <li><a href="login.php">My Account</a></li>
            </ul>
        </nav>
        <div class="search-bar">
            <input type="text" placeholder="Search...">
        </div>
    </header>
</body>

</html>
