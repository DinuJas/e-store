# Nettbutikk prosjekt

Dette er ein skuleoppgåve eg fekk

* Konsept
* Kravspesifikasjon
* Databasemodellering (MySQL)
* Design og brukaroppleving (UX)
* Skisser
* Logikk og Flyt (PHP)


## Database setup
Du trenger:
* Git
* MySQL (server og klient)

### Steg 1: 
```bash
git clone https://github.com/DinuJas/e-store.git
cd e-store
```
### Steg 2:
Logg inn i MySQL og opprett databasen:
```bash
mysql -u root -p -e "CREATE DATABASE e-store;"
```
(Bruk rett MySQL brukar om du ikkje bruker root.)

### Steg 3:
Databasestrukturen ligger i db/schema.sql.
Kjør denne kommandoen ifrå root mappa til prosjektet
```bash
mysql -u root -p nettbutikk < db/schema.sql
```
