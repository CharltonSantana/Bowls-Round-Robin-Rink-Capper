<?php


class RoundRobin {

    public $teams;
    public $countOfTeams;
    public $errors;

    function __construct($teams){
        // Add ghost team if needed.
        if (count($teams) % 2 != 0) {
          $teams[] = "Bye";
        }
        $this->teams = $teams;
        $this->countOfTeams = count($teams);
    }
    function getTeams(){
        return $this->teams;
    }

    function getRound($int){
        $tempArray = $this->teams;
        for($i = 1; $i < $int; $i++){
            $cut = $tempArray[$i];
            unset($tempArray[$i]);
            $tempArray[] = $cut;
        }
        return array_values(array_filter($tempArray));
    }
    function getSchedule(){
        $schedule = [];
        for($i = 1; $i < $this->countOfTeams; $i++){
            $schedule[] = $this->getRound($i);
        }
        $rounds = [];
        $scheduleCount = count($schedule);
        for($i = 0; $i < $scheduleCount; $i++){
            $rounds[$i][] = [$schedule[$i][0], $schedule[$i][1]];
            for($t = 1; $t < $this->countOfTeams / 2; $t++){
                $home = $t + 1;
                $away = $this->countOfTeams - $t;
                $rounds[$i][] = [$schedule[$i][$home], $schedule[$i][$away]];
            }
        }
        return $rounds;
    }
    function capSchedule($schedule, $count){

        // Flatern array, (Remove all rounds, keep games)
        $tempSchedule = [];
        for($i = 0; $i < count($schedule); $i++){
            $tempSchedule = array_merge($tempSchedule, $schedule[$i]);
        }

        // Remove all bye teams from array
        for($i = 0; $i < count($tempSchedule); $i++){
            if($tempSchedule[$i][0] === "Bye" || $tempSchedule[$i][1] === "Bye"){
                echo "Unsetting: $i <br>";
                unset($tempSchedule[$i]);
                $tempSchedule = array_values(array_filter($tempSchedule));
            }
        }
        $tempSchedule = array_values(array_filter($tempSchedule));

        $gameCount = 0;
        $roundCount = 0;
        $cappedSchedule = [];

        for($i = 0; $i < count($tempSchedule); $i++){
            if($roundCount == $count){
                $roundCount = 0;
                $gameCount++;
            }
            $roundCount++;
            $cappedSchedule[$gameCount][$roundCount] = $tempSchedule[$i];
        }

        //  Check for errors, normally caused when more rinks than teams.
        for($x = 0; $x < count($cappedSchedule); $x++){

            // Reset array keys
            $cappedSchedule[$x] = array_values(array_filter($cappedSchedule[$x]));

            $currentTeams = [];
            for($y = 0; $y < count($cappedSchedule[$x]); $y++){
                for($z = 0; $z < count($cappedSchedule[$x][$y]); $z++){
                    $currentTeams[] = $cappedSchedule[$x][$y][$z];
                }
            }
            if($this->hasDuplicates($currentTeams)){
                $currRound = $x + 1;
                $this->errors .= "Duplicates on round $currRound <br>";
            }
        }

        return $cappedSchedule;
    }

    //Function to check for duplicates in an array
    function hasDuplicates( $array ) {
        return count( array_keys( array_flip( $array ) ) ) !== count( $array );
    }

    // Render schedule as a table
    function renderSchedule($schedule){
        $rinks = count($schedule[0]);
        $games = count($schedule);
        $html = '<table>';

            $html .= '<tr>';
                $html .= "<th>Round No</th>";
                for($i = 0; $i < $rinks; $i++){
                    $rinkNumber = $i + 1;
                    $html .= '<th>Rink '.$rinkNumber.'</th>';
                }
            $html .= '</tr>';

            for($g = 0; $g < $games; $g++){
                $gameNumber = $g + 1;
                $html .= '<tr>';
                    $html .= '<td>'.$gameNumber.'</td>';
                    for($i = 0; $i < $rinks; $i++){
                        //$iPlusOne = $i + 1;
                        if(isset($schedule[$g][$i][0]) && isset($schedule[$g][$i][1])){
                            $html .= '<td>';
                            $html .= $schedule[$g][$i][0];
                            $html .= ' vs ';
                            $html .= $schedule[$g][$i][1];
                            $html .= '</td>';
                        }
                    }
                $html .= '</tr>';
            }

        $html .= '</table>';
        return $html;
    }
}
