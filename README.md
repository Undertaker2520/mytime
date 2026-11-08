# Sito di ASD My Time

Sito vetrina dell'associazione sportiva dilettantistica **My Time** di Capannori (LU): corsi, sedi, orari e contatti. WordPress con un child theme a blocchi di Twenty Twenty-Five, senza page builder e senza risorse di terze parti.

Il brief completo è in [CLAUDE.md](CLAUDE.md).

## Cosa chiedere all'associazione

Tutto ciò che sul sito è scritto **[DA COMPLETARE: …]**. In ordine di priorità.

**1. Blocca il lavoro**

- [ ] Logo originale in alta risoluzione o SVG (va in `assets/`; ora il magenta della palette è stimato dai volantini)
- [ ] Numero da usare per WhatsApp (pulsanti "Scrivi su WhatsApp" in Home e nei contatti)
- [ ] A chi corrispondono 347 2177602 e 345 1584374
- [ ] Indirizzi delle palestre di Camigliano e Lunata, e quindi i link a Google Maps
- [ ] Conferma che Balli di gruppo, Danza del ventre e Hip hop quest'anno non ci sono
- [ ] Step e Stretching sono attivi? Dove e quando? (pagina Corsi)
- [ ] Tariffe dei corsi My Time e documenti per l'iscrizione, per esempio il certificato medico (pagina Iscrizioni e costi)
- [ ] Dominio e account OVH intestati all'associazione: chi li apre e chi paga

**2. Prima di andare online**

- [ ] Denominazione legale esatta, codice fiscale, sede legale (piè di pagina e Privacy)
- [ ] La pagina Facebook è ancora gestita dall'associazione?
- [ ] Approvare le descrizioni degli otto corsi (proposte da Nicholas, pagina Corsi)
- [ ] Per ogni corso: a chi è rivolto e nome dell'istruttore, con il suo consenso a pubblicarlo
- [ ] Descrizione di Fit & Walk e dettagli di My Time Performance (Home)
- [ ] Foto da usare; per i minori serve il consenso scritto dei genitori
- [ ] Rilettura dell'informativa Privacy (ora è una bozza), con hosting e tempi di conservazione dei log

**3. Per la messa in sicurezza**

