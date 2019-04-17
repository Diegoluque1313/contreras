<?php
return array(
	"visitante" => array(
		"allow" => array(
			"usuario",
			"equipo",
			"jugador",
			"partido"
		),
		"deny"	=> array(
			"prueba",
			"application/default",
			"application",
			"home",
			"equipo",
			"jugador",
			"partido"
		)
	),
	"admin"		=> array(
		"allow" => array(
			"application/default",
			"application",
			"home",
			"prueba",
			"usuario",
			"equipo",
			"jugador",
			"partido"
		),
		"deny"	=> array()
	)
);