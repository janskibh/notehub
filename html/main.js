function colormode(mode) {
	const profiles = {
		0:["#0D1117", "#0D1117", "#161B22", "#171D24", "#ECF6FF", "#E1EAF3", "#BEC6CD", "#BEC6CD", "dark"],
		1:["#EFF3F4", "#F7F9F9", "#FFFFFF", "#EFF1F1", "#0F1419", "#454A4F", "#0F1419", "#ACB3B3", "light"],
		2:["#FF0000", "#FF8800", "#FFFF00", "#88FF00", "#00FF00", "#00FFFF", "#0000FF", "#8800FF", "dark"]
	}
	var r = document.querySelector('body');
	r.style.setProperty('--nav-bg', profiles[mode][0]);
	r.style.setProperty('--table-bg', profiles[mode][1]);
	r.style.setProperty('--background', profiles[mode][2]);
	r.style.setProperty('--link-hover-bg', profiles[mode][3]);
	r.style.setProperty('--text-color', profiles[mode][4]);
	r.style.setProperty('--link-color', profiles[mode][5]);
	r.style.setProperty('--title-color', profiles[mode][6]);
	r.style.setProperty('--table-corder', profiles[mode][7]);
	r.style.setProperty('--graphtheme', profiles[mode][8]);
}

function ressourceChart(ue, name) {

  const moyennes = [];
  const labels = [];
  const colors = [];
  const palette = ["#ca1414", "#ca1414", "#ca1414", "#ca1414", "#ea1818", "#ea1818", "#ea1818", "#ea1818", "#eb6b17", "#ebb117", "#ebe117", "#e5eb17", "#d8eb17", "#cbeb17","#bfeb17", "#9feb17", "#6ceb17", "#2dde15", "#28c513", "#13be7f", "#7013bf"];

  // Récupération des moyennes et des labels pour chaque ressource
  ue = data.relevé.ues[ue]
  for (const ressource in ue.ressources) {
    var moyenne = ue.ressources[ressource].moyenne
    if (moyenne == "~") {
      moyenne = "0";
    }
    moyennes.push(moyenne);
    labels.push([`${data.relevé.ressources[ressource].titre} (${ue.ressources[ressource].coef})`]);
    colors.push(palette[Math.round(parseInt(moyenne))]);
  }

  // Options pour le graphe
  const options = {
    series: [{
      name: "Moyenne",
      data: moyennes
    },],
    chart: {
      type: 'bar',
      height: 400,
      width: 800,
      background: getComputedStyle(document.body).getPropertyValue('--background'),
      foreColor: getComputedStyle(document.body).getPropertyValue('--text-color')
    },
    plotOptions: {
      bar: {
        horizontal: false,
        distributed: true,
        borderRadius: 2,
      }
    },
    legend: {
      show: false
    },
    colors: colors,
    annotations: {
      yaxis: [{
        y: 0,
        y2: 8,
        borderColor: '#b62828',
        fillColor: '#b62828',
        opacity: 0.2,
      },{
      y: 8,
      y2: 10,
        borderColor: '#deb62f',
        fillColor: '#deb62f',
        opacity: 0.2,
      }]
    },
    xaxis: {
      categories: labels
    },
    yaxis: {
      max: 20
    },
    title: {
      text: name,
      align: 'center',
      margin: 10,
      offsetX: 0,
      offsetY: 0,
      floating: false,
      style: {
        fontSize:  '20px',
        fontWeight:  'bold',
        fontFamily:  undefined,
        color:  getComputedStyle(document.body).getPropertyValue('--title-color')
      },
    },
    theme: {
	mode: 'dark'
    }
  };
  options.theme.mode = getComputedStyle(document.body).getPropertyValue('--graphtheme');
  console.log(getComputedStyle(document.body).getPropertyValue('--graphtheme'));
  return options;
}

