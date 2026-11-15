# Layout su computer: un contenitore unico e sezioni a colonne

Data: 2026-11-15 · Stato: approvato da Nicholas ("procedi")

## Perché

Su computer il sito è troppo stretto e lascia metà schermo vuota: il testo sta in una colonna di 645 px, le griglie arrivano a 1340 px, i bordi non si allineano (es. "Vedi tutti gli orari" e "Altre proposte" sotto le schede delle sedi) e la griglia delle sedi prepara 3 colonne per 2 schede.

## Obiettivi

- Un contenitore unico di **1200 px** per header, contenuto e footer (stessi bordi).
- Paragrafi e liste al massimo **70 caratteri per riga**, allineati a sinistra nel contenitore (non una colonna stretta al centro).
- Su computer ogni sezione usa lo spazio orizzontale con Colonne o Griglia; su telefono tutto in una colonna.

## Non obiettivi

- Cambiare colori, font, animazioni o testi.
- Nuovi contenuti oltre all'anteprima dei corsi in Home.

## Approcci

| | Come | Scelta |
|---|---|---|
| A | `contentSize` e `wideSize` a 1200 px in `theme.json` + tetto di 70ch ai paragrafi + sezioni a colonne con i blocchi Colonne/Griglia | **scelto**: un valore, blocchi nativi modificabili dall'editor |
| B | Testo a 645 px e solo le griglie larghe | è lo stato attuale, bocciato da Nicholas |
| C | Bande a tutta larghezza con sfondi alternati | più lavoro e più CSS, non risolve l'allineamento |

## Il progetto (computer → telefono)

- **Home**: Apertura con testo a sinistra largo al massimo 40rem (la foto si vede a destra) · "I nostri corsi": 8 schede piccole con foto, nome e sede, cliccabili verso la scheda del corso in Corsi (4 colonne) + pulsante · "Come iscriversi" invariato · "Dove ci trovi": 2 colonne piene + pulsante "Vedi tutti gli orari" · "Altre proposte": 3 schede · Avvisi: griglia a 3 colonne · "Vieni a trovarci": titolo e testo a sinistra, pulsanti a destra.
- **Corsi**: griglia a 3 colonne; ogni scheda ha un'ancora (`#zumba`, …) per i link dalla Home.
- **Sedi e orari**: per ogni palestra, scheda (foto, indirizzo) a sinistra e tabella orari a destra.
- **Iscrizioni e costi**: "Come funzionano" e "Quanto costano" affiancati, poi "Come iscriversi", documenti, invito.
- **Contatti**: pulsanti a sinistra, email e social a destra.
- **Footer**: 3 colonne (associazione · contatti · link e crediti).
- **Privacy**: una colonna di testo (70ch).

Griglie con `columnCount` + `minimumColumnWidth`: il numero di colonne scende da solo sugli schermi stretti.

## Verifica

Tutte le pagine e i pattern validi nell'editor; a 1440 px nessuna sezione con colonne vuote e bordi sinistri allineati a quelli dell'header; a 375 px una colonna, nessuno scorrimento orizzontale; nessuna richiesta esterna né violazione CSP; Lighthouse Performance e Accessibilità ≥ 95.
