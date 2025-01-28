<?php
    $jsonData ='{
    "id" : 19
                }';

    $tenista ='[[2005, [\"Roland Garros\"]],[2006, [\"Roland Garros\"]],[2007, [\"Roland Garros\"]],[2008, [\"Roland Garros\",\"Wimbledon\"]],[2009, [\"Australian Open\"]],[2010, [\"Roland Garros\",\"Wimbledon\",\"US Open\"]],[2011, [\"Roland Garros\"]],[2012, [\"Roland Garros\"]],[2013, [\"Roland Garros\",\"US Open\"]],[2014, [\"Roland Garros\"]],[2017, [\"Roland Garros\",\"US Open\"]],[2018, [\"Roland Garros\"]],[2019, [\"Roland Garros\",\"US Open\"]],[2020, [\"Roland Garros\"]],[2022, [\"Australian Open\",\"Roland Garros\"]],[2025, [\"Roland Garros\"]],[2026, [\"Roland Garros\"]],[2027, [\"Roland Garros\"]],[2028, [\"Roland Garros\"]],[2029, [\"Roland Garros\"]],[2030, [\"Roland Garros\"]]]';

    $tenistajson = '{\"2005\":[\"Roland Garros\"], \"2006\":[\"Roland Garros\"], \"2007\":[\"Roland Garros\"], \"2008\":[\"Roland Garros\",\"Wimbledon\"], \"2009\":[\"Australian Open\"], \"2010\":[\"Roland Garros\",\"Wimbledon\",\"US Open\"], \"2011\":[\"Roland Garros\"], \"2012\":[\"Roland Garros\"], \"2013\":[\"Roland Garros\",\"US Open\"], \"2014\":[\"Roland Garros\"], \"2017\":[\"Roland Garros\",\"US Open\"], \"2018\":[\"Roland Garros\"], \"2019\":[\"Roland Garros\",\"US Open\"], \"2020\":[\"Roland Garros\"], \"2022\":[\"Australian Open\",\"Roland Garros\"], \"2025\":[\"Roland Garros\"], \"2026\":[\"Roland Garros\"], \"2027\":[\"Roland Garros\"], \"2028\":[\"Roland Garros\"], \"2029\":[\"Roland Garros\"], \"2030\":[\"Roland Garros\"]}';
    $tejson ='{
        "id": 1,
        "nombre": "Rafael",
        "apellidos": "Nadal",
        "altura": 185,
        "año de nacimiento": 1986,
        "titulos": "{\"2005\":[\"Roland Garros\"], \"2006\":[\"Roland Garros\"], \"2007\":[\"Roland Garros\"], \"2008\":[\"Roland Garros\",\"Wimbledon\"], \"2009\":[\"Australian Open\"], \"2010\":[\"Roland Garros\",\"Wimbledon\",\"US Open\"], \"2011\":[\"Roland Garros\"], \"2012\":[\"Roland Garros\"], \"2013\":[\"Roland Garros\",\"US Open\"], \"2014\":[\"Roland Garros\"], \"2017\":[\"Roland Garros\",\"US Open\"], \"2018\":[\"Roland Garros\"], \"2019\":[\"Roland Garros\",\"US Open\"], \"2020\":[\"Roland Garros\"], \"2022\":[\"Australian Open\",\"Roland Garros\"], \"2025\":[\"Roland Garros\"], \"2026\":[\"Roland Garros\"], \"2027\":[\"Roland Garros\"], \"2028\":[\"Roland Garros\"], \"2029\":[\"Roland Garros\"], \"2030\":[\"Roland Garros\"]}"
    }';
    echo var_dump(json_decode($jsonData));
    echo "</br>";
    echo var_dump(json_decode($tejson));
?>