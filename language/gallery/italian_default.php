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

define ( "GALLERY_ROOT", "Galleria immagini" );
define ( "GALLERY_OF", 'di' );
define ( "GALLERY_TITLE", "Titolo" );
define ( "GALLERY_TITLE_MAXLENGTH", "Massimo %s caratteri" );
define ( "GALLERY_TEXT", "Testo" );
define ( "GALLERY_CREATION_TIME", "Data creazione" );
define ( "GALLERY_AUTHOR", "Autore" );
define ( "GALLERY_NOTFOUND", "Risorsa non trovata" );
define ( "GALLERY_NOTFOUND_EXPLAIN", "La risorsa richiesta non è stata trovata." );
define ( "GALLERY_EMPTY", "Album vuoto" );
define ( "GALLERY_EMPTY_EXPLAIN", "L'album %s non contiene alcuna immagine." );
define ( "GALLERY_PUBLISH", "Pubblica" );
define ( "GALLERY_UNPUBLISH", "Nascondi" );
define ( "GALLERY_DELETE", "Elimina" );
define ( "GALLERY_RSS", 'Feed RSS' );

define ( "GALLERY_FORM_LOAD", "Carica immagini nell'album (dimensione massima %s)" );
define ( "GALLERY_FORM_IMAGE", "Immagine %s" );
define ( "GALLERY_FORM_ZIP", "Archivio zip" );
define ( "GALLERY_FORM_ACCEPTED", "Sono accettati i formati %s" );
define ( "GALLERY_FORM_ERROR_ZIP", "Si è verificato un errore durante l'estrazione dell'archivio." );
define ( "GALLERY_FORM_ERROR_ZIP_IMAGE", "Le seguenti immagini non sono state caricate perché non valide: %s" );
define ( "GALLERY_FORM_ERROR_PERMISSION", "Impossibile scrivere nella cartella di destinazione, controllare i permessi." );

define ( "GALLERY_ALBUM", 'album' );
define ( "GALLERY_ALBUM_MOVE", 'Sposta nell\'album' );
define ( "GALLERY_ALBUM_REGENERATE", "Rigenera" );
define ( "GALLERY_ALBUM_EXPORT", "Esporta" );
define ( "GALLERY_ALBUM_NEW", "Nuovo album" );
define ( "GALLERY_ALBUM_EDIT", "Modifica album %s" );
define ( "GALLERY_ALBUM_DELETE", "Elimina album %s" );
define ( "GALLERY_ALBUM_DELETE_ALERT", "Attenzione l'album %s verrà cancellato definitivamente. Continuare?" );

define ( "GALLERY_ALBUM_REGENERATED", "%s anteprime rigenerate" );
define ( "GALLERY_ALBUM_SAVED", "Album %s salvato" );
define ( "GALLERY_ALBUM_UPDATED", "Album %s modificato" );
define ( "GALLERY_ALBUM_PUBLISHED", "Album %s pubblicato" );
define ( "GALLERY_ALBUM_UNPUBLISHED", "Album %s nascosto" );
define ( "GALLERY_ALBUM_DELETED", "Album %s eliminato" );

define ( "GALLERY_IMAGE", 'Immagine' );
define ( "GALLERY_IMAGE_EDIT", "Modifica immagine %s" );
define ( "GALLERY_IMAGE_DELETE", "Elimina immagine %s" );
define ( "GALLERY_IMAGE_DELETE_ALERT", "Attenzione l'immagine %s verrà cancellata definitivamente. Continuare?" );

define ( "GALLERY_IMAGE_UPDATED", "Immagine %s modificata" );
define ( "GALLERY_IMAGE_PUBLISHED", "Immagine %s pubblicata" );
define ( "GALLERY_IMAGE_UNPUBLISHED", "Immagine %s nascosta" );
define ( "GALLERY_IMAGE_DELETED", "Immagine %s eliminata" );
?>