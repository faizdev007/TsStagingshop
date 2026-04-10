<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <title>Tereza Estates</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Work Sans', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.1)), url('{{ asset("assets/demo1/images/errbg.jpg") }}') center/cover no-repeat fixed;
            color: #333;
            background-color: #333;
            background-attachment: fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        .header {
            padding: 1rem 2rem;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .logo {
            height: auto;
            max-width: 200px;
            display: block;
            margin-left: 1.9rem;
        }
        .header .tagline {
            color: #ff0000;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 100px);
            padding: 2rem;
        }
        .error-card {
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 15px;
            backdrop-filter: blur(7px);
        }
        .error-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 2rem;
            font-weight: 500;
            line-height: 1.6;
        }
        .error-text {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .error-text p {
            margin-bottom: 1rem;
        }
        .home-button {
            display: inline-block;
            background: #d9b483;
            color: #fff;
            text-decoration: none;
            padding: 0.7rem 1rem;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.3s;
            margin: 0.5rem;
            font-size: 0.70rem;
        }
        .home-button:hover {
            background: #c69c6d;
            color: #fff;
            text-decoration: none;
        }
        .error-image {
            max-width: 250px;
            margin: 1.5rem auto 0;
            position: relative;
        }
        .error-image img {
            width: 100%;
            height: auto;
        }
        @keyframes smoke {
            0% { transform: translateY(0) scale(1); opacity: 0.8; }
            100% { transform: translateY(-30px) scale(0.8); opacity: 0; }
        }
        .smoke {
            position: absolute;
            top: 65%;    /* Move down to cup level */
            left: 30%;   /* Align with the blue cup */
            background: rgba(100,100,100,0.6);
            width: 8px;
            height: 12px;
            filter: blur(2px);
            border-radius: 50%;
        }
        .smoke:nth-child(1) { animation: smoke 4s infinite; }
        .smoke:nth-child(2) { 
            animation: smoke 4s infinite 1s; 
            left: 29.5%;
            height: 10px;
            opacity: 0.7;
        }
        .smoke:nth-child(3) { 
            animation: smoke 4s infinite 2s; 
            left: 30.5%;
            height: 11px;
            opacity: 0.6;
        }
        @keyframes ring {
            0% { transform: rotate(0); }
            10% { transform: rotate(15deg); }
            20% { transform: rotate(-13deg); }
            30% { transform: rotate(11deg); }
            40% { transform: rotate(-9deg); }
            50% { transform: rotate(7deg); }
            60% { transform: rotate(-5deg); }
            70% { transform: rotate(3deg); }
            80% { transform: rotate(-2deg); }
            90% { transform: rotate(1deg); }
            100% { transform: rotate(0); }
        }
        .phone-icon {
            display: inline-block;
            animation: ring 4s infinite ease-in-out;
            transform-origin: 50% 0;
            margin-right: 5px;
            color: #d9b483;
        }
        .phone-icon:hover {
            animation: ring 2s infinite ease-in-out;
        }
        .contact-number {
            color: #d9b483;
            text-decoration: none;
            transition: color 0.3s;
        }
        .contact-number:hover {
            color: #c69c6d;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .header-logo {
                margin-left: 1rem;
                max-width: 160px;
            }
            .header {
                padding: 0.8rem 1rem;
            }
            .container {
                height: calc(100vh - 80px);
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                padding-top: 0.5rem;
            }
            .error-card {
                max-width: 90%;
                padding: 1.2rem;
                margin-top: 0;
            }
            .error-title {
                font-size: 1.2rem;
                margin-bottom: 1.2rem;
                line-height: 1.4;
            }
            .error-text {
                font-size: 0.9rem;
                line-height: 1.4;
            }
            .error-text p {
                margin-bottom: 0.7rem;
            }
            .error-image {
                max-width: 180px;
                margin: 1rem auto 0;
            }
            .contact-number {
                display: block;
                margin: 0.5rem 0;
            }
            body {
                background-attachment: fixed;
                height: 100vh;
                overflow: hidden;
            }
            .container {
                height: calc(100vh - 80px);
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('assets/demo1/images/logo.svg') }}" alt="Tereza Estates" class="logo">
    </div>
    <div class="container">
        <div class="error-card">
            <div class="error-title">
                Oops, it seems like there<br>
               
                is some Technical Error!
            </div>
            <div class="error-text">
                <p>We are updating some features to make your experience even better!</p>
                <p>You can try to refresh or <a href="{{ url('/') }}" class="home-button">Go to Home Page</a></p>
                <p>Else, please come back later or<br>contact us directly at<br><a href="tel:+971585365111" class="contact-number" aria-label="Call us at +971 585 365 111"><i class="fas fa-phone phone-icon"></i>+971 585 365 111</a></p>
                <p>Rest assured, all of our technical issues are usually solved in less than 24h</p>
                <p>Thank You for understanding!</p>
            </div>
            <div class="error-image">
                <div class="smoke"></div>
                <div class="smoke"></div>
                <div class="smoke"></div>
                <img src="{{ asset('assets/demo1/images/errimg.png') }}" alt="Error Illustration">
            </div>
        </div>
    </div>
</body>
</html>
