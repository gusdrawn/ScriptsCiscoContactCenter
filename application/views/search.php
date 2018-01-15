<div class="starter-template">
        <h1>Busqueda de Llamadas ACD</h1>
        <p class="lead">Version 0.3 - Gustavo Guillen -  Banesco 2015</p>
      </div>
<table class="table table-bordered">
    <thead>
        <tr>
            <td>Fecha</td>
            <td>Origen</td>
            <td>Destino</td>
            <td>Nro Marcado</td>
            <td>Duración</td>
            <td>ID Agente</td>
            <td>Nombre y Apellido</td>
            <td>Afectación</td>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($row as $value) {
                    /*if ($value['CallTypeID'] == '-1') {
                            continue;
                    }*/
                    echo "<tr>\n";
                    $i=1;
                    while ($i <= 8) {
                        echo "<td>";
                        switch ($i) {
                            case 1:  echo $value['DateTime'];                           break;
                            case 2:  echo $value['ANI'];                                break;
                            case 3:  echo $value['DNIS'];                               break;
                            case 4:  echo $value['DigitsDialed'];                       break;
                            case 5:  echo $value['Duration'].' Seg.';                   break;
                            case 6:  echo $value['AgentPeripheralNumber'];              break;
                            case 7:  echo $value['FirstName'].' '.$value['LastName'];   break;
                            case 8:  echo $value['AnsweredWithinServiceLevel'];         break;
                        }
                        $i++;
                        echo "</td>\n";
                    }
                    echo "</tr>\n";
                } ?>
	</tbody>
</table>