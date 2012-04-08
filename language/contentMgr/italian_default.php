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
	
/*
 ADMIN PANEL
 */

define ( "CONTENT_TITLE", "Titolo" );
define ( "CONTENT_SUBTITLE", "Sottotitolo" );
define ( "CONTENT_TEXT", "Testo" );
define ( "CONTENT_TAGS", "Parole chiave" );
define ( "CONTENT_TAGS_NEW_TIP", "Inserire le parole chiave separate da una virgola" );
define ( "CONTENT_TAGS_TIP", "Selezionare una o più parole chiave da associare" );
define ( "CONTENT_RSS", 'Feed RSS' );

define ( "CONTENT_PUBLISHED", "contenuto pubblicato" );
define ( "CONTENT_UNPUBLISHED", "contenuto nascosto" );
define ( "CONTENT_ISPUBLISHED", "Pubblicato" );
define ( "CONTENT_ISNEWS", "Mostra nelle news" );
define ( "CONTENT_USECOMMENTS", "Permetti di commentare" );
define ( "CONTENT_ROOT", "Categoria principale" );
define ( "CONTENT_CREATION_TIME", "Data creazione" );
define ( "CONTENT_AUTHOR", "Autore" );
define ( "CONTENT_ORD", "Ordina" );
define ( "CONTENT_TYPE", "Tipo di contenuto" );

define ( "CONTENT_CHECKNAME", "Inserire un nome" );
define ( "CONTENT_CHECKTITLE", "Inserire un titolo" );

define ( "CONTENT_VIEW", "Mostra contenuti" );
define ( "CONTENT_NEW", "Nuovo contenuto" );
define ( "CONTENT_EDIT", "Modifica" );
define ( "CONTENT_PUBLISH", "Pubblica" );
define ( "CONTENT_UNPUBLISH", "Nascondi" );
define ( "CONTENT_DELETE", "Elimina" );
define ( "CONTENT_DELETED", "Contenuto eliminato" );
define ( "CONTENT_DELETE_ALERT", "Attenzione il contenuto selezionato verrà cancellato. Continuare?" );
define ( "CONTENT_DELETE_MASS_ALERT", "Attenzione i contenuti selezionati verranno cancellati. Continuare?" );

//ADMIN MODULE
define ( "CONTENT_ARTICLES_NONE", "Nessun articolo presente" );

//CONFIGURATION
define ( "CONTENT_CONFIG", "Configurazione" );
define ( "CONTENT_CONFIG_TITLE", "Configurazione contenuti" );
define ( "CONTENT_CONFIG_COLS", "Numero di colonne nelle news" );
define ( "CONTENT_CONFIG_PAGEELEMS", "Numero di contenuti per pagina" );
define ( "CONTENT_CONFIG_PREVIEWLENGTH", "Caratteri anteprima contenuti" );
define ( "CONTENT_CONFIG_SEARCHPREVIEW", "Caratteri anteprima ricerca" );
define ( "CONTENT_CONFIG_GUESTPOST", "Permetti agli ospiti di commentare gli articoli" );

//ARTICLE
define ( "CONTENT_ARTICLE", "Articolo" );
define ( "CONTENT_ARTICLE_NEW_TITLE", "Scrivi articolo" );
define ( "CONTENT_ARTICLE_EDIT_TITLE", "Modifica articolo" );

define ( "CONTENT_ARTICLE_DELETE", "Elimina articolo" );

define ( "CONTENT_ARTICLE_SAVED", "Articolo salvato" );
define ( "CONTENT_ARTICLE_MODIFIED", "Articolo modificato" );
define ( "CONTENT_ARTICLE_DELETED", "Articolo eliminato" );

define ( "CONTENT_ARTICLE_NONE", "Nessun articolo presente" );
define ( "CONTENT_ARTICLE_NOTFOUND", "Articolo non disponibile" );
define ( "CONTENT_ARTICLE_NOTFOUND_EXPLAIN", "L'articolo richiesto non è stato trovato." );

define ( "CONTENT_HOMEPAGE_SAVED", "Homepage salvata" );
define ( "CONTENT_HOMEPAGE_EDIT_TITLE", "Modifica homepage" );

//CATEGORY
define ( "CONTENT_CATEGORY", "Categoria" );
define ( "CONTENT_CATEGORY_MOVE", "Sposta nella categoria" );
define ( "CONTENT_CATEGORY_NEW_TITLE", "Nuova categoria" );
define ( "CONTENT_CATEGORY_EDIT_TITLE", "Modifica categoria" );

define ( "CONTENT_CATEGORY_DELETE", "Elimina categoria" );

define ( "CONTENT_CATEGORY_SAVED", "Categoria salvata" );
define ( "CONTENT_CATEGORY_MODIFIED", "Categoria modificata" );
define ( "CONTENT_CATEGORY_DELETED", "Categoria eliminata" );

//FEED
define ( "CONTENT_FEED_NOTFOUND", "Il feed richiesto non esiste" );

/*
 FRONTEND
 */

define ( "CONTENT_NEWS", "News" );

//SEARCH ENGINE
define ( "CONTENT_SEARCH", "Cerca nel sito" );
define ( "CONTENT_SEARCH_RESULT", "Risultati della ricerca" );
define ( "CONTENT_SEARCH_RESULT_TAGS", "Risultati della ricerca per tag" );
define ( "CONTENT_SEARCH_NONE", "Nessun risultato per %s" );
define ( "CONTENT_SEARCH_NONE_TAGS", "Nessun risultato per i tag %s" );
define ( "CONTENT_SEARCH_LENGTH", "Inserire una parola di almeno 3 lettere" );

//ARTICLES
define ( "CONTENT_WRITEBY", "Scritto da" );
define ( "CONTENT_READALL", "leggi tutto" );
define ( "CONTENT_READED", "Letture" );
define ( "CONTENT_EDITOR_TIME", "Ultima modifica" );
define ( "CONTENT_EDITOR_FROM", "di" );

//COMMENTS
define ( "CONTENT_COMMENT_GUESTPOST", "Solo gli utenti registrati possono inviare commenti." );
define ( "CONTENT_COMMENT", "Commento" );
define ( "CONTENT_COMMENT_TITLE", "Commenti sull'articolo" );
define ( "CONTENT_COMMENT_NEWCOMMENT", "Invia un nuovo commento" );
define ( "CONTENT_COMMENT_NAME", "Nome" );
define ( "CONTENT_COMMENT_EMAIL", "Email" );
define ( "CONTENT_COMMENT_EMAIL_TIP", "L'indirizzo email inserito non verrà visualizzato." );
define ( "CONTENT_COMMENTS", "commenti" );
define ( "CONTENT_COMMENT_VIEW", "Mostra commento" );
define ( "CONTENT_COMMENT_NOCOMMENT", "Nessun commento inviato" );
define ( "CONTENT_COMMENT_SEND", "Invia" );
define ( "CONTENT_COMMENT_INSERT", "Commento inserito" );
define ( "CONTENT_COMMENT_DELETE", "Elimina commento di %s" );
define ( "CONTENT_COMMENT_DELETED", "Commento eliminato" );
define ( "CONTENT_COMMENT_DATE", "Data" );
define ( "CONTENT_COMMENT_USER", "Mittente" );
define ( "CONTENT_COMMENT_ADDR", "Indirizzo IP" );
define ( "CONTENT_COMMENT_OPRHAN", "Articolo non disponibile" );

define ( "CONTENT_COMMENTS_FOR", "Commenti per %s" );
define ( "CONTENT_COMMENTS_BY", "Commento scritto da %s" );

?>