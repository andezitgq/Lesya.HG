<html>
    <head>
        <style>
            body {
                background: #13324f;
                color: white;
                font-family: 'Montserrat', sans-serif;
                font-weight: lighter;
                font-size: 40px;
                display:flex;
                justify-content: center;
                align-items: center;
            }
            
            .error-box {
                display: flex;
                justify-content: center;
                align-items: center;
                margin: auto;
            }
            
            .error-box div {
                margin-left: 5px;
                margin-right: 5px;
                background: white;
                width: 2px;
                height: 40px;
            }
        </style>
        <title><?php echo $code; ?> | <?php echo $error; ?></title>
    </head>
    <body>
        <div class="error-box">
            <p><?php echo $code; ?></p>
            <div></div>
            <p><?php echo $error; ?></p>
        </div>
        <script type="text/javascript">
            
        </script>
    </body>
</html>