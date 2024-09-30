<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .footer-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .footer-logo img {
            max-width: 150px;
            height: auto;
        }

        h3 {
            color: #007bff;
        }

        a {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        a:hover {
            background: #0056b3;
        }

        .contact {
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }

        .contact p {
            margin: 5px 0;
        }

        .copyright {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="footer-logo">
            <img src="{{ asset('logo.png') }}" alt="Clinic Logo">
        </div>
        <h2>Healthy Life Clinic | EMR Systems</h2>
        <h3>Your Password Reset Link</h3>
        <p>
            Click the link below to reset your password. Only the admin account's password can be reset through email.
            If you need to reset the password of another type of account, please contact your admin.
        </p>
        <a href="{{ $link = url('password/reset', $token) . '?email=' }}">
            Click Here to Reset Your Password
        </a>

        <!-- Contact Section -->
        <div class="contact">
            <p>If you have any questions, feel free to contact us:</p>
            <p>Email: <a href="mailto:healthylifeclinicemr@gmail.com">healthylifeclinicemr@gmail.com
                </a></p>
            <p>Phone: <a href="tel:+923276798673">+92 (327) 679-8673</a></p>
        </div>

        <!-- Copyright Section -->
        <div class="copyright">
            &copy; {{ date('Y') }} Healthy Life Clinic EMR Systems. All rights reserved.
        </div>
    </div>
</body>

</html>