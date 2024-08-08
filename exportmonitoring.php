<?php

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; Filename = DataMonitoringPME.xls");

require 'monitoringexport.php';

?>