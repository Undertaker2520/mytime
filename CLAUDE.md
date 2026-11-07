# Progetto: sito di ASD My Time

Sito dell'associazione sportiva dilettantistica **My Time** di Capannori (LU), che organizza corsi sportivi comunali di fitness e danza nelle palestre di Camigliano e Lunata. Il sito deve far capire in pochi secondi **quali corsi ci sono, dove, quando e come contattare l'associazione**, e permettere di chiamare o scrivere su WhatsApp con un tocco dal telefono.

Lo aggiornano i soci dell'associazione, persone non tecniche: ogni contenuto che cambia durante l'anno (orari, corsi, avvisi) deve essere modificabile dall'editor di WordPress senza toccare codice.

Il sito è anche un lavoro dimostrativo di Nicholas Barsanti per il servizio "sito vetrina + messa in sicurezza WordPress": deve essere veloce, accessibile e configurato in modo esemplare dal punto di vista della sicurezza.

## Tempi e modo di lavorare

Nicholas lavora a tempo pieno e sta preparando due esami universitari (gennaio–febbraio 2027). Il progetto avanza **a piccoli passi, in sessioni da 1–2 ore**, senza scadenze. Ogni sessione deve chiudersi con qualcosa di funzionante e un commit. All'inizio di ogni sessione, riassumi in tre righe dove eravamo rimasti e proponi il prossimo passo.

## Tecnologia

