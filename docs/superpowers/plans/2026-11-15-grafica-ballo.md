# Grafica a tema ballo Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Foto segnaposto, sezioni più larghe, animazioni CSS a tema ballo e una sezione "Come iscriversi" con un telefono la cui chat si anima allo scorrimento.

**Architecture:** Tutto nel child theme: CSS in `tema/style.css` (solo variabili di `theme.json`), un nuovo pattern `mytime/come-iscriversi`, pattern Apertura aggiornato. Le foto stanno nella Libreria media (non nel repository); le pagine si rigenerano con lo script wp-cli `crea-pagine.php` della sessione, che inserisce foto e nuove sezioni.

**Tech Stack:** WordPress 7.1.4, Twenty Twenty-Five 1.5 child, CSS scroll-driven animations (`view-timeline`, `animation-timeline`), wp-cli di Local, browser integrato e Playwright per le verifiche, Lighthouse via `npx`.

**Spec:** `docs/superpowers/specs/2026-11-15-grafica-ballo-design.md`

## Global Constraints

- Nessuna richiesta a domini esterni dalle pagine pubbliche; CSP pubblica `img-src 'self' data:`.
- Niente JavaScript sul sito pubblico, niente jQuery/SCSS/librerie; colori e font solo da `var(--wp--preset--*)`.
- Animazioni automatiche ≤ 3 s e poi ferme; quelle a scorrimento solo dentro `@supports (animation-timeline: view())`; tutte spente con `prefers-reduced-motion: reduce` (regola già in `style.css`).
- Il titolo `h1` dell'Apertura non si anima (LCP).
- Testo a 645 px (`contentSize` invariato); larghe fino a 1340 px (`alignwide`) griglia corsi, griglia sedi, sezione "Come iscriversi".
- Velatura Apertura con foto: `overlayColor: contrast`, `dimRatio: 65`, testo bianco.
- Chat: colori di My Time, nessun logo/verde/grafica di WhatsApp.
- Foto: Pexels, solo adulti, massimo 1600 px di lato, scaricate solo dopo l'approvazione dell'elenco da parte di Nicholas.
- Testi della chat (da far approvare): «Ciao! Vorrei iscrivermi a Zumba a Lunata 💃» / «Ciao! Zumba a Lunata è il martedì e il giovedì alle 20:00. Ti spieghiamo noi costi e documenti 😊» / «Perfetto, grazie!»

## Review Focus

- Firefox (niente `animation-timeline`): tutti i messaggi e le schede devono essere visibili e fermi, non invisibili a opacità 0. → test in Task 4.
- Socio che inserisce il pattern "Come iscriversi" in un'altra pagina o due volte nella stessa pagina: la timeline nominata deve restare per sezione. → test in Task 4.
- Socio che cambia o aggiunge un messaggio (4 invece di 3): il quarto deve comunque comparire. → test in Task 4.
- Apertura senza foto (pattern inserito di nuovo): deve restare leggibile, non grigia. → test in Task 2.
- Foto molto chiara nell'Apertura: contrasto del testo ≥ 4,5:1. → calcolo in Task 2.

---

### Task 1: Foto da Pexels nella Libreria media

**Files:**
- Modify: `README.md` (nuova sezione "Foto segnaposto")
- Modify: `CLAUDE.md` (sezione Design → Foto: eccezione temporanea)

**Interfaces:**
- Produces: ID degli allegati WordPress per `apertura`, `zumba`, `pilates`, `aerofantasy`, `funzionale`, `gag`, `step`, `hiit`, `tonificazione`, `sede-camigliano`, `sede-lunata`, salvati in `scratchpad/foto.json` (`{"zumba": 31, …}`) per Task 5.

- [ ] **Step 1:** Cercare su pexels.com le candidate (gruppi di adulti che ballano/fanno fitness in palestra, luce viva), una per voce sopra; verificare la licenza attuale su pexels.com/license.
- [ ] **Step 2:** Presentare a Nicholas l'elenco: voce, link Pexels, autore, dimensione del file. **Fermarsi fino all'approvazione.**
- [ ] **Step 3:** Scaricare le approvate in una cartella nuova della scratchpad, ridimensionare a 1600 px di lato (GD di PHP), importare con `wp media import <file> --title=… --alt=… --porcelain`, salvare gli ID in `foto.json`.
- [ ] **Step 4:** Verifica: `wp post list --post_type=attachment --fields=ID,post_title` mostra 11 allegati; ogni URL `/wp-content/uploads/…` risponde 200.
- [ ] **Step 5:** README: tabella voce → autore → link Pexels → "da sostituire". CLAUDE.md: «Foto segnaposto da Pexels ammesse temporaneamente (decisione di Nicholas, 2026-11-15), solo adulti, elencate nel README».
- [ ] **Step 6:** Commit `Foto segnaposto: elenco e regola temporanea`.

### Task 2: Apertura con foto e velatura scura

**Files:**
- Modify: `tema/patterns/apertura.php`
- Modify: `tema/style.css` (pulsante outline e motto su sfondo scuro)

**Interfaces:**
- Produces: Copertina con `className: mt-apertura`, `overlayColor: contrast`, `isDark: true`; nel pattern `dimRatio: 100` (nessuna foto), in Home `dimRatio: 65` + `url`/`id` della foto (Task 5).

- [ ] **Step 1:** Calcolo di verifica (Global Constraints): bianco su `#1A1A1A` al 65% sopra foto bianca ≥ 4,5:1 (atteso ≈ 5,4:1).
- [ ] **Step 2:** Pattern: Copertina nera piena (`dimRatio 100`, `minHeight 70vh` su telefono via `minHeight`/`minHeightUnit`), testo bianco, motto con `mt-motto`, pulsante WhatsApp `is-style-outline` bianco.
- [ ] **Step 3:** Verifica nell'editor: il pattern è valido (`wp.blocks.parse` → nessun `isValid === false`).
- [ ] **Step 4:** Commit `Apertura: copertina scura pronta per la foto`.

