<?php
// Datos de conexión SSH
$host = 'ipServer';
$port = sshport;
$username = 'root';
$password = 'password';

// Inicia la conexión SSH
$connection = ssh2_connect($host, $port);
if (!$connection) {
    die('Error al conectar al servidor remoto');
}

// Autentica la conexión SSH
if (!ssh2_auth_password($connection, $username, $password)) {
    die('Error de autenticación');
}

// Ejecuta los comandos para obtener el uso de CPU y RAM en el servidor remoto
$cpu_command = "top -bn1 | grep 'Cpu(s)' | awk '{printf \"CPU: %.2f%%\", \$2+\$4}'";
$ram_command = "top -bn1 | grep 'MiB Mem' | awk '{printf \"RAM: %.2f%%\", \$6/\$4*100}'";
$cpu_stream = ssh2_exec($connection, $cpu_command);
$ram_stream = ssh2_exec($connection, $ram_command);

// Obtiene la salida de los comandos
stream_set_blocking($cpu_stream, true);
stream_set_blocking($ram_stream, true);
$cpu_output = stream_get_contents($cpu_stream);
$ram_output = stream_get_contents($ram_stream);
fclose($cpu_stream);
fclose($ram_stream);

// Imprime el uso de CPU y RAM en el servidor remoto
echo $cpu_output . " " . $ram_output;
?>
