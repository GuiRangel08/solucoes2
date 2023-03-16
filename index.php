<?php
require_once __DIR__ . "/config/db.php";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$conn) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}

$sql = "SELECT
        Tb_banco.nome AS nome_do_banco,
        Tb_convenio.verba,
        MIN(Tb_contrato.data_inclusao) AS data_inclusao_mais_antiga,
        MAX(Tb_contrato.data_inclusao) AS data_inclusao_mais_nova,
        SUM(Tb_contrato.valor) AS soma_valor_contratos
    FROM
        Tb_banco
        INNER JOIN Tb_convenio ON Tb_banco.codigo = Tb_convenio.banco
        INNER JOIN Tb_convenio_servico ON Tb_convenio.codigo = Tb_convenio_servico.convenio
        INNER JOIN Tb_contrato ON Tb_convenio_servico.codigo = Tb_contrato.convenio_servico
    GROUP BY
        nome_do_banco,
        Tb_convenio.verba"

$result = mysqli_query($conn, $sql);

if (!$result) {
    $error = mysqli_error($conn);
    mysqli_close($conn);
    die("Erro ao consultar o banco de dados: " . $error);
}

echo "<table>";
echo "<tr'><th>Nome do Banco</th><th>Verba</th><th>Código do Contrato</th><th>Data de Inclusão</th><th>Valor</th><th>Prazo</th></tr>";
if (mysqli_num_rows($result) > 0) {
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['nome_banco'] . "</td>";
        echo "<td>" . $row['verba'] . "</td>";
        echo "<td>" . $row['data_contrato_antigo'] . "</td>";
        echo "<td>" . $row['data_contrato_novo'] . "</td>";
        echo "<td>" . $row['valor_total'] . "</td>";
        echo "</tr>";
    }
}
echo "</table>";

mysqli_close($conn);

?>