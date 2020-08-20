<?php	
	echo '<html><head><meta http-equiv=”Content-Type” content=”text/html; charset=utf-8><title>Listagem de dispositivos</title></head><body>';	
	$hostname="shcglobal.mysql.dbaas.com.br";	
	$username="shcglobal";	
	$password="MySQL#2019!";	
	$dbname="shcglobal";	
	$conn = new mysqli($hostname, $username, $password, $dbname);	
	if ($conn->connect_error) {		
		die("ERROR: Unable to connect: " . $conn->connect_error);	
	}	
	echo '<h1>Listagem de dispositivos</h1>';	
	$sql = "SELECT 
				device.id, 
				Pessoa.nome, 
				Pessoa.sobreNome 
			FROM 
				device 
			INNER JOIN 
				Pessoa ON device.pessoaId = Pessoa.ID";	
	$result = mysqli_query($conn, $sql);	
	echo "<table border='1'>";	
	if (mysqli_num_rows($result) > 0) {		
		echo "<tr>";        
		echo "<td>ID</td>";		
		echo "<td>Nome</td>";
		echo "<td>Sobrenome</td>";		
		echo "</tr>";        
		while($row = mysqli_fetch_assoc($result)) {			
			echo "<tr>";            
			echo "<td>" . $row["id"]. "</td>";			
			echo "<td>" . $row["nome"]. "</td>";
			echo "<td>" . $row["sobreNome"]. "</td>";			
			echo "</tr>";        
		}    
	}	
	echo '</table>';    
	mysqli_close($conn);	
	echo "Página gerada as " . date('d-m-Y H:i');    
	echo '</body></html>';	
?>