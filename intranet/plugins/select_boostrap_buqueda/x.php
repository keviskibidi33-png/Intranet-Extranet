<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <link rel="stylesheet" href="dist/css/bootstrap-select.css">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.js" defer></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js" defer></script>
  <script src="dist/js/bootstrap-select.js" defer></script>
</head>

<body>


  <div class="form-group">
    <label for="tokens">Key words (data-tokens)</label>
    <select id="tokens" class="selectpicker form-control" multiple data-live-search="true">
      <option data-tokens="first">I actually am called "one"</option>
      <option data-tokens="second">And me "two"</option>
      <option data-tokens="last">I am "three"</option>
      <option data-tokens="luigi">luigi</option>
    </select>
  </div>


</body>
</html>