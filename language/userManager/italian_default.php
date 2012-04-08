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

define ( "USERMANAGER_NAME", "Nome completo" );
define ( "USERMANAGER_EMAIL", "Email" );
define ( "USERMANAGER_USERNAME", "Nome utente" );
define ( "USERMANAGER_PASSWORD", "Password" );
define ( "USERMANAGER_NEWPASSWORD", "Nuova password" );
define ( "USERMANAGER_PASSWORD2", "Verifica password" );
define ( "USERMANAGER_PERMS", "Permessi" );
define ( "USERMANAGER_USERLANG", "Lingua preferita" );
define ( "USERMANAGER_REGISTRATIONDATE", "Data registrazione" );
define ( "USERMANAGER_VISITDATE", "Ultima visita" );
define ( "USERMANAGER_BLOCK", "Blocca l'account" );
define ( "USERMANAGER_CONNECTED", "Connesso" );

define ( "USERMANAGER_LOGGEDOUT", "Utente disconnesso" );

//USER ADMINISTRATION
define ( "USERMANAGER_LOOKUP_ERROR", "Errori" );
define ( "USERMANAGER_IP", "Indirizzo IP" );
define ( "USERMANAGER_CLIENT", "Browser" );
define ( "USERMANAGER_HIDDEN", "Nascosto" );
define ( "USERMANAGER_EXPIRE", "Scadenza sessione" );
define ( "USERMANAGER_LANGUAGE", "Lingua" );
define ( "USERMANAGER_URL", "Pagina corrente" );
define ( "USERMANAGER_REFERER", "Pagina di provenienza" );
define ( "USERMANAGER_HOSTNAME", "Nome host" );
define ( "USERMANAGER_COUNTRYNAME", "Nome paese" );
define ( "USERMANAGER_COUNTRYCODE", "Codice paese" );
define ( "USERMANAGER_REGIONNAME", "Nome regione" );
define ( "USERMANAGER_REGIONCODE", "Codice regione" );
define ( "USERMANAGER_CITY", "Città" );
define ( "USERMANAGER_ZIPCODE", "Codice postale" );
define ( "USERMANAGER_LATITUDE", "Latitudine" );
define ( "USERMANAGER_LONGITUDE", "Longitudine" );
define ( "USERMANAGER_WHOIS", "Richiesta WHOIS" );
define ( "USERMANAGER_CURRENTPAGE", "Pagina corrente" );
define ( "USERMANAGER_USERS_DELBLOCK", "Non è possibile cancellare se stessi." );
define ( "USERMANAGER_USERS_DELCONFIRM", "Cancellare definitivamente gli utenti selezionati?" );
define ( "USERMANAGER_USERS_GROUPDELCONFIRM", "Cancellare definitivamente i gruppi selezionati?" );
define ( "USERMANAGER_USERS_EDIT", "Modifica utente" );
define ( "USERMANAGER_USERS_ADD", "Aggiungi utente" );
define ( "USERMANAGER_USERS_CHECKBLOCK", "Blocca" );
define ( "USERMANAGER_USERS_CHECKBLOCK_EMPTY", "Selezionare gli utenti da bloccare!" );
define ( "USERMANAGER_USERS_CHECKBLOCKED", "Gli utenti selezionati sono stati bloccati" );
define ( "USERMANAGER_USERS_CHECKDELETE", "Elimina" );
define ( "USERMANAGER_USERS_CHECKDELETE_EMPTY", "Selezionare gli utenti da eliminare!" );
define ( "USERMANAGER_USERS_CHECKDELETED", "Gli utenti selezionati sono stati eliminati" );
define ( "USERMANAGER_USERS_DELETE", "Elimina utente" );
define ( "USERMANAGER_USERS_SAVED", "Utente inserito" );
define ( "USERMANAGER_USERS_EDITED", "Utente modificato" );
define ( "USERMANAGER_USERS_DELETED", "Utente eliminato" );
define ( "USERMANAGER_USERS_NOTFOUND", "Utente non trovato" );

