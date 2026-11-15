# Layout su computer Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans. Steps use checkbox (`- [ ]`) syntax.

**Goal:** Contenitore unico di 1200 px e sezioni a colonne su computer, una colonna su telefono.

**Architecture:** `theme.json` porta `contentSize`/`wideSize` a 1200px; `style.css` limita paragrafi e liste a 70ch allineati a sinistra; pattern Invito e footer a colonne; le pagine si rigenerano con `strumenti/crea-pagine.php` (versionato, solo per il sito locale) usando blocchi Colonne e Griglia nativi.

**Tech Stack:** WordPress 7.1 blocchi core (Colonne, Gruppo griglia, Query), wp-cli di Local, Playwright.

**Spec:** `docs/superpowers/specs/2026-11-15-layout-desktop-design.md`

## Global Constraints

- Larghezza contenitore 1200px (`contentSize` = `wideSize`); paragrafi e liste diretti del contenuto max 70ch, bordo sinistro = bordo del contenitore.
- Testo dell'Apertura max 40rem.
- Griglie: corsi `columnCount 3` / `18rem`; anteprima corsi `4` / `14rem`; sedi `2` / `20rem`; altre proposte `3` / `16rem`; avvisi `3`.
- Niente colori fissi, niente JavaScript, niente richieste esterne; riassunti delle pagine conservati.
- Le pagine rigenerate mantengono gli stessi testi, foto e orari di oggi.

## Review Focus

- Pagina con una sola sede o un solo avviso: nessuna colonna vuota che sbilanci la sezione → verifica in Task 3 (griglie con `minimumColumnWidth`, mai colonne fisse).
- Schermo intermedio (tablet 768–1024 px): niente schede strizzate sotto i 14rem → verifica a 900 px in Task 3.
- Link "Zumba" dall'anteprima: deve arrivare alla scheda giusta (`#zumba`) → verifica in Task 3.
- Socio che inserisce il pattern Invito in un'altra pagina: resta valido e a colonne → verifica in Task 2.
- Paragrafi dentro colonne/schede: il tetto di 70ch non deve spostarli → verifica in Task 1.

---

### Task 1: Contenitore e misura del testo
**Files:** Modify `tema/theme.json`, `tema/style.css`
- [ ] Verifica (prima rossa): a 1440 px il bordo sinistro di titolo del sito, primo `h2` del contenuto e testo del footer coincidono (±2 px); i `p` diretti del contenuto sono larghi ≤ 70ch; i `p` dentro le schede non hanno margine sinistro aggiunto.
- [ ] `contentSize`/`wideSize` 1200px; regola 70ch con margine sinistro `max(0px, (100% - content-size)/2)` solo per `p, ul, ol` figli diretti di `.wp-block-post-content`; testo dell'Apertura max 40rem.
- [ ] Verifica verde, commit.

### Task 2: Pattern Invito e footer a colonne
**Files:** Modify `tema/patterns/invito-contatti.php`, `tema/parts/footer.html`
- [ ] Verifica (prima rossa): il pattern e il footer contengono `wp:columns`; validi nell'editor.
- [ ] Invito: colonne (titolo, testo, email/Instagram | pulsanti in verticale). Footer: 3 colonne.
- [ ] Verifica verde, commit.

### Task 3: Pagine a colonne
**Files:** Create `strumenti/crea-pagine.php`
- [ ] Verifica (prima rossa): Home con anteprima di 8 corsi linkati a `/corsi/#…`, sedi a 2 colonne, altre proposte a 3, avvisi a 3; Corsi con ancore; Sedi e orari con colonne scheda|tabella; Iscrizioni con colonne; Contatti con colonne; riassunti conservati.
- [ ] Script che rigenera le 6 pagine con `wp_update_post` (stessi testi e foto di oggi).
- [ ] Verifiche: editor valido; 1440/900/375 px senza scorrimento orizzontale né colonne vuote; link `#zumba`; nessuna richiesta esterna/CSP.
- [ ] Commit.

### Task 4: README, guida, verifica finale
- [ ] README (contenitore, script), guida se cambiano i passi; Lighthouse; screenshot; commit e push; revisione finale.
