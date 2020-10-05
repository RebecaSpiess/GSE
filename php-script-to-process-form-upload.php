<?php 
error_log("Entrou");
$csv_mimetypes = array('text/csv', 'text/plain', 'application/csv', 'text/comma-separated-values', 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel', 'text/anytext', 'application/octet-stream', 'application/txt');
if (in_array($_FILES['csvfile']['type'], $csv_mimetypes)) {
    /* Grab the location of this PHP script and change the path to a different location where we can save the data */
    $filePathRaw = dirname(__FILE__);
    $filePathSegments = explode("/", $filePathRaw);
    $filePath = "C:\Users\spies\Documents\arquivos_CSV";
    /* Generate a filename for the CSV file */
    $token = date("YmdHis");
    /* Save the CSV data */
    $rawCSV = file_get_contents($_FILES['csvfile']['tmp_name']);
    $fileCSV = fopen($filePath . "/" . $token . ".csv", "w");
    fwrite($fileCSV, $rawCSV);
    fclose($fileCSV);
    //chmod($filePath . "/" . $token . ".txt", 0644);
    
    $rawCSV = fopen($filePath . "/" . $token . ".csv", "r");
    /*while(!feof($fileCSV)) {
        $linearr = fgetcsv($fileCSV, 1, ';', '"');
        error_log($linearr);
        $column1 = trim($linearr[0]);
        if (!isset($column1)){
            break;    
        }*/
    if ($rawCSV) {
        while (($buffer = fgets($rawCSV, 4096)) !== false) {
            //echo $buffer;
            error_log($buffer);
        }
        if (!feof($rawCSV)) {
            echo "Erro: falha inexperada de fgets()\n";
        }
        
        fclose($rawCSV);
    }
       // error_log($column1);

    
    /* Get the content of the CSV file 
    $fCSV = $filePath . "/" . $token . ".txt";
    if (file_exists($fCSV)) {
        $fcnt = fopen($fCSV, "r");
        $fsize = filesize($fCSV);
        $csvresults = fread($fcnt, $fsize);
        fclose($fcnt);
        header("Content-Transfer-Encoding: UTF-8");
        header("Cache-Control: public");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-Type: text/plain");
        header("Location: turma_cadastro.php");
    } */
}
?>