//GROUP ADMINISTRATION
define ( "USERMANAGER_GROUP", "Gruppo" );
define ( "USERMANAGER_GROUPS_PERMS", "Permessi gruppo" );

define ( "USERMANAGER_GROUPS_USERS", "Membri" );
define ( "USERMANAGER_GROUPS_BLOCK", "Non è possibile cancellare il gruppo selezionato." );
define ( "USERMANAGER_GROUPS_ADD", "Aggiungi gruppo" );
define ( "USERMANAGER_GROUPS_EDIT", "Modifica gruppo" );
define ( "USERMANAGER_GROUPS_NAME", "Nome del gruppo" );
define ( "USERMANAGER_GROUPS_SAVE", "Salva gruppo" );
define ( "USERMANAGER_GROUPS_DELETE", "Elimina gruppo" );
define ( "USERMANAGER_GROUPS_CREATION", "Data di creazione" );
define ( "USERMANAGER_GROUPS_DELETED", "Gruppo eliminato" );
define ( "USERMANAGER_GROUPS_SAVED", "Gruppo inserito" );
define ( "USERMANAGER_GROUPS_MODIFIED", "Gruppo modificato" );

define ( "USERMANAGER_LOGIN", "Login" );
define ( "USERMANAGER_LOGOUT", "Disconnetti" );

define ( "USERMANAGER_LOGIN_INCORRECT", "Username o password non validi" );
define ( "USERMANAGER_LOGIN_NOPRIV", "Non si dispone dei privilegi per l'accesso." );
define ( "USERMANAGER_LOGIN_INUSE", "L'account è già in uso da un altro utente!" );
define ( "USERMANAGER_LOGIN_MAXATTEPTS", "E' stato raggiunto il massimo numero di tentativi. Riprovare più tardi." );

//TITLES
define ( "USERMANAGER_SESSIONINFO", "Dettagli della connessione" );
define ( "USERMANAGER_DETAILS", "Dettagli dell'utente" );
define ( "USERMANAGER_IPLOOKUP", "Informazioni indirizzo ip" );
define ( "USERMANAGER_USERADMIN", "Gestione utenti" );
define ( "USERMANAGER_SITECONFIG", "Configurazione sito" );
define ( "USERMANAGER_ACCOUNT", "Profilo personale" );
define ( "USERMANAGER_ACCOUNT_PM", "Messaggi privati" );
define ( "USERMANAGER_GROUPS", "Gestione gruppi" );
define ( "USERMANAGER_USERS", "Gestione utenti" );
define ( "USERMANAGER_REGISTRATION", "Registrazione" );
define ( "USERMANAGER_CONFIG", "Configurazione" );