- **WordPress** (ultima versione stabile), con un **tema a blocchi** personalizzato: child theme di Twenty Twenty-Five, oppure un tema a blocchi minimale scritto da zero. Niente page builder (Elementor, Divi e simili).
- Stile definito in `theme.json`: palette, tipografia, spaziature. CSS aggiuntivo al minimo.
- **Pattern di blocchi** riutilizzabili per le parti ripetute: "Scheda corso", "Scheda sede", "Tabella orari", "Apertura", "Invito a contattarci". L'editor inserisce un pattern e cambia solo i testi.
- **Plugin**: il minimo indispensabile, ognuno da proporre e motivare prima di installarlo. Candidati: Two Factor (2FA per gli account), un plugin di backup (es. UpdraftPlus). **Niente moduli**: il sito non raccoglie iscrizioni, pagamenti o dati personali (decisione dell'associazione).
- Font **self-hosted** (dichiarati in `theme.json`), mai Google Fonts da CDN. Nessuna risorsa di terze parti: niente embed di Instagram, Facebook o Google Maps (solo link), niente analytics, nessun cookie di profilazione. Così il sito non richiede banner cookie.
- Sviluppo in locale con **LocalWP** sul PC personale di Nicholas (Windows). Il tema vive in un repository Git privato.

## Hosting e pubblicazione

- Hosting **OVH Web Hosting Starter**, account e dominio **intestati all'associazione**, non a Nicholas. Dominio da definire (ipotesi: `asdmytime.it` o `mytimecapannori.it`).
- Pubblicazione: installazione WordPress su OVH, poi caricamento del tema e importazione dei contenuti. Proporre il metodo più semplice e documentarlo nel README.
- HTTPS con il certificato gratuito di OVH.

## Sicurezza (obiettivo: sito presentabile come esempio ai clienti)

- Aggiornamenti automatici attivi per core, tema e plugin.
- Account: un amministratore (Nicholas) e account **Editore** per i soci; 2FA per tutti; password robuste; nessun utente "admin".
- XML-RPC disattivato, enumerazione degli utenti bloccata, modifica dei file dal pannello disattivata (`DISALLOW_FILE_EDIT`), prefisso tabelle non standard.
- Intestazioni di sicurezza via `.htaccess` (Apache su OVH): Content-Security-Policy compatibile con l'editor di WordPress, HSTS, X-Content-Type-Options, Referrer-Policy, Permissions-Policy. Obiettivo A su securityheaders.com senza rompere l'amministrazione.
- Backup automatici settimanali conservati fuori dall'hosting.
- File `/.well-known/security.txt` con contatto di Nicholas.

## Design

- **Logo**: cerchio **nero** con il monogramma "MT" stilizzato in **rosa/magenta e bianco**, scritta "MY TIME" sotto. File: `assets/logo.png` (versione ricevuta su WhatsApp, a bassa risoluzione: usarla come segnaposto e chiedere l'originale o un SVG). Esiste anche una versione precedente con il monogramma viola (foto profilo Facebook): non usarla.
- **Palette** dal logo e dai volantini dell'associazione: nero/grafite, **magenta del logo come colore principale**, bianco, un rosa chiaro per gli sfondi tenui. I volantini usano anche accenti vivaci (azzurro, viola, verde, giallo) per distinguere i corsi: si possono usare con moderazione, per esempio come colore di ciascuna scheda corso. Verificare i contrasti WCAG AA (il magenta su bianco va controllato per i testi piccoli).
- Tono **allegro ed energico ma ordinato**, come i volantini: "Movimento · Energia · Divertimento". È un'associazione per persone di tutte le età, non una palestra aggressiva.
- Mobile-first: la maggior parte delle visite arriverà da telefono, spesso da Instagram.
- Pulsanti grandi per chiamare e scrivere su WhatsApp.
- Foto: solo quelle fornite dall'associazione. Ci sono pochi iscritti minorenni (2–3): **nessuna foto di minori senza consenso scritto dei genitori**; nel dubbio usare segnaposto o immagini senza volti riconoscibili.

## Struttura

Pagine (menu in quest'ordine):

1. **Home**: apertura con il motto, anteprima dei corsi, le due sedi, le "altre proposte", invito a contattarci.
2. **Corsi**: una scheda per corso (pattern "Scheda corso").
3. **Sedi e orari**: le due palestre con indirizzo e link a Google Maps, tabella degli orari per sede e giorno (pattern "Tabella orari").
4. **Iscrizioni e costi**: come funzionano i corsi comunali, tariffe, come iscriversi (contattando l'associazione: niente iscrizioni online).
5. **Contatti**: telefoni, WhatsApp, email, Instagram, Facebook.
6. **Privacy** (nel piè di pagina): informativa breve, titolare l'associazione, nessun cookie di profilazione. Da far verificare.

Piè di pagina: nome dell'associazione, codice fiscale, contatti, link social, "Sito realizzato da Nicholas Barsanti" con link a https://nicholasbarsanti.it.

## Segnaposto

Tutto ciò che manca va scritto come **[DA COMPLETARE: descrizione]**, ben visibile, così i soci sanno cosa sostituire. Non inventare mai orari, prezzi, nomi di istruttori o recensioni.

## Contenuti

### Motto e frasi dai materiali dell'associazione
- "Un ritaglio di tempo per divertirsi, tenersi in forma e… sorridere insieme!" (pagina Facebook)
- "ASD My Time – Il tempo per noi" e "Movimento · Energia · Divertimento" (volantini)
- "Il movimento è una forma di felicità" (volantino)

### Associazione
- Nome: **ASD My Time** [DA COMPLETARE: denominazione legale esatta]
- Codice fiscale: [DA COMPLETARE]
- Sede legale: [DA COMPLETARE]

### Contatti
- Telefono: 347 2177602 · 345 1584374 (pubblicati dal Comune di Capannori; indicare a chi corrispondono [DA COMPLETARE])
- Email: mytime.asd@libero.it
- Instagram: https://www.instagram.com/mytime.sd/
- Facebook: https://www.facebook.com/profile.php?id=100063509229571 [DA COMPLETARE: confermare che la pagina è ancora gestita dall'associazione]
- WhatsApp: [DA COMPLETARE: quale numero usare]

### Sedi
1. **Camigliano** – palestrina della scuola primaria. Indirizzo: [DA COMPLETARE]
2. **Lunata** – palestra della scuola primaria. Indirizzo: [DA COMPLETARE]

### Orari 2026-2027 (dai volantini dell'associazione)

**Camigliano – palestrina scuola primaria**

| Corso | Giorni e orari |
| --- | --- |
| Allenamento funzionale | lunedì 19:30, mercoledì 20:15 |
| Zumba | martedì 18:30, venerdì 18:30 |
| Pilates | martedì 19:15 e 20:00, giovedì 19:15 e 20:00 |
| Aerofantasy | mercoledì 19:30, venerdì 19:15 |

**Lunata – palestra scuola primaria**

| Corso | Giorni e orari |
| --- | --- |
| HIIT GAG | lunedì 21:00 |
| Step GAG | martedì 19:00 |
| Zumba | martedì 20:00, giovedì 20:00 |
| GAG | giovedì 19:00 |
| Tonificazione | giovedì 20:45 |

Il volantino di Camigliano riporta anche Balli di gruppo (mercoledì 21:00), Danza del ventre (giovedì 21:00) e Hip hop (martedì 21:00), ma l'associazione ha indicato che **questi tre corsi quest'anno non ci sono**: non inserirli. [DA COMPLETARE: confermare]

### Corsi
Per ogni corso: descrizione breve (2–3 righe, da proporre e far approvare), a chi è rivolto [DA COMPLETARE], sede e orari (dalle tabelle sopra), istruttore [DA COMPLETARE].

Corsi attivi: Zumba, Pilates, Aerofantasy, Allenamento funzionale, GAG (gambe, addominali, glutei), Step GAG, HIIT GAG, Tonificazione. Il volantino generale dei corsi comunali cita anche Step e Stretching: [DA COMPLETARE: confermare se sono attivi e dove].

### Altre proposte (dal volantino generale)
- **Fit & Walk** [DA COMPLETARE: descrizione]
- **My Time Focus**: lavoro in piccoli gruppi da 3 a 6 persone, personalizzato
- **My Time Performance**: per aumentare e migliorare le proprie capacità [DA COMPLETARE: dettagli]

### Iscrizioni e costi
- I corsi fanno parte dei **corsi sportivi comunali del Comune di Capannori**: durano 7 mesi, **dal 1° ottobre al 30 aprile**.
- Tariffe comunali 2026-2027: da 140 a 180 € a seconda della fascia (ragazzi, adulti, over 65); eventuale mese di proroga fino a 28 €. Tariffe specifiche di My Time: [DA COMPLETARE].
- Iscrizione: contattando direttamente l'associazione (telefono, WhatsApp, email). **Il sito non raccoglie iscrizioni né pagamenti.** Documenti richiesti (es. certificato medico): [DA COMPLETARE].
- Link alla pagina del Comune: https://www.comune.capannori.lu.it/notizia/giovedi-1-ottobre-iniziano-i-corsi-sportivi-comunali-2026-2027-iscrizioni-aperte/

## Regole di lavoro

- Prima di scrivere codice proponi un piano (struttura del tema, pattern, palette, plugin) e aspetta conferma.
- Testi in italiano, tono caldo e semplice, frasi brevi.
- Commit piccoli e descrittivi in italiano.
- `README.md` con: avvio in locale con LocalWP, struttura del tema, pubblicazione su OVH, checklist di sicurezza.
- Alla fine prepara una **guida per i soci** (`GUIDA-SOCI.md`), con istruzioni passo passo e linguaggio non tecnico: accedere, cambiare un orario, aggiungere un corso con il pattern, pubblicare un avviso, cosa non toccare.

## Checklist finale

- [ ] Leggibile e usabile su telefono, pulsanti di chiamata e WhatsApp funzionanti
- [ ] Nessuna richiesta verso domini esterni nelle pagine pubbliche
- [ ] Lighthouse ≥ 95 su Performance, Accessibilità, Best Practices e SEO
- [ ] securityheaders.com almeno A, amministrazione funzionante
- [ ] XML-RPC disattivato, 2FA attivo, aggiornamenti automatici attivi, backup verificato
- [ ] Tutti i [DA COMPLETARE] elencati nel README, in modo da sapere cosa chiedere all'associazione
