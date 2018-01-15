<ul class="nav nav-tabs">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Script</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Llamadas</a></li>
</ul>
<div class="tab-content">
   <div role="tabpanel" class="tab-pane active" id="home">
    <div class="starter-template">
          <h1>Generación de Scripts - Migración de Agencias</h1>
          <p class="lead">Version 0.1 - Gustavo Guillen -  Banesco 2015</p>
        </div>
        <form action="<?php echo base_url('search/reporte')?>" method="post" accept-charset="utf-8">
        <div class="form-inline">

            <div class="form-group">
              <label for="agencia">Agencia</label>
              <input type="number" class="form-control" name="agencia" placeholder="Codigo de Agencia" required>
            </div>
            <div class="form-group">
              <label for="ipvoip">Dirección VOIP</label>
              <input type="text" class="form-control" name="ipvoip" placeholder="Dirección IP de VOZ" required>
            </div>
            <div class="form-group">
              <label for="version">Script</label>
              <select  class="form-control" name="version">
                <option value="2">TIP 7</option>
                <option value="1" selected>TIP</option>
                <option value="3">SW</option>
                <option value="4">RT 9</option>
              </select>
            </div>
<div class="form-group">
 <label for="interfacelan">Interface LAN/VOIP</label>
                    <select  class="form-control" name="interfacelan">
                      <option value="GigabitEthernet0/0" selected>GigabitEthernet0/0</option>
                      <option value="Fastethernet0/0">FastEthernet0/0</option>
                      <option value="GigabitEthernet0/1">GigabitEthernet0/0</option>
                      <option value="Fastethernet0/1">FastEthernet0/0</option>
                  </select>
            </div>
            </div>
                <!--<div class="form-group">
                <label for="grupo">Grupo Call-Manager</label>
                  <select  class="form-control" name="grupo">
                      <option value="1">Agencias Grupo 1</option>
                      <option value="2">Agencias Grupo 2</option>
                    </select>
                </div>-->
                  <div class="form-group">
                    <label for="script">Script</label>
                    <textarea rows="10" cols="80" name="script" class="form-control" ></textarea>
                  </div>
                  <div class="form-group form-inline">
                    <label for="slot0">Slot 0</label>
                    <select  class="form-control form-inline" name="slot0">
                      <option value="1">Serial 1</option>
                      <option value="2" selected>Serial 2</option>
                      <option value="3">2 FXO</option>
                      <option value="4">4 FXO</option>
                      <option value="5">2 FXS</option>
                      <option value="6">4 FXS</option>              
                      <option value="7">Ninguno</option> 
                    </select>
                    <label for="slot1">Slot 1</label>
                    <select  class="form-control" name="slot1">
                      <option value="1">Serial 1</option>
                      <option value="2">Serial 2</option>
                      <option value="3">2 FXO</option>
                      <option value="4" selected>4 FXO</option>
                      <option value="5">2 FXS</option>
                      <option value="6">4 FXS</option>       
                      <option value="7">Ninguno</option>        
                    </select>
                    <label for="slot2">Slot 2</label>
                    <select  class="form-control" name="slot2">
                      <option value="1">Serial 1</option>
                      <option value="2">Serial 2</option>
                      <option value="3">2 FXO</option>
                      <option value="4">4 FXO</option>
                      <option value="5">2 FXS</option>
                      <option value="6" selected>4 FXS</option>              
                      <option value="7">Ninguno</option> 
                    </select>
                    <label for="slot3">Slot 3</label>
                    <select  class="form-control" name="slot3">
                      <option value="1">Serial 1</option>
                      <option value="2">Serial 2</option>
                      <option value="3">2 FXO</option>
                      <option value="4">4 FXO</option>
                      <option value="5">2 FXS</option>
                      <option value="6">4 FXS</option>     
                      <option value="7" selected>Ninguno</option>            
                    </select>
                  </div>
                  <div class="form-group form-inline">
                    <label for="lineafxo1">Linea 1</label>
                    <input type="number" class="form-control" name="lineafxo[]" placeholder="Linea 1 FXO">
                    <label for="lineafxo2">Linea 2</label>
                    <input type="number" class="form-control" name="lineafxo[]" placeholder="Linea 2 FXO">
                    <label for="lineafxo3">Linea 3</label>
                    <input type="number" class="form-control" name="lineafxo[]" placeholder="Linea 3 FXO">
                  </div>
                  <div class="form-group form-inline">
                    <label for="lineafxo4">Linea 4</label>
                    <input type="number" class="form-control" name="lineafxo[]" placeholder="Linea 4 FXO">
                    <label for="lineafxo5">Linea 5</label>
                    <input type="number" class="form-control" name="lineafxo[]" placeholder="Linea 5 FXO">
                    <label for="lineafxo6">Linea 6</label>
                    <input type="number" class="form-control" name="lineafxo[]" placeholder="Linea 6 FXO">
                  </div>
                  <div class="form-group form-inline">
                    <label for="lineafxs1">Linea 1</label>
                    <input type="number" class="form-control" name="lineafxs[]" placeholder="Linea 1 FXS">
                    <label for="lineafxs1">Linea 2</label>
                    <input type="number" class="form-control" name="lineafxs[]" placeholder="Linea 2 FXS">
                    <label for="lineafxs1">Linea 3</label>
                    <input type="number" class="form-control" name="lineafxs[]" placeholder="Linea 3 FXS">
                  </div>
                  <div class="form-group form-inline">
                    <label for="lineafxs1">Linea 4</label>
                    <input type="number" class="form-control" name="lineafxs[]" placeholder="Linea 4 FXS">
                    <label for="lineafxs1">Linea 5</label>
                    <input type="number" class="form-control" name="lineafxs[]" placeholder="Linea 5 FXS">
                    <label for="lineafxs1">Linea 6</label>
                    <input type="number" class="form-control" name="lineafxs[]" placeholder="Linea 6 FXS">
                  </div>
                  <ol class="breadcrumb">
                    <li class="active">Script para RT</li>
                  </ol>
                  <div class="form-group form-inline">
                  <div class="form-group">
                    <label for="modelo">Modelo</label>
                    <input type="text" class="form-control" name="modelo" value="2911" placeholder="Modelo de Router" required>
                  </div>
                  <div class="form-group">
                    <label for="region">Region</label>
                      <select  class="form-control" name="region">
                        <option value="0" selected>Metropolitana</option>
                        <option value="1">Oriente</option>
                        <option value="2">Occidente</option>
                        <option value="3">Centro de los Llanos</option>
                        <option value="4">Zulia-Falcon</option>
                        <option value="5">Central Andida</option>
                      </select>
                  </div>
                  <label for="iploop">IP Loopback</label>
                    <input type="text" class="form-control" name="iploop" value="" placeholder="IP Loopback">
                 
            
            <label for="iplan">Dirección LAN</label>
                        <input type="text" class="form-control" name="iplan" placeholder="Dirección IP de Router">     
          
          </div>
          <ol class="breadcrumb">
            <li class="active">Script para Switch</li>
          </ol>
          <div class="form-group form-inline">
            <div class="alert alert-warning" role="alert">El campo Region y Dirección LAN es necesario</div>
            <label for="switch">Modelo</label>
            <input type="text" class="form-control" name="switch" value="3560" placeholder="Modelo del Switch">

            <label for="nswitch">Switch Nro</label>
            <select  class="form-control" name="nswitch">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>
            <label for="pswitch">Nro Puertos</label>
            <select  class="form-control" name="pswitch">
              <option value="1" selected>24</option>
              <option value="2">48</option>
            </select>
          </div>
          <input type="submit" class="btn btn-info" value="Descargar">
        </form>
          </div>
          <div role="tabpanel" class="tab-pane" id="profile">
     <div class="starter-template">
        <h1>Busqueda de Llamadas ACD</h1>
        <p class="lead">Version 0.3 - Gustavo Guillen -  Banesco 2015</p>
      </div>
        
        <form action="<?php echo base_url('search/acd')?>" method="post" accept-charset="utf-8">
          <div class="form-group">
            <label for="ani">ANI</label>
            <input type="number" class="form-control" name="ani" placeholder="Quien Llamo" value="">
          </div>
          <div class="form-group">
            <label for="dnis">DNIS</label>
            <input type="number" class="form-control" name="dnis" placeholder="Persona Llamada" value="">
          </div>
          <div class="form-group">
            <label for="acd">ACD</label>
            <select  class="form-control" name="acd">
                <option value="">No buscar</option>
                <option value="ACD000">Test ACD</option>
                <option value="ACD001">Mont.Event</option>
                <option value="ACD002">Sop.Distr</option>
                <option value="ACD003">Sop.Centra</option>
                <option value="ACD004">Operaciones</option>
                <option value="ACD005">Sop.Comuni</option>
                <option value="ACD006">ServClient</option>
                <option value="ACD007">COS</option>
                <option value="ACD008">SopPostVenta</option>
                <option value="ACD009">CAT</option>
                <option value="ACD010">GciaRequerimiento.Serv</option>
                <option value="ACD011">Telemercadeo</option>
                <option value="ACD012">SupervisoresCAT</option>
                <option value="ACD013">Cobranza</option>  
                <option value="ACD014">Cobranza</option>  
                <option value="ACD015">Cobranza</option>  
                <option value="ACD016">Cobranza</option>  
                <option value="ACD017">Cobranza</option>  
                <option value="ACD018">Cobranza</option>  
                <option value="ACD019">Monitoreo y Fraude</option>  
              
              </select>
          </div>
            <div class="form-inline">
            <div class="input-group">
                <label for="date1">Fecha Desde</label>
                <input name="date1" class="form-control dp" type="text" value="<?php echo $hoy ?>" readonly>
                <span class="glyphicon glyphicon-calendar"  aria-hidden="true"></span>
            </div>
            <div class="input-group">
                <label for="date2">Fecha Hasta</label>
                <input name="date2" class="form-control dp" type="text" value="<?php echo $hoy ?>" readonly>
                <span class="glyphicon glyphicon-calendar"  aria-hidden="true"></span>
            </div>
            </div>
            <br>
          <input type="submit" class="btn btn-info" value="Buscar">
        </form>
  </div>
</div>