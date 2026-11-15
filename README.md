# Sito di ASD My Time

Sito vetrina dell'associazione sportiva dilettantistica **My Time** di Capannori (LU): corsi, sedi, orari e contatti. WordPress con un child theme a blocchi di Twenty Twenty-Five, senza page builder e senza risorse di terze parti.

Il brief completo è in [CLAUDE.md](CLAUDE.md).

## Cosa chiedere all'associazione

Tutto ciò che sul sito è scritto **[DA COMPLETARE: …]**. In ordine di priorità.

**1. Blocca il lavoro**

- [ ] Logo originale in alta risoluzione o SVG (va in `assets/`; ora il magenta della palette è stimato dai volantini)
- [ ] Numero da usare per WhatsApp (pulsanti "Scrivi su WhatsApp" in Home e nei contatti)
- [ ] A chi corrispondono 347 2177602 e 345 1584374
- [ ] Indirizzi delle palestre di Camigliano e Lunata, e quindi i link a Google Maps e le posizioni sulla mappa di Contatti
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
- [ ] Foto vere da usare al posto dei segnaposto Pexels (vedi "Foto segnaposto"); per i minori serve il consenso scritto dei genitori
- [ ] Rilettura dell'informativa Privacy (ora è una bozza), con hosting e tempi di conservazione dei log

**3. Per la messa in sicurezza**

