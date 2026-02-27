
<?php
// Agora buscamos as configurações das variáveis de ambiente do Docker
$host = getenv('DB_HOST') ?: 'mysql-server';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD'); 
$db   = getenv('DB_NAME');

echo "<div style='text-align: center; font-family: Arial; margin-top: 30px;'>";
echo "<h1>🚀 Dashboard Protegido - Swarm</h1>";

if (!$pass || !$db) {
    echo "<h3 style='color: orange;'>⚠️ Variáveis de ambiente não configuradas!</h3>";
}

try {
    $link = new mysqli($host, $user, $pass, $db);
    if ($link->connect_error) { throw new Exception($link->connect_error); }

    $link->query("CREATE TABLE IF NOT EXISTS acessos (id INT AUTO_INCREMENT PRIMARY KEY, data_hora DATETIME, container_id VARCHAR(50))");
    $meu_id = gethostname();
    $link->query("INSERT INTO acessos (data_hora, container_id) VALUES (NOW(), '$meu_id')");

    echo "<h3 style='color: green;'>✅ Conexão Segura: OK!</h3>";
    $res = $link->query("SELECT COUNT(*) as total FROM acessos");
    $row = $res->fetch_assoc();
    echo "<p>Total de registros: <strong>" . $row['total'] . "</strong></p>";

} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Erro: " . $e->getMessage() . "</h3>";
}

echo "<h1>ID: " . gethostname() . "</h1>";
echo "</div>";
?>
