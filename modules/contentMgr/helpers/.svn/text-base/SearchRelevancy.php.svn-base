<?php
/**
 * @package		DottedBytes
 * @copyright	Copyright (C) 2009 -2010 DottedBytes. All rights reserved.
 * @license		GNU/GPL, see COPYING file
 * @author		Daniel Zozin
 *
 * This file is part of DottedBytes.
 * DottedBytes is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * DottedBytes is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DottedBytes.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace dottedBytes\modules\contentMgr\helpers;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class SearchRelevancy {
	private static $fulltext = false;
	
	private static function PulisciQuery($queryvar) {
		// array parole di cui non tener conto nelle ricerche
		$arrayBadWord = Array ("lo", "l", "il", "la", "i", "gli", "le", "uno", "un", "una", "un", "su", "sul", "sulla", "sullo", "sull", "in", "nel", "nello", "nella", "nell", "con", "di", "da", "dei", "d", "della", "dello", "del", "dell", "che", "a", "dal", "è", "e", "per", "non", "si", "al", "ai", "allo", "all", "al", "o" );
		$queryclean = strtolower ( $queryvar );
		for($a = 0; $a < count ( $arrayBadWord ); $a ++) {
			// sostituisco bad words con espressioni regolari \b ->solo se parole singole, non facenti parti di altre
			$queryclean = preg_replace ( '/\b' . $arrayBadWord [$a] . '\b/', "", $queryclean );
		}
		$queryclean = preg_replace ( '/\W/', " ", $queryclean );
		return $queryclean;
	}
	
	// CreaQueryRicerca: Creo la query di ricerca.
	// peso titolo se non specificato=5, peso testo se non specificato=3
	// searchlevel -> 1 o 0. default 1. Se 0 trova parole non complete. Es. cerchi osso?ok anche ossobuco. Se 1 non succede.
	private static function CreaQueryRicerca($arrayToFind, $pesotitolo = 5, $pesotesto = 3, $searchlevel = 1) {
		// trasformo la stringa in un array di parole da cercare
		// numero elementi da cercare
		$elementiToFind = count ( $arrayToFind );
		// punteggio massimo raggiungibile
		$maxPoint = $elementiToFind * $pesotitolo + $elementiToFind * $pesotesto;
		if ($elementiToFind == 0) {
			return "";
		} else {
			$query = "select ROUND((";
			$sqlwhere = "";
			// ciclo per ogni parola trovata ($Valore)
			foreach ( $arrayToFind as $Indice => $Valore ) {
				// se $Valore è presente in titolo instr(titolo, '$Valore') restituirà 1 altrimenti 0
				// moltiplico il valore restituito (1 o 0) per il peso della parola (5 per il titolo, 3 per testo)
				if ($searchlevel == 1) {
					// regexp: uso espressioni regolari. [[:<:]] equivale a \b per separare parole
					$query .= "((title REGEXP '[[:<:]]" . $Valore . "[[:>:]]')>0)*$pesotitolo+";
					$query .= "((text REGEXP '[[:<:]]" . $Valore . "[[:>:]]')>0)*$pesotesto+";
					$sqlwhere .= "title REGEXP '[[:<:]]" . $Valore . "[[:>:]]' OR text REGEXP '[[:<:]]" . $Valore . "[[:>:]]' OR ";
				} else {
					$query .= "(instr(title, '$Valore')>0)*$pesotitolo+";
					$query .= "(instr(text, '$Valore')>0)*$pesotesto+";
					$sqlwhere .= "title LIKE '%$Valore%' OR text LIKE '%$Valore%' OR ";
				}
			}
			$sqlwhere = substr ( $sqlwhere, 0, strlen ( $sqlwhere ) - 4 );
			// calcolo la percentuale di rilevanza  --> rilevanza*100/$maxPoint
			$query .= "0)*100/$maxPoint,2) as rilevanza, id from #__contents WHERE $sqlwhere order by rilevanza DESC";
			return $query;
		}
	}
	
	public static function getQuery($keywords) {
		return self::CreaQueryRicerca ( $keywords, 5, 3, 2 );
	}
}

?>