- [ ] (Nicholas) Confermare il contatto in `security.txt`: ora è https://nicholasbarsanti.it
- [ ] (Nicholas) In `GUIDA-SOCI.md`: indirizzo del sito e contatto di Nicholas per i soci
- [ ] Nomi ed email dei soci che avranno un account Editore (ognuno con un'app di autenticazione sul telefono)
- [ ] Account Google Drive o Dropbox dell'associazione per i backup

## Foto segnaposto

Finché l'associazione non manda le sue foto, il sito usa foto gratuite di [Pexels](https://www.pexels.com/license/) (uso libero, attribuzione non obbligatoria). Sono solo nella Libreria media di WordPress, **non** nel repository. La licenza vieta di far credere che le persone ritratte sostengano l'associazione: vanno sostituite appena possibile.

| Dove | Foto | Autore |
|---|---|---|
| Apertura (Home) | [12086683](https://www.pexels.com/photo/women-dancing-in-the-hall-12086683/) | Wellness Gallery Catalyst Foundation |
| Zumba | [5936039](https://www.pexels.com/photo/group-of-people-in-a-fitness-class-5936039/) | David Awokoya |
| Pilates | [25596885](https://www.pexels.com/photo/women-stretching-on-yoga-mats-at-gym-25596885/) | Ahmet Kurt |
| Aerofantasy | [12086684](https://www.pexels.com/photo/women-dancing-in-the-hall-12086684/) | Wellness Gallery Catalyst Foundation |
| Allenamento funzionale | [6339345](https://www.pexels.com/photo/people-in-the-fitness-center-doing-jumping-jacks-6339345/) | Pavel Danilyuk |
| GAG | [864091](https://www.pexels.com/photo/woman-wearing-white-and-black-sweat-suit-doing-curl-ups-864091/) | Andrea Piacquadio |
| Step GAG | [6285206](https://www.pexels.com/photo/a-person-doing-exercise-6285206/) | Gustavo Fring |
| HIIT GAG | [6339341](https://www.pexels.com/photo/a-group-of-people-running-in-place-6339341/) | Pavel Danilyuk |
| Tonificazione | [903171](https://www.pexels.com/photo/women-having-exercise-using-dumbbells-903171/) | Andrea Piacquadio |
| Sede Camigliano | [7186312](https://www.pexels.com/photo/shiny-floor-in-gym-7186312/) | cottonbro studio |
| Sede Lunata | [7201151](https://www.pexels.com/photo/wooden-floors-inside-a-gym-7201151/) | cottonbro studio |

Nella Libreria media si riconoscono dal titolo "Segnaposto Pexels: …".

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

Le pagine (Home, Corsi, Sedi e orari, Iscrizioni e costi, Contatti, Privacy) e il menu stanno nel database di WordPress, non nel repository. Per ricrearle in locale c'è `strumenti/crea-pagine.php` (`wp eval-file`): **sovrascrive** il contenuto delle pagine (i riassunti restano, la Privacy non viene toccata) e usa le foto segnaposto della Libreria media. Non va usato sul sito online.

## Struttura del tema

```
tema/
├─ style.css         intestazione del child theme + animazioni e grafica (usa solo le variabili di theme.json)
├─ theme.json        palette, font, dimensioni, pulsanti: l'unica fonte di colori e font
├─ functions.php     carica style.css, categoria "My Time" per i pattern
├─ parts/footer.html piè di pagina dell'associazione
└─ patterns/         apertura, scheda corso, scheda sede, tabella orari, invito a contattarci, come iscriversi
```

- `style.css` è caricato da `functions.php`, perché Twenty Twenty-Five non carica lo `style.css` del child. La versione nell'URL è la data di modifica del file, così i browser non tengono un CSS vecchio. Solo la prima immagine di ogni pagina si carica subito, le altre in lazy. Niente SCSS né jQuery: le variabili sono quelle di `theme.json` (`var(--wp--preset--color--accent-1)` ecc.).
- Animazioni solo CSS: entrata dell'Apertura (il titolo resta fermo, è l'elemento LCP), schede di corsi e sedi che compaiono scorrendo (`animation-timeline: view()`, ferme dove non supportato), tutto spento con "riduci movimento".
- L'Apertura è un blocco Copertina con foto e velatura nera al 70%, testo bianco (contrasto ≥ 5,4:1 anche sul punto più chiaro della foto). Il pattern, senza foto, è nero pieno.
- Animazioni a tema ballo, solo CSS: forme colorate che saltano 4 volte (2,4 s) nell'Apertura, tratto magenta che si disegna sotto i titoli, schede che entrano con un passo alternato. Le colorazioni usano le variabili di `theme.json` (maschere e gradienti, niente colori nei SVG).
- "Come iscriversi" (`mytime/come-iscriversi`): telefono disegnato in CSS con la chat su WhatsApp. I messaggi arrivano mentre si scorre (`view-timeline` per sezione); su computer la sezione resta ferma durante la conversazione. **Su Firefox** (che non supporta ancora le animazioni a scorrimento) e con "riduci movimento" i messaggi sono tutti visibili da subito.
- Contenitore unico di 1200 px (`contentSize` = `wideSize` in `theme.json`): header, contenuto e footer hanno gli stessi bordi. Paragrafi e liste del contenuto al massimo 70ch, allineati a sinistra; testo dell'Apertura al massimo 40rem.
- Su computer le sezioni usano Colonne e Griglie adattive (`columnCount` + `minimumColumnWidth`: le colonne calano da sole su tablet e telefono). Home: anteprima di 8 corsi cliccabili verso `/corsi/#ancora`, sedi a 2 colonne, altre proposte e avvisi a 3; Corsi a 3 colonne; Sedi e orari scheda | tabella; Iscrizioni, Contatti, Invito e footer a colonne.
- **Mappa in Contatti** (pattern `mytime/mappa-distanza`, eccezione decisa da Nicholas): Leaflet 1.9.4 servito dal tema (`tema/assets/leaflet/`, licenza BSD, impronte SHA-256 verificate) e `tema/assets/js/mappa.js`, caricato solo nelle pagine con la mappa. Al caricamento la pagina non contatta nessuno; dopo il clic su "Mostra la mappa" arrivano le tessere da `tile.openstreetmap.org` e la ricerca indirizzi va a `nominatim.openstreetmap.org` (CSP aggiornata in `server/.htaccess`, Privacy aggiornata). La distanza è in linea d'aria, con il link al percorso in auto su OpenStreetMap. Le posizioni delle palestre sono nell'attributo `data-sedi` del blocco HTML: oggi sono il centro dei paesi (attenzione: a Capannori ci sono due località "Camigliano"), da sostituire con le coordinate vere.
- In locale `wp-config.php` ha `WP_DEVELOPMENT_MODE` = `theme`, così WordPress non tiene in cache l'elenco dei pattern. **Non** va messo su OVH.
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
- commenti e pingback chiusi ovunque: gli avvisi non hanno moduli che raccolgono nome ed email
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

## Guida per i soci

[GUIDA-SOCI.md](GUIDA-SOCI.md): accesso con l'app di autenticazione, cambiare un orario, aggiungere un corso con il pattern, pubblicare un avviso, cosa non fare. Le etichette sono quelle di WordPress in italiano (lingua del sito: `it_IT`).

## Ancora da scrivere

Pubblicazione su OVH.
