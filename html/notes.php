<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: login.php?page=" . $_SERVER['REQUEST_URI']);
    exit();
}

include '../include/config.php';
include '../include/connect.php';
include '../include/functions.php';

$userdata = $_SESSION['userdata'];
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title;?></title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
  </head>
  <style>
    #sem_links {
	position: fixed;
	top: 30px;
	right: 20px;
	display: block;
	z-index: 1200;
    }
    #sem_links a {
	position: relative;
	margin: 10px;
	text-decoration: none;
	padding: 10px;
	background-color: var(--table-bg);
	border-radius: 10px;
    }
    #sem_links a:hover {
	background-color: var(--link-hover-bg);
    }
    .rname {
        border-bottom: 0;
        color: var(--title-color);
    }
    .apexcharts-xaxis-label {
            fill: var(--text-color);
    }

    .apexcharts-canvas {
            margin: 0 auto 0 auto;
    }

    .chart {
            margin: 0 auto 0 auto;
    }

    #circhart {
	display: flex;
	margin: 100px;
    }

    #chart1 {
      //margin-top: 500px;
    }
    /*
    #abschart {
            position: absolute;
            right: 100px;
            top: 150px;
        z-index: 500;
    }
    #retchart {
        position: absolute;
        left: 100px;
        top: 150px;
        z-index: 500;
    }*/
    @media screen and (min-width: 1800px) {
	    #circhart {
		width: 800px;
		margin-left: 800px;
	    }
            #charts {
            display:grid;
            grid-gap: 0;
            margin: 20px;
            }

            #chart1 {
            grid-column: 1;
            grid-row: 1;
      //margin-top: 400px;
            }

            #chart2 {
            grid-column: 2;
            grid-row: 1;
      //margin-top: 400px;
            }

            #chart3 {
            grid-column: 1;
            grid-row: 2;
            }

            #chart4 {
            grid-column: 2;
            grid-row: 2;
            }
        /*#abschart {
      right: 80px;
      top: 100px;
        }
        #retchart {
      right: -40px;
      top: 100px;
        }*/
	#lastgrades {
		position: absolute;
		top: 25px;
		left: 20px;
		font-size: 0.8em;
		width: 500px;
	}
	#lastgrades td, #lastgrades th{
		width: 50px;
	}
	.notecol {
		width: 50px;
	}
    }
  </style>
  <body>
    <nav>
	  <?php nav($pages)?>
    </nav>
    <h1>Notes</h1>
    <?php 
    if ($_SESSION['userdata']['verified'] == 0) {
      die("<p>Identifiants CAS non renseignés dans la page <a href='profil.php'>profil</a></p></body></html>");
    } else if (!isset($_SESSION['notedata'])) {
      $_SESSION['notedata'] = authcas($_SESSION['usercas'], $_SESSION['passcas']);
    }
    if ($_SESSION['notedata'] == 2) {
      die("<p>Erreur d'authentification CAS, vérifiez vos identifiants dans la page <a href='profil.php'>profil</a></p></body></html>");
    }
    $data = $_SESSION['notedata'];
    ?>
    <div id="sem_links">
      <?php
      for ($i = 0; $i < sizeof($data); $i++) {
        echo '<a href="notes.php?sem_id=' . $i .'">' . $data[$i]->relevé->semestre->annee_universitaire . ' Semestre ' . $data[$i]->relevé->semestre->numero . '</a><br><br>';
      }
      ?>
    </div>
    <div id="circhart">
    	<div id="abschart" class="chart"></div>
    	<div id="retchart" class="chart"></div>
    </div>
    <div id="charts"></div>
    <hr/>
    <?php
      if (!isset($_GET['sem_id'])) {
        $sem = 0;
      } else {
	      $sem = intval($_GET['sem_id']);
      }
      if ($sem >= sizeof($data)) {
        die("Numéro de semestre invalide");
      }
      $sem_data = $data[$sem];
      $notes = array();
      $michel = array("ressources", "saes");
      $allcolors = array(
		    0 => array("#FF4949", "#FFB14A", "#D8FF4A", "#4AFF4A", "#4AFFBA"),
		    1 => array("#C90000", "#D06F00", "#CAB000", "#06B800", "#00BF8F")
      );
      $colors = $allcolors[$_SESSION['colormode']];
      echo "<table>";
      if ($sem_data->relevé->semestre->notes->value == "~") {
           $noteval = '<td style="color: #888888">' . $sem_data->relevé->semestre->notes->value . '</td>';
      } else if (floatval($sem_data->relevé->semestre->notes->value) == floatval($sem_data->relevé->semestre->notes->max)){
           $noteval = '<td style="color: ' . $colors[4] . '">' . $sem_data->relevé->semestre->notes->value . '</td>';
      } else if (floatval($sem_data->relevé->semestre->notes->value) > floatval($sem_data->relevé->semestre->notes->moy)){
           $noteval = '<td style="color: ' . $colors[3] . '">' . $sem_data->relevé->semestre->notes->value . '</td>';
      } else if (floatval($sem_data->relevé->semestre->notes->value) == floatval($sem_data->relevé->semestre->notes->moy)){
           $noteval = '<td style="color: ' . $colors[2] . '">' . $sem_data->relevé->semestre->notes->value . '</td>';
      } else if (floatval($sem_data->relevé->semestre->notes->value) == floatval($sem_data->relevé->semestre->notes->min)){
          $noteval = '<td style="color: ' . $colors[0] . '">' . $sem_data->relevé->semestre->notes->value . '</td>';
      } else if (floatval($sem_data->relevé->semestre->notes->value) < floatval($sem_data->relevé->semestre->notes->moy)){
           $noteval = '<td style="color: ' . $colors[1] . '">' . $sem_data->relevé->semestre->notes->value . '</td>';
      } else {
           $noteval = '<td>' . $sem_data->relevé->semestre->notes->value . '</td>';
      }

      echo "<td>Moyenne Générale</td>" . $noteval . "<td><span style='color: " . $colors[0] . "'>" . $sem_data->relevé->semestre->notes->min . "</span> | <span style='color: " . $colors[2] . "'>" . $sem_data->relevé->semestre->notes->moy . "</span> | <span style='color: " . $colors[4] . "'>" . $sem_data->relevé->semestre->notes->max . "</span></td></tr>";

      echo "</table>";
      foreach ($michel as $m) {
      foreach ($sem_data->relevé->$m as $ressource_key => $ressource) {
        echo "<table>";
        echo "<tr><th class='rname' colspan='3'>" . $ressource_key . " - " . $ressource->titre . "</th></tr>";
        echo "<tr><th>Description</th><th>Coef</th><th>Note</th><th>Min Moy Max</th></tr>";
        foreach($ressource->evaluations as $eval) {
	  	if (!is_null($eval->date)){
			$eval->ressource = $ressource_key;
			$notes[] = $eval;
      }
      if ($eval->note->value == "~") {
        $noteval = '<td style="color: #888888">' . $eval->note->value . '</td>';
      } else if (floatval($eval->note->value) == floatval($eval->note->max)){
        $noteval = '<td style="color: ' . $colors[4] . '">' . $eval->note->value . '</td>';
      } else if (floatval($eval->note->value) > floatval($eval->note->moy)){
        $noteval = '<td style="color: ' . $colors[3] . '">' . $eval->note->value . '</td>';
      } else if (floatval($eval->note->value) == floatval($eval->note->moy)){
        $noteval = '<td style="color: ' . $colors[2] . '">' . $eval->note->value . '</td>';
      } else if (floatval($eval->note->value) == floatval($eval->note->min)){
        $noteval = '<td style="color: ' . $colors[0] . '">' . $eval->note->value . '</td>';
      } else if (floatval($eval->note->value) < floatval($eval->note->moy)){
        $noteval = '<td style="color: ' . $colors[1] . '">' . $eval->note->value . '</td>';
      } else {
        $noteval = '<td>' . $eval->note->value . '</td>';
      }
          echo "<tr><td>" . $eval->description . "</td><td>" . $eval->coef . "</td>" . $noteval . "<td><span style='color: " . $colors[0] . "'>" . $eval->note->min . "</span> | <span style='color: " . $colors[2] . "'>" . $eval->note->moy . "</span> | <span style='color: " . $colors[4] . "'>" . $eval->note->max . "</span></td></tr>";
        }
          echo "</table>";
        }
      }
      echo "<hr>";
      echo "<table id='lastgrades'>";
      echo "<tr><th class='rname' colspan='3'>Dernières notes</th></tr>";
      echo "<tr><th>Eval</th><th>Date</th><th class='notecol'>Note</th></tr>";
      function compareByDate($a, $b) {
        return strtotime($a->date) - strtotime($b->date);
      }
      usort($notes, 'compareByDate');
      $notes = array_reverse($notes);
      for ($i = 0; $i < sizeof($notes) && $i < 3; $i++) {
        $notedate = strtotime($notes[$i]->date);
        echo "<tr><td>" . $notes[$i]->ressource . " - " . $notes[$i]->description . "</td><td>" . date("d/m/Y", $notedate). "</td><td class='notecol'>" . $notes[$i]->note->value . "</td></tr>";
      }
      echo "</table>";
      ?>
		<footer><?php footer()?></footer>
  </body>
  <script src="main.js"></script>
  <script>
  colormode(<?php echo $_SESSION['colormode'];?>);
  const data = <?php echo json_encode($data[$_GET['sem_id']]);?>;
  console.log(data);
  // Récupération des données pour chaque UE

  // Création des graphes
  var i = 1;
  const container = document.getElementById("charts");
  const template = document.createElement("div");
  const charts = []
  for (const ue in data.relevé.ues) {
    const graph = template.cloneNode(true);
    graph.setAttribute("id", `chart${i}`);
    container.appendChild(graph);

    const chart = new ApexCharts(document.querySelector(`#chart${i}`), ressourceChart(ue, `UE${i}`));
    chart.render();

    i += 1;
  }
  const graph = template.cloneNode(true);
  graph.setAttribute("id", `chart${i}`);
  container.appendChild(graph);

  const ueschart = new ApexCharts(document.querySelector(`#chart${i}`), uesChart(data, "Moyennes UES"));
  ueschart.render();
  const abschart = new ApexCharts(document.querySelector("#abschart"), absencesChart(data));
  const rangchart = new ApexCharts(document.querySelector("#retchart"), rangChart(data));
  abschart.render();
  rangchart.render();
  </script>
</html>