//REGISTRATION
define ( "USERMANAGER_REGISTRATION_UNOTAVAILABLE", "Lo username %s è già stato utilizzato." );
define ( "USERMANAGER_REGISTRATION_EMAILNOTAVAILABLE", "L'indirizzo email scelto è già in uso da un altro utente." );
define ( "USERMANAGER_REGISTRATION_SENDMAIL", "E' stata inviata una email all'indirizzo specificato per completare la registrazione." );
define ( "USERMANAGER_REGISTRATION_COMPLETE", "Registrazione al sito completata." );
define ( "USERMANAGER_REGISTRATION_FAIL", "Registrazione al sito fallita." );
define ( "USERMANAGER_REGISTRATION_INCORRECT", "Impossibile completare la registrazione, alcuni dati non sono corretti." );
define ( "USERMANAGER_REGISTRATION_NEWPASS", "La nuova password è stata inviata via email all'indirizzo <b>%s</b>." );
define ( "USERMANAGER_REGISTRATION_REGISTER", "Registrati" );
define ( "USERMANAGER_REGISTRATION_CHECKPASS", "Inserire una password dagli 8 ai 20 caratteri" );
define ( "USERMANAGER_REGISTRATION_CHECKEQUALPASS", "Le due password non corrispondono" );
define ( "USERMANAGER_REGISTRATION_NEWPASS_FAIL", "Dati inseriti non validi, ricontrollare i dati" );
define ( "USERMANAGER_REGISTRATION_FORGOT", "Richiesta nuova password" );
define ( "USERMANAGER_REGISTRATION_PRIVACY", "Informativa sulla privacy" );
define ( "USERMANAGER_REGISTRATION_PRIVACY_ACCEPT", "Accetto quanto riportato nell'informativa sulla privacy" );
define ( "USERMANAGER_REGISTRATION_PRIVACY_ERROR", "È necessario accettare l'informativa sulla privacy per completare la registrazione" );
define ( "USERMANAGER_REGISTRATION_RESEND", "L'account è in attesa della conferma via email all'indirizzo <b>%s</b>.<br/>Cliccare sul pulsante seguente per inviare un'altra email.<br/>La password verrà rigenerata, sarà comunque possibile cambiarla in seguito." );
define ( "USERMANAGER_REGISTRATION_RESEND_BUTTON", "Invia" );

define ( "USERMANAGER_OPENID_INFO", "OpenID è un meccanismo che permette di avere un unico nome utente e password registrati presso un provider OpenID.<br />
I siti che supportano OpenID ti chiederanno solo l'indirizzo del tuo provider, in modo che quando vorrai fare il login la tua richiesta verr&#224; ridirezionata la tuo provider.<br />
Per maggiori informazioni su OpenID visitare il <a href=\"http://openid.net/\">sito ufficiale</a>." );

define ( "USERMANAGER_OPENID_DOWN", "Impossibile contattare il provider OpenID." );
define ( "USERMANAGER_OPENID_LOGIN_INCORRECT", "Identificatore OpenID non valido." );
define ( "USERMANAGER_OPENID_LOGIN_NOAUTH", "Autorizzazione OpenID non valida." );
define ( "USERMANAGER_OPENID_LOGIN_CANCELED", "Richiesta di validazione annullata." );

define ( "USERMANAGER_OPENID_LOGIN", "Login con OpenID" );
define ( "USERMANAGER_OPENID_PROVIDER", "Provider OpenID" );
define ( "USERMANAGER_OPENID_ENABLE", "Abilita login con OpenID" );

define ( "USERMANAGER_REGISTRATION_SREG_BUTTON", "OpenID Simple Registration" );
define ( "USERMANAGER_REGISTRATION_SREG_NOTAVAILABLE", "L'indirizzo %s è già associato ad un utente." );
define ( "USERMANAGER_REGISTRATION_SREG_TITLE", "Registrazione rapida con OpenID" );

define ( "USERMANAGER_REGISTRATION_SREG_GETINFO", "Carica dati" );
define ( "USERMANAGER_REGISTRATION_SREG_COMPLETE", "Registrazione rapida completata" );

//REGISTRATION MAIL
define ( "USERMANAGER_REGISTRATION_EMAIL_TITLE", "Registrazione al sito" );
define ( "USERMANAGER_REGISTRATION_EMAIL_PART1", "Buongiorno" );
define ( "USERMANAGER_REGISTRATION_EMAIL_PART2", "questa email è stata inviata in seguito alla richiesta di registrazione sul sito" );
define ( "USERMANAGER_REGISTRATION_EMAIL_PART3", "Per completare la procedura di registrazione cliccare sul collegamento seguente" );
define ( "USERMANAGER_REGISTRATION_EMAIL_PART4", "Queste sono alcune informazioni dell'account appena creato" );