function uesChart(data, name) {

  const moyennes = [];
  const labels = [];
  const colors = [];
  const palette = ["#ca1414", "#ca1414", "#ca1414", "#ca1414", "#ea1818", "#ea1818", "#ea1818", "#ea1818", "#eb6b17", "#ebb117", "#ebe117", "#e5eb17", "#d8eb17", "#cbeb17","#bfeb17", "#9feb17", "#6ceb17", "#2dde15", "#28c513", "#13be7f", "#7013bf"];


  // Récupération des moyennes et des labels pour chaque ressource
  for (const ue in data.relevé.ues) {
    moyenne = data.relevé.ues[ue].moyenne.value
    if (moyenne == "~") {
      moyenne = "0"
    }
    moyennes.push(data.relevé.ues[ue].moyenne.value);
    labels.push([`${ue}`]);
    colors.push(palette[parseInt(moyenne, 10)])
  }

  // Options pour le graphe
  const options = {
    series: [{
      name: "Moyenne",
      data: moyennes
    }],
    chart: {
      type: "bar",
      height: 400,
      width: 800,
      background: getComputedStyle(document.body).getPropertyValue('--background'),
      foreColor: getComputedStyle(document.body).getPropertyValue('--text-color')
    },
    plotOptions: {
      bar: {
        horizontal: false,
        distributed: true,
        endingShape: 'rounded',
      }
    },
    legend: {
      show: false
    },
    colors: colors,
    annotations: {
      yaxis: [{
        y: 0,
        y2: 8,
        borderColor: '#b62828',
        fillColor: '#b62828',
        opacity: 0.2,
      },{
      y: 8,
      y2: 10,
        borderColor: '#deb62f',
        fillColor: '#deb62f',
        opacity: 0.2,
      }]
    },
    xaxis: {
      categories: labels
    },
    yaxis: {
      max: 20
    },
    title: {
      text: name,
      align: 'center',
      margin: 10,
      offsetX: 0,
      offsetY: 0,
      floating: false,
      style: {
        fontSize:  '20px',
        fontWeight:  'bold',
        fontFamily:  undefined,
        color:  getComputedStyle(document.body).getPropertyValue('--title-color')
      },
    },
    theme: {
      mode: 'dark',
      palette: 'palette1',
    }
  };
  options.theme.mode = getComputedStyle(document.body).getPropertyValue('--graphtheme');
  return options;
}

function absencesChart(data) {

  const absences = data.relevé.semestre.absences.total;
  const absences_injustifie = data.relevé.semestre.absences.injustifie;
  const abs_percent = (absences_injustifie/5)*100;
  var color;
  if (absences <= 1) {
    color = "#23A100"
  } else if (absences == 2) {
    color = "#00FF00"
  } else if (absences == 3) {
    color = "#FFFF00"
  } else if (absences == 4) {
    color = "#FF7500"
  } else if (absences >= 5) {
    color = "#FF0000"
  };
  const options = {
    chart: {
      height: 400,
      type: "radialBar",
    },
    series: [abs_percent],
    colors: [color],
    plotOptions: {
      radialBar: {
        startAngle: -135,
        endAngle: 135,
        track: {
          background: getComputedStyle(document.body).getPropertyValue('--table-bg'),
          startAngle: -135,
          endAngle: 135,
        },
        dataLabels: {
          name: {
            offsetY: 10,
            fontSize: "30px",
            show: true,
            label: "Absences"
          },
          value: {
            fontSize: "10px",
            show: false,
            color: getComputedStyle(document.body).getPropertyValue('--title-color'),
          }
        }
      }
    },
    labels: [[`${absences_injustifie}/${absences}`]],
    stroke: {
      lineCap: "round"
    }
  };

  // Options pour le graphe

  return options;
}
function rangChart(data) {

  const rang = parseInt(data.relevé.semestre.rang.value, 10);
  const total = data.relevé.semestre.rang.total;
  const rang_percent = 100-((rang/total)*100);
  const colors = ["#ca1414", "#ca1414", "#ca1414", "#ca1414", "#ea1818", "#ea1818", "#ea1818", "#ea1818", "#eb6b17", "#ebb117", "#ebe117", "#e5eb17", "#d8eb17", "#cbeb17","#bfeb17", "#9feb17", "#6ceb17", "#2dde15", "#28c513", "#13be7f", "#7013bf"];
  var color_pos = Math.round(rang_percent/5);
  var color = colors[color_pos];
  const options = {
    chart: {
      height: 400,
      type: "radialBar",
    },
    series: [rang_percent],
    colors: [color],
    plotOptions: {
      radialBar: {
        startAngle: -135,
        endAngle: 135,
        track: {
          background: getComputedStyle(document.body).getPropertyValue('--table-bg'),
          startAngle: -135,
          endAngle: 135,
        },
        dataLabels: {
          name: {
            offsetY: 10,
            fontSize: "30px",
            show: true,
            label: "Rang"
          },
          value: {
            fontSize: "10px",
            show: false,
            color: getComputedStyle(document.body).getPropertyValue('--title-color'),
          }
        }
      }
    },
    labels: [`${rang}/${total}`],
    stroke: {
      lineCap: "round"
    }
  };

  // Options pour le graphe

  return options;
}
