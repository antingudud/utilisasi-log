<?php
Class Chart extends GraphModel {
    public function getChart($idDevice, $year, $desiredMonth, $range = ""){
        return $this->drawBarChart($idDevice, $year, $desiredMonth, $range);
    }
}