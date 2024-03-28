<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <h2 action="{{ route('secret') }}"> Hola mundo! </h2>

    <div>
        <a href="{{ route('logout') }}"> <button type="button"> Salir </button></a>
    </div>

    <button type="button" id="generateReportBtn">Generar Informe</button>

    <script>
        document.getElementById('generateReportBtn').addEventListener('click', function() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '{{ route('tasks.generateReport') }}', true);
            xhr.responseType = 'blob';
            xhr.onload = function() {
                if (this.status === 200) {
                    var blob = new Blob([this.response], {
                        type: 'application/pdf'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'report.pdf';
                    link.click();
                }
            };
            xhr.send();
        });
    </script>
</body>

</html>
