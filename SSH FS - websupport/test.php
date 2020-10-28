<!DOCTYPE html>
<html>



<head>
<style>

* {
    margin: 0;
    padding: 0;
    border: 0;
    outline: 0;
    vertical-align: baseline;
    background: transparent;
    box-sizing: border-box;
    font-family: -apple-system,BlinkMacSystemFont,"segoe ui",roboto,oxygen,ubuntu,cantarell,"fira sans","droid sans","helvetica neue",Arial,sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale
}


.header {
    background-color: #003580;
    padding: 1%;
    width: 100%;
}

.headerH1{
    width: 20%;
    color: white;
    margin: 0 auto;
    text-align: center;
    display: inline-block;
    margin-left: 40%;
}

.headerH1 h1 {
    text-align: center;
}

.buttons{
    display: inline-block;
    width: 20%;
    text-align: center;
    float: right;
    height: 43px;
}

.clearfix::after {
  content: "";
  clear: both;
  display: table;
}

.actionButtons {
    background-color: #fff;
    border: 1px solid #003580;
    transition-duration: .1s;
    width: 100px;
    height: 43px;
}

.actionButtons:hover {
    cursor: pointer;
    font-weight: 700;
    box-shadow: 0 12px 16px 0 rgba(0,0,0,.24),0 17px 50px 0 rgba(0,0,0,.19)
}

</style>
</head>

<body>
<div class="header">
    <div class="headerH1">
        <h1>Hilton Hotels</h1>
    </div>
    <div class="buttons">
        <button onclick="window.location.href='signinView.php';" type="button" id="LoginButton" class="actionButtons">Sign in</button>
        <button onclick="window.location.href='registerView.php';" type="button" id="RegisterButton" class="actionButtons">Register</button>
    </div>
</div>
</body>

</html>