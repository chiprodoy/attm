<?php
if (! function_exists('isTimeOnRange')) {
    function isTimeOnRange(\DateTime $currentTime,\DateTime $startTime,\DateTime $endTime){
        if($currentTime > $startTime && $currentTime < $endTime) return true;
        return false;
    }
}

if (! function_exists('timeDiference')) {
    function timeDiference(\DateTime $startTime,\DateTime $endTime){
            $selisih = $startTime->getTimestamp() - $endTime->getTimestamp();
            $result = floor($selisih / 60);
        return $result;
    }
}