//PASSWORD FORGOT MAIL
define ( "USERMANAGER_FORGOT_EMAIL_TITLE", "Recupero password su" );
define ( "USERMANAGER_FORGOT_PWD", "Invia password" );
define ( "USERMANAGER_FORGOT_EMAIL_PART1", "questa email è stata inviata in seguito alla richiesta di recupero della password sul sito" );
define ( "USERMANAGER_FORGOT_EMAIL_PART2", "Questi sono i dati per accedere al sito, per cambiare la password accedere al sito con questi dati e modificarli nel profilo utente" );

//PASSWORD CHANGED MAIL
define ( "USERMANAGER_CHANGEPASS_EMAIL_TITLE", "Password cambiata su" );
define ( "USERMANAGER_CHANGEPASS_EMAIL_PART1", "questa email è stata inviata in seguito al cambiamento della password sul sito" );
define ( "USERMANAGER_CHANGEPASS_EMAIL_PART2", "Questi sono i nuovi dati per l'accesso con la nuova password" );

//PRIVATE MESSAGES NOTIFICATION
define ( "USERMANAGER_ACCOUNT_PM_EMAIL_TITLE", "Nuovo messaggio su" );
define ( "USERMANAGER_ACCOUNT_PM_EMAIL_PART1", "È arrivato un nuovo messaggio personale dall'utente " );
define ( "USERMANAGER_ACCOUNT_PM_EMAIL_PART2", "Clicca sul link seguente per leggere il messaggio" );

//USER ACCOUNT
define ( "USERMANAGER_ACCOUNT_EDIT", "Modifica profilo" );
define ( "USERMANAGER_ACCOUNT_EDITED", "Profilo modificato" );
define ( "USERMANAGER_ACCOUNT_ARTICLES", "Articoli pubblicati" );
define ( "USERMANAGER_ACCOUNT_ARTICLES_TITLE", "Articolo" );
define ( "USERMANAGER_ACCOUNT_ARTICLES_READED", "Letture" );
define ( "USERMANAGER_ACCOUNT_OPRHAN", "Articolo non disponibile" );
define ( "USERMANAGER_ACCOUNT_ARTICLES_COMMENTS", "Commenti" );
define ( "USERMANAGER_ACCOUNT_ARTICLES_EDITORTIME", "Ultima modifica" );
define ( "USERMANAGER_ACCOUNT_ARTICLES_AUTHORTIME", "Data pubblicazione" );
define ( "USERMANAGER_ACCOUNT_ARTICLES_ADD", "Nuovo articolo" );
define ( "USERMANAGER_ACCOUNT_COMMENTS", "Commenti lasciati" );
define ( "USERMANAGER_ACCOUNT_COMMENTS_VIEW", "Vai a commento" );
define ( "USERMANAGER_ACCOUNT_COMMENTS_DATE", "Data del commento" );
define ( "USERMANAGER_ACCOUNT_OPENID_IDERROR", "Inserire l'indirizzo di un provider OpenID valido." );

//EMAIL CHANGE
define ( "USERMANAGER_CHANGEEMAIL_EDIT", "Cambia email" );
define ( "USERMANAGER_CHANGEEMAIL_SENT", "Una email di conferma è stata inviata al nuovo indirizzo <b>%s</b>.<br/>Seguire le instruzioni contenute per completare la procedura." );
define ( "USERMANAGER_CHANGEEMAIL_EDIT_TEXT", "Una email verrà inviata al nuovo indirizzo per confermarlo, seguire le istruzioni in essa contenute per cambiare indirizzo email." );
define ( "USERMANAGER_CHANGEEMAIL_EDIT_NEW", "Nuovo indirizzo email" );
define ( "USERMANAGER_CHANGEEMAIL_EDIT_EXISTS", "L'indirizzo richiesto è già stato utilizzato da un altro utente." );
define ( "USERMANAGER_CHANGEEMAIL_EMAIL_TITLE", "Cambiamento indirizzo email" );
define ( "USERMANAGER_CHANGEEMAIL_EMAIL_PART1", "questa email è stata inviata in seguito alla richiesta di cambiamento dell'indirizzo email sul sito " );
define ( "USERMANAGER_CHANGEEMAIL_EMAIL_PART2", "Per attivare il nuovo indirizzo email cliccare sul collegamento seguente" );

