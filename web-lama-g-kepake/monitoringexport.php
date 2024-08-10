<?php
include 'fungsi.php' ;
include 'header.php';
// $pengguna = query("SELECT * FROM pengguna");


$query_all = query("SELECT * from data_monitoring ORDER BY LENGTH(id),CAST(id AS UNSIGNED)");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMElectric | Monitoring</title>
    <!-- <link rel="stylesheet" href="./css/style.css"> -->
    <link rel="icon" href="./img/PM.ico">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>

    <!-- Ini Tabel -->
    <div class="tabular--wrapper">
        <h3 class="main--title">Data Project</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID PROJECT</th>
                        <th>NO</th>
                        <th>DATE PBI</th>
                        <th>NAME PROJECT</th>
                        <th>NAME PANEL</th>
                        <th>PBI</th>
                        <th>HEIGHT</th>
                        <th>WIDTH</th>
                        <th>DEEP</th>
                        <th>TYPE PANEL</th>
                        <th>UNIT</th>
                        <th>CELL</th>
                        <th>TARGET PPC</th>
                        <th>TARGET ENG</th>
                        <th>ID USER INPUT</th>
                        <th>DESIGN PIC</th>
                        <th>DESIGN START</th>
                        <th>DESIGN END</th>
                        <th>NESTING PIC </th>
                        <th>NESTING START</th>
                        <th>NESTING END</th>
                        <th>PROGRAM PIC</th>
                        <th>PROGRAM START</th>
                        <th>PROGRAM END</th>
                        <th>CHECKER PIC</th>
                        <th>CHECKER START</th>
                        <th>CHECKER END</th>
                        <th>ENG PANEL</th>
                        <th>BREAKDOWN</th>
                        <th>BUSBAR</th>


                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($query_all as $row): ?>

                    <tr>
                        <td>
                            <?= $row["id"]; ?>
                        </td>
                        <td>
                            <?= $row["nomor"]; ?>
                        </td>
                        <td>
                            <?= $row["date_pbi"]; ?>
                        </td>
                        <td>
                            <?= $row["nama_project"]; ?>
                        </td>
                        <td>
                            <?= $row["nama_panel"]; ?>
                        </td>
                        <td>
                            <?= $row["pbi"]; ?>
                        </td>
                        <td>
                            <?= $row["size_panel_height"]; ?>
                        </td>
                        <td>
                            <?= $row["size_panel_width"]; ?>
                        </td>
                        <td>
                            <?= $row["size_panel_deep"]; ?>
                        </td>
                        <td>
                            <?= $row["type_panel"]; ?>
                        </td>
                        <td>
                            <?= $row["unit"]; ?>
                        </td>
                        <td>
                            <?= $row["cell"]; ?>
                        </td>
                        <td>
                            <?= $row["target_ppc"]; ?>
                        </td>
                        <td>
                            <?= $row["target_eng"]; ?>
                        </td>

                        <td>
                            <?= $row["design_pic"]; ?>
                        </td>
                        <td>
                            <?= $row["design_start"]; ?>
                        </td>
                        <td>
                            <?= $row["design_end"]; ?>
                        </td>
                        <td>
                            <?= $row["nesting_pic"]; ?>
                        </td>
                        <td>
                            <?= $row["nesting_start"]; ?>
                        </td>
                        <td>
                            <?= $row["nesting_end"]; ?>
                        </td>
                        <td>
                            <?= $row["program_pic"]; ?>
                        </td>
                        <td>
                            <?= $row["program_start"]; ?>
                        </td>
                        <td>
                            <?= $row["program_end"]; ?>
                        </td>
                        <td>
                            <?= $row["checker_pic"]; ?>
                        </td>
                        <td>
                            <?= $row["checker_start"]; ?>
                        </td>
                        <td>
                            <?= $row["checker_end"]; ?>
                        </td>
                        <td>
                            <?= $row["keterangan"]; ?>
                        </td>
                        <td>
                            <?= $row["eng_panel"]; ?>
                        </td>
                        <td>
                            <?= $row["busbar"]; ?>
                        </td>
                    </tr>
                    <?php $i++; ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
    </div>

    </div>

    </div>
    </div>

</body>

</html>