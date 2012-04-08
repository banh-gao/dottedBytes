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

// no direct access
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );


define ( "SITECONFIG_CONF_OF", "Configurazione di %s" );

define ( "SITECONFIG_CONFFILE", "File di configurazione" );
define ( "SITECONFIG_CONFFILE_TIP", "I parametri di questa sezione devono essere modificati manualmente nel file di configurazione <b>configuration.php</b> situato nella cartella principale del sito." );

define ( "SITECONFIG_PATH", "Percorsi" );
define ( "SITECONFIG_BASEPATH", "Root configurata" );
define ( "SITECONFIG_BASEPATH_DETECTED", "Root rilevata" );
define ( "SITECONFIG_BASEURL", "URL configurato" );
define ( "SITECONFIG_BASEURL_DETECTED", "URL rilevato" );

define ( "SITECONFIG_ERRORS", "Gestione errori" );
define ( "SITECONFIG_LOGDIR", "Cartella dei log" );
define ( "SITECONFIG_HIDDENERRORS", "Errori non segnalati" );
define ( "SITECONFIG_DEBUGMODE", "Modalità debug attiva" );
define ( "SITECONFIG_REWRITE", "Rewrite engine" );
define ( "SITECONFIG_REWRITE_ENABLE", "Rewrite engine avviato" );
define ( "SITECONFIG_REWRITE_SYMBOL", "Simbolo di rewriting" );
define ( "SITECONFIG_REWRITE_EXTENSION", "Estensione del rewriting" );

define ( "SITECONFIG_G_ANALYTICS", "Google Analytics" );
define ( "SITECONFIG_G_ANALYTICS_ENABLE", "Abilita Google Analytics" );
define ( "SITECONFIG_G_ANALYTICS_ACCOUNT", "Codice account" );
define ( "SITECONFIG_G_ANALYTICS_ACCOUNT_TIP", "Specificare il codice visualizzato nel profilo di Analytics (es. UA-1234678-1)" );


define ( "SITECONFIG_DB", "Database" );
define ( "SITECONFIG_DB_DSN", "Url DSN" );
define ( "SITECONFIG_DB_PREFIX", "Prefisso tabelle" );

?>