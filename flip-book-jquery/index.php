<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="js/jquery.min.js"></script>
</head>
<body>

<div class="source-link">
    <a id="show-source" href="#" data="simple-jquery-pdf">Show source code</a>
</div>

<div class="sample-container">
    <div>

    </div>
</div>






<script src="js/html2canvas.min.js"></script>
<script src="js/three.min.js"></script>
<script src="js/pdf.min.js"></script>
<script src="js/3dflipbook.min.js"></script>
<script type="text/javascript">
    jQuery('.sample-container div').FlipBook({pdf: 'dergi.pdf'});
</script>
</body>
</html>