define ( "USERMANAGER_ACCOUNT_ERROR_GROUP", "Selezionare un gruppo" );
define ( "USERMANAGER_ACCOUNT_ERROR_LANG", "Selezionare una lingua" );

//USER ACCOUNT PM
define ( "USERMANAGER_ACCOUNT_PM_NEW", "Nuovo PM" );
define ( "USERMANAGER_ACCOUNT_PM_READ", "Leggi messaggio" );
define ( "USERMANAGER_ACCOUNT_PM_CLOSE", "Chiudi" );
define ( "USERMANAGER_ACCOUNT_PM_SEND", "Invia PM" );
define ( "USERMANAGER_ACCOUNT_PM_DELETE", "Elimina PM" );
define ( "USERMANAGER_ACCOUNT_PM_REPLY", "Rispondi" );
define ( "USERMANAGER_ACCOUNT_PM_SENDER", "Mittente" );
define ( "USERMANAGER_ACCOUNT_PM_FROM", "Messaggio inviato da %s %s" );
define ( "USERMANAGER_ACCOUNT_PM_RECIPIENT", "Destinatari" );
define ( "USERMANAGER_ACCOUNT_PM_RECIPIENT_TIP", "Selezionare i destinatari del messaggio (massimo %d)" );
define ( "USERMANAGER_ACCOUNT_PM_SUBJECT", "Oggetto" );
define ( "USERMANAGER_ACCOUNT_PM_ISREAD", "Letto" );
define ( "USERMANAGER_ACCOUNT_PM_MESSAGE", "Messaggio" );
define ( "USERMANAGER_ACCOUNT_PM_DATE", "Data" );
define ( "USERMANAGER_ACCOUNT_PM_UNREAD", "messaggi non letti" );
define ( "USERMANAGER_ACCOUNT_PM_CHECKREC", "Selezionare il destinatario." );
define ( "USERMANAGER_ACCOUNT_PM_CHECKSUB", "Inserire l'oggetto." );
define ( "USERMANAGER_ACCOUNT_PM_CHECKMSG", "Inserire il messaggio." );
define ( "USERMANAGER_ACCOUNT_PM_SENTOK", "Messaggio inviato" );
define ( "USERMANAGER_ACCOUNT_PM_DELETED", "Messaggio eliminato" );
define ( "USERMANAGER_ACCOUNT_PM_DELETED_ERROR", "Impossibile eliminare il messaggio." );
define ( "USERMANAGER_ACCOUNT_PM_NOUSER", "Utenti non trovati (%s)" );
define ( "USERMANAGER_ACCOUNT_PM_OVERUSER", "Non è possibile mandare il messagio a più di %d utenti." );
define ( "USERMANAGER_ACCOUNT_PM_EMPTY", "Nessun messaggio" );
define ( "USERMANAGER_ACCOUNT_PM_RECEIVED", "Messaggi ricevuti" );
define ( "USERMANAGER_ACCOUNT_PM_SENT", "Messaggi inviati" );
define ( "USERMANAGER_ACCOUNT_PM_NOTFOUND", "Messaggio privato non trovato" );

//ONLINE
define ( "USERMANAGER_ONLINE_GUEST", "ospite" );
define ( "USERMANAGER_ONLINE_GUESTS", "ospiti" );
define ( "USERMANAGER_ONLINE_REGISTER", "utente" );
define ( "USERMANAGER_ONLINE_REGISTERS", "utenti" );
define ( "USERMANAGER_ONLINE_AND", "e" );
define ( "USERMANAGER_ONLINE_HAVE", "Abbiamo" );
define ( "USERMANAGER_ONLINE_ONLINE", "connesso" );
define ( "USERMANAGER_ONLINE_MOREONLINE", "connessi" );

?>