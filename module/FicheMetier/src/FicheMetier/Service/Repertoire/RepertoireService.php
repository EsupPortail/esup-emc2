<?php

namespace FicheMetier\Service\Repertoire;

use Laminas\View\Model\JsonModel;

class RepertoireService {

    public function readCSV(string $fichier_path): array
    {

        $handle = fopen($fichier_path, "r");
        $array = [];
        $all = "";
        while ($content = fgetcsv($handle, 0, ";")) {
            $all = implode("|",$content);
            $encoding = mb_detect_encoding($all, 'UTF-8, ISO-8859-1');
            $content = array_map(function (string $st) use ($encoding) {
                $st = str_replace(chr(63),'\'', $st);
                $st = mb_convert_encoding($st, 'UTF-8', $encoding);
                return $st;
            }, $content);
            $array[] = $content;
        }

        $header = $array[0];
        $data = array_splice($array,1);


//        var_dump($header);

        $jsonData = [];
        foreach ($data as $item) {
            $jsonItem = [];
            foreach ($header as $key => $value) {
                if (strstr($item[$key],PHP_EOL)) {
                    $jsonItem[$value] = explode(PHP_EOL,$item[$key]);
                } else {
                    $jsonItem[$value] = $item[$key];
                }
            }
            $jsonData[] = $jsonItem;
        }

        $json = json_encode($jsonData);
        return $jsonData;
//        $result = new JsonModel([
//            $jsonData,
//        ]);
//        return $result;
    }
}