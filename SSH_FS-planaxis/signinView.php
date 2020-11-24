<?php
    include_once("include.php");
    sessionTimeout();

printHTMLheader( "Sign in" );
printHeader();
    print
    ("
    <style>
        .header{
            background-color: #003580;
            padding: 1%;
        }

        .header h1{
            margin: 0 auto;
            display: inline-block;
            color: white;
        }

        .RegisterForm {
            padding: 16px;
            width: 40%;
            margin: 0 auto;
        }

        input[type=text], input[type=password] {
            width: 96%;
            padding: 15px;
            margin: 5px 0 22px 0;
            display: inline-block;
            border: none;
            background: #f1f1f1;
        }

        input[type=text]:focus, input[type=password]:focus {
            background-color: #ddd;
            outline: none;
        }

        hr {
            border: 1px solid #f1f1f1;
            margin-bottom: 25px;
        }

        .registerbtn {
            background-color: #005C9D;
            color: white;
            padding: 16px 20px;
            margin: 0 auto;
            border: none;
            cursor: pointer;
            width: 100%;
            opacity: 0.9;
        }

        .registerbtn:hover {
            opacity:1;
        }

        a {
            color: dodgerblue;
        }

        .signin {
            background-color: #f1f1f1;
            text-align: center;
        }

    </style>
</head>

<body>
    ");
    
    ?>
    <form action="authenticate.php" method="post">
        <div class="RegisterForm">
            <h1>Sign in</h1>
            <p>Please fill in this form to Sign in.</p>
            <hr>

            <label class="required" for="email"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" name="email" id="email" required>

            <label class="required" for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" id="password" required>

            <hr>

            <button type="submit" class="registerbtn">Sign in</button>
        </div>
    
        <div class="RegisterForm signin">
            <p>Don't have an account? <a href="registerView.php">Register</a>.</p>
        </div>
    </form>  

</body>

</html>