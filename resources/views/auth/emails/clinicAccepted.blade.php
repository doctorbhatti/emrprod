<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Accepted</title>
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
            color: #28a745;
        }

        p {
            line-height: 1.6;
            font-size: 16px;
        }

        a {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        a:hover {
            background: #218838;
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

        <h3>Your Clinic Has Been Accepted!</h3>
        <p>
            Congratulations! Your clinic has been accepted. You can now access your account and manage your clinic's
            operations.
            Please visit the following link to log in to your account:
        </p>
        <a href="{{ url('login') }}" target="_blank">Click Here to Login</a>

        <!-- Contact Section -->
        <div class="contact">
            <p>If you have any questions, feel free to contact us:</p>
            <p>Email: <a href="mailto:support@clinic.com">support@clinic.com</a></p>
            <p>Phone: <a href="tel:+923276798673">+92 (327) 679-8673</a></p>
        </div>

        <!-- Copyright Section -->
        <div class="copyright">
            &copy; {{ date('Y') }} Healthy Life Clinic | EMR Systems. All rights reserved.
        </div>
    </div>
</body>

</html>