# Grafica a tema ballo: foto, larghezza, animazioni, telefono "come iscriversi"

Data: 2026-11-15 · Stato: da approvare

## Perché

Nicholas trova il sito troppo statico: mancano foto, il contenuto sembra stretto, e il tema dell'associazione è il ballo. Vuole un sito più vivo, che mostri in modo immediato **come ci si iscrive**: si sceglie un corso e si scrive su WhatsApp. In futuro le prenotazioni potrebbero passare dal sito: non è parte di questo lavoro.

## Obiettivi

1. Foto subito, da archivi gratuiti, sostituibili via via con quelle vere dell'associazione.
2. Sezioni più larghe su computer, senza peggiorare la lettura dei paragrafi.
3. Animazioni che richiamano il ballo.
4. Una sezione "Come iscriversi" con un telefono che, mentre si scorre, mostra una conversazione su WhatsApp con l'associazione.

## Non obiettivi

- Prenotazioni o iscrizioni online, moduli, raccolta di dati (progetto futuro, richiederà di rivedere privacy e sicurezza).
- jQuery, SCSS, librerie di animazione, video o Lottie (decisione già presa: solo CSS e variabili di `theme.json`).

## Anti-obiettivi (sarebbe un fallimento anche se funziona)

- Richieste a domini esterni dalle pagine pubbliche (foto collegate da Pexels, font, script).
- Lighthouse sotto 95 per colpa delle foto o delle animazioni.
- Animazioni automatiche che durano più di 5 secondi senza modo di fermarle (WCAG 2.2.2) o che restano attive con "riduci movimento".
- Imitare il marchio WhatsApp (logo, verde e grafica dell'app): la chat usa i colori di My Time.
- Foto in cui compaiono minori.

## Vincoli

- Child theme di Twenty Twenty-Five, stile in `theme.json`, CSS in `tema/style.css`, nessun JavaScript sul sito pubblico.
- CSP del sito pubblico: `img-src 'self' data:`, quindi le foto vanno caricate sul sito.
- I soci devono poter cambiare testi e foto dall'editor.
- Le animazioni a scorrimento (`animation-timeline`) non esistono su Firefox: lì tutto resta visibile e fermo.

## Approcci considerati per il telefono

| | Come | Pro | Contro |
|---|---|---|---|
| **A, scelto** | Solo CSS legato allo scorrimento (`view-timeline`), telefono che resta fermo mentre si scorre | zero JavaScript, testi modificabili, nessun peso | su Firefox niente animazione, messaggi già visibili |
| B | ~30 righe di JavaScript che fanno partire la conversazione quando il telefono entra nello schermo | funziona ovunque | non segue lo scorrimento, aggiunge JS al sito pubblico |
| C | Video o Lottie | effetto ricco | pesante, librerie esterne bloccate dalla CSP, testi non modificabili |

Nicholas ha scelto A.

## Il progetto

### 1. Foto

- Fonte: **Pexels** (licenza: uso gratuito anche commerciale, attribuzione non obbligatoria, vietato rivendere le foto). Solo adulti.
- Prima di scaricare: elenco delle candidate (anteprima, autore, link, dimensione) da approvare.
- Le foto vanno nella **Libreria media** di WordPress, non nel repository (il repository è pubblico e la licenza Pexels non permette di ridistribuirle come raccolta). Ridimensionate a massimo 1600 px di lato; WordPress genera le versioni più piccole e i `srcset`.
- Dove:
  - **Apertura** (Home): foto di gruppo che balla, come sfondo della Copertina;
  - **Schede corso**: una foto in cima a ogni scheda (8, una per corso; GAG, HIIT GAG e Tonificazione possono condividerne di simili);
  - **Schede sede**: una foto generica di palestra per Camigliano e Lunata.
- Testo alternativo descrittivo e generico ("Gruppo di adulti durante una lezione di zumba"). Nel README, sezione nuova: elenco delle foto segnaposto con autore e link, da sostituire.
- Il `CLAUDE.md` dice "solo foto dell'associazione": si aggiunge l'eccezione temporanea decisa da Nicholas.

### 2. Apertura con foto

- La Copertina usa la foto con una velatura **nera al 65%** e testo bianco. Contrasto del testo almeno 5,3:1 anche sul punto più chiaro della foto. Il motto resta un'etichetta bianca con testo magenta scuro. Il pulsante Chiama resta magenta, WhatsApp diventa bordo bianco.
- Larghezza piena, altezza minima circa 70% dello schermo su telefono.
- La foto dell'Apertura si carica subito (`fetchpriority="high"` gestito da WordPress per la prima immagine), le altre in modalità lazy.

### 3. Larghezza

- Il testo resta a 645 px (circa 75 caratteri per riga).
- Diventano **larghe** (fino a 1340 px): la griglia dei corsi (3 colonne su computer), la griglia delle sedi, la sezione "Come iscriversi". Si usa l'allineamento "ampio" dei blocchi, già previsto dal tema.

### 4. Animazioni a tema ballo (solo CSS, spente con "riduci movimento")

- **Apertura, "battuta" iniziale**: forme colorate decorative (cerchi e note nei colori dei corsi, disegnate in SVG dentro il CSS) che saltano a tempo 4 volte e poi si fermano, in meno di 3 secondi. Non sono nel contenuto e non si leggono con il lettore di schermo.
- **Tratto magenta sotto i titoli**: si "disegna" da sinistra a destra quando il titolo entra nello schermo (legato allo scorrimento).
- **Schede di corsi e sedi**: entrano con un piccolo passo di lato alternato, a sinistra e a destra come un passo di danza, invece della semplice dissolvenza (legato allo scorrimento).
- Il titolo dell'Apertura resta fermo: è l'elemento che Google misura per la velocità (LCP).

### 5. Sezione "Come iscriversi" (nuovo pattern `mytime/come-iscriversi`)

Su computer:

```
┌──────────────────────────────── larghezza ampia ────────────────────────────────┐
│  Come iscriversi                              ┌───────────────┐                 │
│                                               │ ASD My Time   │  ← telefono     │
│  ① Scegli il corso e l'orario                 │               │    fermo mentre │
│  ② Scrivici su WhatsApp o chiamaci            │  [Ciao! …]    │    scorri       │
│  ③ Vieni in palestra: ti aspettiamo!          │    [Ciao! …]  │                 │
│                                               │  [Grazie!]    │                 │
│  [ Scrivici su WhatsApp ]                     └───────────────┘                 │
└──────────────────────────────────────────────────────────────────────────────────┘
```

Su telefono: titolo, poi il telefono (non resta fermo, i messaggi compaiono mentre passa nello schermo), poi i tre passi e il pulsante.

- Il telefono è un Gruppo con una cornice disegnata in CSS (bordo scuro arrotondato, intestazione "ASD My Time"). I messaggi sono paragrafi normali con due stili, "noi" e "persona": i soci possono cambiarne il testo dall'editor.
- Conversazione proposta (testi da far approvare, nessun dato inventato):
  1. Persona: «Ciao! Vorrei iscrivermi a Zumba a Lunata 💃»
  2. My Time: «Ciao! Zumba a Lunata è il martedì e il giovedì alle 20:00. Ti spieghiamo noi costi e documenti 😊»
  3. Persona: «Perfetto, grazie!»
- I messaggi compaiono uno dopo l'altro in base allo scorrimento della sezione (`view-timeline` sulla sezione, un intervallo diverso per ogni messaggio). Su Firefox o con "riduci movimento" sono tutti visibili.
- Il pulsante sotto è quello vero: "Scrivi su WhatsApp [DA COMPLETARE: numero]".
- Dove: **Home**, dopo "I nostri corsi"; **Iscrizioni e costi**, al posto del paragrafo "Come iscriversi".

### 6. Aggiornamenti collegati

- Pattern Scheda corso e Scheda sede: aggiungere un blocco Immagine in cima? No: un'immagine vuota nel pattern verrebbe pubblicata rotta. Il pattern resta senza foto; la guida per i soci spiega come aggiungerla (blocco Immagine in cima alla scheda). Nelle pagine attuali le foto si inseriscono direttamente.
- `GUIDA-SOCI.md`: aggiungere "Cambiare una foto" (Apertura, schede) e "Cambiare i messaggi del telefono".
- README: elenco foto segnaposto, nuovo pattern, nota su Firefox.

## Lasciato a chi costruisce

- Tempi e curve esatte delle animazioni, nei limiti sopra.
- Disegno delle forme decorative dell'Apertura.
- Scelta delle singole foto, previa approvazione di Nicholas.

## Verifica

- Tutte le pagine valide nell'editor, nessuna violazione della CSP, nessuna richiesta esterna.
- Telefono a 375 px e computer a 1440 px: nessuno scorrimento orizzontale, telefono leggibile.
- "Riduci movimento" emulato: niente animazioni, tutti i messaggi visibili.
- Lighthouse (in locale, indicativo) Performance e Accessibilità ≥ 95 sulla Home con le foto.