### Task 3: Larghezza e animazioni a tema ballo

**Files:**
- Modify: `tema/style.css`

**Interfaces:**
- Consumes: classi `mt-apertura`, `mt-scheda-corso`, `mt-scheda-sede`; le griglie diventano `alignwide` in Task 5.
- Produces: `@keyframes mt-battuta` (forme decorative), `mt-passo-sx`/`mt-passo-dx` (schede alternate), `mt-tratto` (sottolineatura disegnata).

- [ ] **Step 1:** Forme decorative: `.mt-apertura::before/::after` con SVG inline (cerchi e note nei colori `corso-*`), `aria-hidden` implicito (pseudo-elementi), animazione `mt-battuta` 4 × 0,6 s.
- [ ] **Step 2:** Tratto sotto `h2`: `::after` con `transform-origin:left`, `scaleX(0→1)` legato a `view()`; senza supporto resta visibile intero.
- [ ] **Step 3:** Schede: dispari `mt-passo-sx`, pari `mt-passo-dx` (translateX ±24px + rotate ±2deg → 0), legate a `view()`.
- [ ] **Step 4:** Verifica Playwright a 1440 px e 375 px: nessuno scorrimento orizzontale (le forme non escono dalla Copertina: `overflow:hidden` su `.mt-apertura`); con `reducedMotion: reduce` `animationName` = `none` su tutti e tre.
- [ ] **Step 5:** Commit `Animazioni a tema ballo`.

### Task 4: Pattern "Come iscriversi" con il telefono

**Files:**
- Create: `tema/patterns/come-iscriversi.php`
- Modify: `tema/style.css`

**Interfaces:**
- Produces: pattern `mytime/come-iscriversi` (categoria `mytime`): Gruppo `alignwide` `className: mt-iscriversi` con Colonne (sinistra: `h2` "Come iscriversi", lista numerata dei tre passi, pulsante WhatsApp; destra: Gruppo `mt-telefono` con paragrafo intestazione `mt-telefono-testa` "ASD My Time" e paragrafi `mt-bolla mt-bolla-persona` / `mt-bolla mt-bolla-noi`).

- [ ] **Step 1:** Scrivere il pattern con i tre messaggi dei Global Constraints e i passi: «Scegli il corso e l'orario» / «Scrivici su WhatsApp o chiamaci» / «Vieni in palestra: ti aspettiamo!».
- [ ] **Step 2:** CSS: `.mt-iscriversi { view-timeline: --mt-chat block; }`; `.mt-bolla` stato finale visibile di default; dentro `@supports (animation-timeline: view())`: `.mt-bolla:nth-of-type(n)` con `animation-range: contain calc(10% + n*15%) …` (regola generica per n fino a 6); su ≥ 782 px `.mt-telefono { position: sticky; top: 15vh }` e sezione con `min-height: 140vh`.
- [ ] **Step 3:** Verifica nell'editor: pattern valido.
- [ ] **Step 4:** Verifica Review Focus con Playwright su una pagina di prova (poi cancellata) con il pattern **due volte** e un **quarto messaggio** aggiunto: a scorrimento finito tutte le bolle hanno `opacity: 1`; in Firefox (`playwright firefox`) tutte visibili subito; con `reducedMotion: reduce` tutte visibili.
- [ ] **Step 5:** Commit `Pattern Come iscriversi con il telefono animato`.

### Task 5: Pagine con foto, sezioni larghe e telefono

**Files:**
- Modify (fuori repository): `scratchpad/crea-pagine.php`

**Interfaces:**
- Consumes: `foto.json` (Task 1), pattern di Task 2 e 4, classi di Task 3.

- [ ] **Step 1:** Script: Apertura in Home con foto `apertura` (`url`, `id`, `dimRatio 65`); immagine in cima a ogni scheda corso e sede (blocco Immagine con `id`, `sizeSlug: large`, `alt`); griglie `align: wide`; Home: "Come iscriversi" dopo "I nostri corsi"; Iscrizioni e costi: "Come iscriversi" al posto di h2+paragrafo "Come iscriversi". Usa `wp_update_post` (riassunti conservati).
- [ ] **Step 2:** Eseguire lo script; verifica nell'editor: tutte le pagine valide.
- [ ] **Step 3:** Verifica pubblica: nessuna richiesta esterna, nessuna violazione CSP, prima immagine della Home con `fetchpriority="high"`, le altre `loading="lazy"`.

### Task 6: Guida, README e verifica finale

**Files:**
- Modify: `GUIDA-SOCI.md`, `README.md`

- [ ] **Step 1:** Guida: "Cambiare una foto" (Apertura: seleziona la Copertina → Sostituisci; schede: blocco Immagine → Sostituisci; solo foto con consenso) e "Cambiare i messaggi del telefono".
- [ ] **Step 2:** README: struttura (nuovo pattern), nota Firefox, foto segnaposto.
- [ ] **Step 3:** Lighthouse locale sulla Home: `npx lighthouse http://mytime.local/ --only-categories=performance,accessibility,best-practices,seo --form-factor=mobile --quiet --chrome-flags="--headless"` → Performance e Accessibilità ≥ 95 (indicativo: Local non è OVH).
- [ ] **Step 4:** Screenshot a 375 e 1440 px per Nicholas.
- [ ] **Step 5:** Commit e push `Grafica a tema ballo: foto, telefono, animazioni`.
