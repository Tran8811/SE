<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">

  <style>
    @import url("https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css");

    * {
      -webkit-font-smoothing: antialiased;
      box-sizing: border-box;
    }

    html, body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #d0eddf;
      margin: 0;
    }

    .container {
      width: 375px;
      height: 100vh;
      max-height: 50%;
      background-color: #fdf1ea;
      overflow: auto;
      display: flex;
      flex-direction: column;
    }

    .centered {
      align-items: center;
      justify-content: center;
      position: relative;
    }

    input {
      background-color: #ffffff;
      color: #333333;
      border: 2px solid #7288a3;
      padding: 10px;
      border-radius: 5px;
    }

    input:focus {
      background-color: #f0f8ff;
      border-color: #784d46;
    }

    input::placeholder {
      color: #999999;
    }

    label {
      color: #56674d;
      font-weight: bold;
      font-size: 16px;
    }

     button{
      width: 70%;
      height: 35%;
      background-color: #d5e1d3;
      border-radius: 5px;
      border: none;
      font-family: "Inknut Antiqua", serif;
      font-weight: 400;
      color: #000000;
      font-size: 18px;
      text-align: center;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      pointer-events: auto;
    }

    button:hover {
      background-color: #879b87;
      color: #fff;
    }

    h1 {
      color: #130202;
      font-family: "Inria Serif-Regular", Helvetica, serif;
      font-size: 24px;
      font-weight: bold;
      text-align: center;
    }


  </style>
</head>
<body>

<div class="container centered">
  <h1>Forgot Password</h1>
  <form method="post" action="send-password-reset.php">
    <label for="email">Email</label>
    <input type="email" name="email" id="email">
    <button>Send</button>
  </form>
</div>

</body>
</html>

