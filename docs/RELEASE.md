# Publicering av livebaserad version

Den färdiga webbversionen ligger i `site/`. Katalogen innehåller vanlig HTML, CSS
och JavaScript. Ingen server, databas, installation eller byggkedja behövs i produktion.
Preview- och testverktygen körs bara lokalt.

## Återstående beslut före publicering

1. Mottagarnumret **+595 992 279599** bekräftades av Anton den 5 oktober 2026
   och är aktiverat i denna leverans. Alla WhatsApp-knappar anger Dentista.com.py,
   aktuell sida och relevant tjänst/ämne. Namngiven ansvarig verksamhet och
   vem som svarar behöver fortfarande anges med verifierade uppgifter.
2. Identifiera eller säkerhetskopiera nuvarande Hostinger-installation. Ingen
   befintlig GitHub-gren matchade den publicerade sidstrukturen/koden. Denna
   leverans utgår från offentlig live-HTML och behöver därför godkännas som ny
   statisk publiceringskälla. Bevara befintliga privata filer och serverinställningar.
3. Bekräfta den ansvariga verksamheten och uppdatera kontakt-/integritetstexten
   med verifierad identitet. Lägg bara till schema för en klinik när verklig
   klinik, adress och yrkesuppgifter faktiskt är verifierade.

## Bekräftad kontakt och framtida ändringar

Det bekräftade numret är redan aktiverat. För att återskapa konfigureringen:

```text
python tools/configure-contact.py --number 595992279599 --display "+595 992 279599" --owner-confirmed
node --test tools/contact.test.cjs
python tools/activation.test.py
python tools/check-site.py
python tools/check-whatsapp.py
```

`--owner-confirmed` representerar ett faktiskt ägarbeslut, inte ett sätt att
kringgå kontrollen. Verktyget uppdaterar både JavaScript-konfigurationen och
alla statiska WhatsApp-/telefonlänkar så att de även fungerar utan JavaScript.
Det öppnar inte WhatsApp och skickar inget. Återställ med
`python tools/configure-contact.py --disable` om bekräftelsen återkallas.

## Preview och testmottagare

```text
python tools/preview.py --port 8097
python tools/preview.py --port 8098 --test-contact
```

Den första använder den riktiga lokala konfigurationen. Den andra injicerar
ett syntetiskt nummer i HTTP-svaret och ersätter alla WhatsApp-destinationer
med en lokal mottagare. Produktionsfiler ändras inte. Klicka inte på
telefonlänken i testläget; det är ingen riktig mottagare.

## Skapa paket

```text
python tools/package-site.py --output ../dentista-delivery/dentista-review.zip
```

Paketet innehåller **enbart innehållet i `site/`**, direkt på zip-roten. Git,
historisk rotversion, anteckningar, testkod och skärmbilder ingår inte. Det
levererade paketet har kontakt **aktiverad** för det bekräftade numret.
Paketet har uppdaterats efter aktiveringen; publicera efter godkänd diff och
kontrollerna nedan. PR #9 är redo för merge, inte draft.

## Hosted kontroll efter en separat godkänd publicering

- Ladda upp paketets innehåll till rätt public_html; ladda inte upp `site/`
  som en underkatalog och ladda aldrig upp hela checkouten.
- Kontrollera att rätt index.html används och att befintlig index.php/
  serverkonfiguration inte oavsiktligt fortsätter styra startsidan.
- Prova HTTPS, www → non-www, slash-redirects, en riktig 404 med status 404,
  äldre `/tratamientos/*.html` och `/zonas/*.html` mot deras relevanta mål.
- `.htaccess` är granskad som konfiguration men har inte körts i lokal Apache.
  Hostinger måste därför verifiera redirects, headers, cache och filskydd.
- Kontrollera de 31 sitemap-URL:erna, canonical, robots, bildladdning och
  WhatsApp-länkar på mobil och desktop. Bekräfta mottagaren utan att skicka
  ett produktionslead som tekniskt test.
- Avtal och hantering av data när ett meddelande faktiskt skickas ligger
  utanför det lokala meddelandeformuläret. Inget CRM är kopplat i den här versionen.

Ingen merge eller Hostinger-publicering har gjorts i detta uppdrag.
