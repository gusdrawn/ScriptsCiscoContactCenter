<?php
class Awro extends CI_Model {

	function __construct()
    {
        parent::__construct();		
    }
	function consultar($datos) {
		$sql = NULL;
		if($datos['ani']) {
			$sql = "ANI = '".$datos['ani']."' and ";
		}
		if($datos['dnis']) {
			$sql .= "DNIS = '".$datos['dnis']."' and ";
		}
		if($datos['acd']) {
			$sql .= "Agent.Description = '".$datos['acd']."' and ";
		}
		/*echo "<br>".$sql."<br>";
		echo "
			select * 
			FROM [baucc_awdb].[dbo].[Termination_Call_Detail] 
			INNER JOIN [baucc_awdb].[dbo].[Agent] 
			ON Termination_Call_Detail.AgentPeripheralNumber =  Agent.PeripheralNumber 
			where CallTypeID != '-1' AND ".$sql."
			DateTime >= '".$datos['date1']." 00:00:00' AND 
			DateTime < '".$datos['date2']." 23:59:59'<br>";*/
		$query = $this->db->query("
			select 
				Termination_Call_Detail.DateTime, ANI, DNIS, DigitsDialed, Agent.Description, 
				Duration, FirstName, LastName, AgentPeripheralNumber, AnsweredWithinServiceLevel
			FROM [baucc_awdb].[dbo].[Termination_Call_Detail] 
				INNER JOIN [baucc_awdb].[dbo].[Agent] 
					ON Termination_Call_Detail.AgentPeripheralNumber =  Agent.PeripheralNumber 
				INNER JOIN [baucc_awdb].[dbo].[Person]
					ON Agent.PersonID = Person.PersonID
			where CallTypeID != '-1' AND ".$sql."
			Termination_Call_Detail.DateTime >= '".$datos['date1']." 00:00:00' AND 
			Termination_Call_Detail.DateTime < '".$datos['date2']." 23:59:59'");
		return $query->result_array();
	}

}
/*
** Gustavo Guillen Version 0.3 Experimental **
** Nueva Propuesta **
select Termination_Call_Detail.DateTime, ANI, DNIS, Agent.Description, Duration, FirstName, LastName, AgentPeripheralNumber, AnsweredWithinServiceLevel
FROM [baucc_awdb].[dbo].[Termination_Call_Detail] 
INNER JOIN [baucc_awdb].[dbo].[Agent] 
ON Termination_Call_Detail.AgentPeripheralNumber =  Agent.PeripheralNumber 
INNER JOIN [baucc_awdb].[dbo].[Person]
ON Agent.PersonID = Person.PersonID
where Agent.Description = 'ACD005' AND
Termination_Call_Detail.DateTime >= '2015-04-08 00:00:00' AND 
Termination_Call_Detail.DateTime < '2015-04-08 23:59:59' AND CallTypeID != '-1'



** BACKUP VERSION 0.2 **

<?php
class Awro extends CI_Model {

	function __construct()
    {
        parent::__construct();		
    }
	function consultar($datos) {
		$sql = NULL;
		if($datos['ani']) {
			$sql = "ANI = '".$datos['ani']."' and ";
		}
		if($datos['dnis']) {
			$sql .= "DNIS = '".$datos['dnis']."' and ";
		}
		if($datos['acd']) {
			$sql .= "Description = '".$datos['acd']."' and ";
		}
		echo "<br>".$sql."<br>";
		echo "
			select * 
			FROM [baucc_awdb].[dbo].[Termination_Call_Detail] 
			INNER JOIN [baucc_awdb].[dbo].[Agent] 
			ON Termination_Call_Detail.AgentPeripheralNumber =  Agent.PeripheralNumber 
			where CallTypeID != '-1' AND ".$sql."
			DateTime >= '".$datos['date1']." 00:00:00' AND 
			DateTime < '".$datos['date2']." 23:59:59'<br>";
		$query = $this->db->query("
			select * 
			FROM [baucc_awdb].[dbo].[Termination_Call_Detail] 
			INNER JOIN [baucc_awdb].[dbo].[Agent] 
			ON Termination_Call_Detail.AgentPeripheralNumber =  Agent.PeripheralNumber 
			where CallTypeID != '-1' AND ".$sql."
			DateTime >= '".$datos['date1']." 00:00:00' AND 
			DateTime < '".$datos['date2']." 23:59:59'");
		return $query->result_array();
	}

}*/