- [ ] (Nicholas) Confermare il contatto in `security.txt`: ora è https://nicholasbarsanti.it
- [ ] Nomi ed email dei soci che avranno un account Editore (ognuno con un'app di autenticazione sul telefono)
- [ ] Account Google Drive o Dropbox dell'associazione per i backup

## Avvio in locale

Serve [LocalWP](https://localwp.com/) con un sito chiamato `mytime`.

1. Clona il repository, per esempio in `C:\sviluppo\mytime`.
2. Collega tema e plugin a Local con due junction (PowerShell, non serve essere amministratore):

   ```powershell
   $site = "$env:USERPROFILE\Local Sites\mytime\app\public"
   New-Item -ItemType Junction -Path "$site\wp-content\themes\mytime" -Target "C:\sviluppo\mytime\tema"
   New-Item -ItemType Junction -Path "$site\wp-content\plugins\mytime-sicurezza" -Target "C:\sviluppo\mytime\sicurezza"
   ```

3. In WP Admin attiva il tema **My Time** (Twenty Twenty-Five deve restare installato) e il plugin **My Time – Sicurezza**.
4. Per provare `.htaccess` il sito Local deve usare Apache (Overview → Web server): copia `server/.htaccess` nella cartella `app\public`. L'Apache di Local non carica `mod_headers`: aggiungi `LoadModule headers_module "{{ modules }}/mod_headers.so"` in `Local Sites\mytime\conf\apache\modules.conf.hbs` e riavvia il sito, altrimenti gli header di sicurezza non partono.

> ⚠️ Per togliere una junction usa solo `cmd /c rmdir "<percorso della junction>"`. `Remove-Item -Recurse` può cancellare i file veri. Togli le junction anche prima di eliminare il sito in Local.

Le pagine (Home, Corsi, Sedi e orari, Iscrizioni e costi, Contatti, Privacy) e il menu stanno nel database di WordPress, non nel repository.

## Struttura del tema

```
tema/
├─ style.css         intestazione del child theme (Template: twentytwentyfive)
├─ theme.json        palette, font, dimensioni, pulsanti e le poche righe di CSS
├─ functions.php     categoria "My Time" per i pattern
├─ parts/footer.html piè di pagina dell'associazione
└─ patterns/         apertura, scheda corso, scheda sede, tabella orari, invito a contattarci
```

- Il CSS aggiuntivo sta in `theme.json` → `styles.css`, perché Twenty Twenty-Five non carica lo `style.css` del child.
- I colori usano i nomi di Twenty Twenty-Five (`base`, `contrast`, `accent-1`…), così i template del tema padre restano coerenti. Contrasti WCAG AA verificati: magenta `#E2007A` solo come sfondo dei pulsanti (testo bianco 4,65:1), magenta scuro `#B8005F` per i link.
- Font: Manrope, self-hosted dal tema padre.
- I soci possono scegliere solo colori e dimensioni del tema (`custom: false`).
- Gli orari sono sia nella scheda di ogni corso sia nella tabella della sede: quando cambiano vanno corretti in tutti e due i punti.

## Sicurezza

```
sicurezza/mytime-sicurezza.php   plugin con le protezioni in PHP
server/.htaccess                 .htaccess completo per la cartella principale su OVH
server/.well-known/security.txt  contatto per segnalare problemi di sicurezza
```

Il plugin **My Time – Sicurezza** fa tutto in codice versionato, senza toccare `wp-config.php`:

- `DISALLOW_FILE_EDIT`: niente editor dei file di temi e plugin in WP Admin
- aggiornamenti automatici di core (anche versioni maggiori), plugin e temi
- XML-RPC spento, pingback compresi
- nomi utente non scopribili: `?author=N` e `/author/…` portano alla Home; `/wp-json/wp/v2/users` esiste solo per chi ha fatto l'accesso; niente utenti nella sitemap né nei dati oEmbed; stesso messaggio per nome utente o password sbagliati; "password dimenticata" risponde allo stesso modo anche per utenti inesistenti
- mai Google Fonts, nemmeno in amministrazione: UpdraftPlus caricava Inter da `fonts.googleapis.com` nella sua pagina
- niente script delle emoji sul sito pubblico (creava un worker bloccato dalla CSP e poteva caricare immagini da s.w.org)
- meta description presa dal "Riassunto" di pagine e articoli

`server/.htaccess` aggiunge: redirect a HTTPS, blocco di `xmlrpc.php`, `wp-config.php`, `readme.html` e `license.txt`, niente PHP in `wp-content/uploads`, niente elenco delle cartelle, e gli header HSTS, X-Content-Type-Options, X-Frame-Options, Referrer-Policy, Permissions-Policy e Content-Security-Policy (più permissiva in amministrazione, dove l'editor ne ha bisogno).

Gli avatar Gravatar sono spenti (Impostazioni → Discussione), così nemmeno l'amministrazione chiama domini esterni.

### Checklist

- [x] Plugin di sicurezza attivo e verificato in locale
- [x] `.htaccess` provato in locale con Apache: sito, editor, Editor del sito, caricamento immagini, nessuna violazione della CSP
- [ ] Su OVH: prefisso tabelle diverso da `wp_` all'installazione
- [ ] Su OVH: un solo amministratore (nessun utente "admin"), account **Editore** per i soci
- [x] Two Factor 0.17.0 e UpdraftPlus 1.26.14 installati in locale e provati con la CSP (codice QR e pagina backup senza violazioni)
- [ ] Two Factor attivo per tutti gli account (ognuno configura la sua app dal proprio profilo)
- [ ] UpdraftPlus: backup settimanali su Google Drive o Dropbox dell'associazione, ripristino provato
- [ ] Twenty Twenty-Three e Twenty Twenty-Four eliminati
- [ ] `security.txt` caricato in `/.well-known/` e `Expires` rinnovato ogni anno
- [ ] securityheaders.com almeno A, con l'amministrazione funzionante

## Ancora da scrivere

Pubblicazione su OVH e guida per i soci (`GUIDA-SOCI.md`).
