<?php

namespace Indicateur\View\Helper;

use Indicateur\Entity\Db\Indicateur;
use Zend\Form\View\Helper\AbstractHelper;

class IndicateurStructureViewHelper extends AbstractHelper
{
    /**
     * @param Indicateur $indicateur
     * @param array $data
     * @return string
     */
    public function render($indicateur, $data) {

        $rubriques = [
//            'id'                    => 'id',
//            'Code'                  => 'sigle',
            'Libelle court'         => 'libelle_court',
            'Libelle long'          => 'libelle_long',
            'Type'                  => 'type',
            //'Description'           => 'description',
            //'Historisé'             => 'histo',
        ];

        $html  = '';
        $html .= '<table id="datatable" class="table table-extra-condensed">';
        $html .= '<thead>';
        $html .= '<tr>';
        foreach ($rubriques as $clef => $valeur) {
            $html .= '<th> '.$clef.' </th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        foreach($data as $entry) {
            $html .= '<tr>';
            foreach ($rubriques as $clef => $valeur) {
                $html .= '<td>'.$entry[$valeur].'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }
}