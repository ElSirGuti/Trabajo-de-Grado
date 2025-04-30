<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Bot Test</title>
</head>
<body>
    <h1>Chat Bot</h1>
    <input type="text" id="text">

    <br><br>

    <button onclick="generateResponse();">Generar Respuesta</button>

    <br><br>

    <div id="response"></div>

    <script>
        function generateResponse() {
            var text = document.getElementById("text");
            var response = document.getElementById("response");

            fetch("response.php", {
                method: "post",
                body: JSON.stringify({
                    "text": text.value
                }),
            }).then(res => res.text()).then(data => {
                response.innerHTML = data;
            });
        }
    </script>
</body>
</html>