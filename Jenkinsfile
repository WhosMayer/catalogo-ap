pipeline {
    agent any
    options { disableConcurrentBuilds(); timestamps(); buildDiscarder(logRotator(numToKeepStr: '20')) }
    triggers { pollSCM('H/2 * * * *') }
    parameters {
        string(name: 'PHP_EXE', defaultValue: 'C:\\xampp\\php\\php.exe', description: 'Ruta de PHP en el agente')
        string(name: 'DEPLOY_DIR', defaultValue: 'C:\\xampp\\htdocs\\catalogo-ap', description: 'Carpeta de publicación de esta aplicación')
    }
    stages {
        stage('Obtener código de Git') { steps { checkout scm } }
        stage('Validar PHP y probar requisitos') { steps { bat '"%PHP_EXE%" ci\\check.php' } }
        stage('Empaquetar entrega') {
            steps {
                powershell '''
                    New-Item -ItemType Directory -Force -Path build | Out-Null
                    $archivos = @('index.php','login.php','Logout.php','registro.php','catalogo.php','config','includes','assets','app')
                    Compress-Archive -Path $archivos -DestinationPath build/catalogo-ap.zip -Force
                    "Build: $env:BUILD_NUMBER`nCommit: $env:GIT_COMMIT`nURL: $env:BUILD_URL" | Set-Content build/version.txt
                '''
                archiveArtifacts artifacts: 'build/*', fingerprint: true
            }
        }
        stage('Entrega continua a XAMPP') {
            steps {
                powershell '''
                    $destino = [IO.Path]::GetFullPath($env:DEPLOY_DIR)
                    if ((Split-Path $destino -Leaf) -ne 'catalogo-ap') { throw 'La carpeta final debe llamarse catalogo-ap.' }
                    New-Item -ItemType Directory -Force -Path $destino | Out-Null
                    Expand-Archive -LiteralPath build/catalogo-ap.zip -DestinationPath $destino -Force
                    Copy-Item -LiteralPath build/version.txt -Destination (Join-Path $destino 'version.txt')
                '''
            }
        }
    }
    post {
        success { echo 'Pruebas aprobadas y aplicación publicada. Revisa los cambios y version.txt en XAMPP.' }
        failure { echo 'La ejecución falló. Consulta Console Output; si fallan las pruebas no se publica.' }
    }
}
