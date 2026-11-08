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

- [ ] Nomi ed email dei soci che avranno un account Editore (ognuno con un'app di autenticazione sul telefono)
- [ ] Account Google Drive o Dropbox dell'associazione per i backup

## Avvio in locale

Serve [LocalWP](https://localwp.com/) con un sito chiamato `mytime`.

1. Clona il repository, per esempio in `C:\sviluppo\mytime`.
2. Collega il tema a Local con una junction (PowerShell, non serve essere amministratore):

   ```powershell
   $site = "$env:USERPROFILE\Local Sites\mytime\app\public"
   New-Item -ItemType Junction -Path "$site\wp-content\themes\mytime" -Target "C:\sviluppo\mytime\tema"
   ```

3. In WP Admin → Aspetto → Temi attiva **My Time** (Twenty Twenty-Five deve restare installato).

> ⚠️ Per togliere la junction usa solo `cmd /c rmdir "$site\wp-content\themes\mytime"`. `Remove-Item -Recurse` può cancellare i file veri del tema. Togli la junction anche prima di eliminare il sito in Local.

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

## Ancora da scrivere

Pubblicazione su OVH, checklist di sicurezza e guida per i soci (`GUIDA-SOCI.md`).
