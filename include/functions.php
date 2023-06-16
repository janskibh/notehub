<?php
    function authcas($username, $password) {

        $s = curl_init();

        $url1 = "https://cas2.uvsq.fr/cas/login?service=https://bulletins.iut-velizy.uvsq.fr/services/doAuth.php";
	    $url2 = "https://bulletins.iut-velizy.uvsq.fr/services/data.php?q=semestresEtudiant";
        $url3 = "https://bulletins.iut-velizy.uvsq.fr/logout.php";
        curl_setopt($s, CURLOPT_URL, $url1);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($s, CURLOPT_COOKIEJAR, $username . "-cookies.txt");
        $req1 = curl_exec($s);

        $req1_html = new DOMDocument();
        @$req1_html->loadHTML($req1);
        $inputs = $req1_html->getElementsByTagName("input");
        foreach ($inputs as $input) {
            if ($input->getAttribute("name") == "execution") {
                $execution = $input->getAttribute("value");
                break;
            }
        }

        curl_setopt($s, CURLOPT_URL, $url1);
        curl_setopt($s, CURLOPT_POST, true);
        curl_setopt($s, CURLOPT_COOKIEFILE, $username . "-cookies.txt");
        curl_setopt($s, CURLOPT_POSTFIELDS, array (
            "username" => $username,
            "password" => $password,
            "execution" => $execution,
            "_eventId" => "submit",
            "geolocalisation" => ""
        ));

        $auth = curl_exec($s);

        if (curl_getinfo($s, CURLINFO_HTTP_CODE) != 200) {
            if (curl_getinfo($s, CURLINFO_HTTP_CODE) == 302) {
                return 3;
            } else if (curl_getinfo($s, CURLINFO_HTTP_CODE) == 403){
                return 2;
            } else {
                return 1;
            }
        }

        curl_setopt($s, CURLOPT_URL, $url2);
        $semestres = curl_exec($s);
        if (curl_getinfo($s, CURLINFO_HTTP_CODE) != 200) {
            if (curl_getinfo($s, CURLINFO_HTTP_CODE) == 500) {
            return 4;
            } else {
            return 1;
            }
        }
        $semestres_data = json_decode($semestres, true);

        $semestres_json = array();

        foreach ($semestres_data as $sem) {
            $id_semestre = $sem['formsemestre_id'];
            $url = "https://bulletins.iut-velizy.uvsq.fr/services/data.php?q=relev%C3%A9Etudiant&semestre=" . $id_semestre;
            curl_setopt($s, CURLOPT_URL, $url);
            $notes_request = curl_exec($s);
            array_push($semestres_json, json_decode($notes_request));
        }
        curl_setopt($s, CURLOPT_URL, $url3);
        curl_exec($s);
        curl_close($s);

        return $semestres_json;
    }
    function footer() {
        echo '<h2>A propos</h2>';
        $modes = array("clair", "sombre", "sombre");
        $modes_codes = array("1", "0", "0");
        echo '<a href="colormode.php?mode=' . $modes_codes[$_SESSION['colormode']] . '&source=' . $_SERVER['REQUEST_URI'] . '">Mode ' . $modes[$_SESSION['colormode']] . '</a><br><br>';
        echo '<a href="data_usage.php">Utilisation des données</a><br><br>';
        echo $_SESSION['userdata']['admin'] == 1 ? '<a href="admin.php">Admin</a>' : '';
        echo "<hr>&copy; 2023 Jan BELLON | Club Réseaux | IUT de Vélizy";
    }
    function nav($pages) {
        echo '<a href="index.php"><img src="./img/notehub' . $_SESSION['colormode'] . '.png" id="notehub-icon"/></a>';
        foreach($pages as $key => $value) {
            echo '<a href="' . $value . '" class="navlink">' . $key . '</a>';
        };
        echo '<a href="logout.php" class="navlink" style="color: #FE2424">Deconnexion</a>';
        // lien vers profil
        echo '<a href="profil.php"><img src="';
        echo $_SESSION['userdata']['pp_url'] != NULL ? $_SESSION['userdata']['pp_url'] : 'img/default_pp.jpg';
        echo '" height="50px" style="border-radius: 25px; position:absolute; right: 20px; top: 5px"/></a>';
    }
    function addlog($log, $log_dir) {
        $log_file = fopen($log_dir . "/notehub.log", "a") or die("Log Error");
		fwrite($log_file, $log);
		fclose($log_file);
    }
?>
