#IIS Hotel: rezervace a správa ubytování 
:
Popis varianty:
Úkolem zadání je vytvořit informační systém pro rezervace a správu ubytování v řetězci hotelů. Každý hotel má nějaké označení, pomocí kterého ho jeho zákazníci budou moci vhodně odlišit, vlastní nabídku ubytování a další atributy (např. adresa, popis, počet hvězd, hodnocení zákazníků apod.). Nabídka ubytování se skládá z různých typů pokojů lišících se cenou za noc. Pokoje mají různé vlastnosti: typ (standard, business, apod.), počet lůžek, vybavení, volitelný obrázek, apod. Uživatelé budou moci informační systém použít jak pro správu hotelů a ubytování, tak pro rezervaci pokojů a správu objednávek - a to následujícím způsobem:

    administrátor:
        spravuje uživatele
        má rovněž práva všech následujících rolí

    vlastník:
        vkládá a spravuje hotely a jejich nabídky pokojů
        může vkládat obrázky k položkám nabídek
        má rovněž práva recepčního

    recepční:
        vyřizuje rezervace (platby) pro vybrané hotely, po úhradě rezervační jistiny (pokud je vyžadována) potvrdí rezervaci a případně provede klíče od pokoje (pokud je domluveno na místě), řeší check-in/out
        má rovněž práva zákazníka

    zákazník:
        rezervuje 1 až N pokojů (zvolte vhodné omezení - např. rezervační jistina, případně vyžadovaná úhrada do určité doby před ubytováním - kontroluje a případně schvaluje/ruší recepční)
        sleduje stav jeho objednávek (přijetí, potvrzení, apod.)
        má rovněž práva (a, b) neregistrovaného návštěvníka

    neregistrovaný návštěvník:
        (a) má možnost nabídky pokojů jednotlivých hotelů
        (b) má možnost filtrovat položky nabídek dle různých vlastností (např. počet postelí, kvalita, vybavení apod.)
        může provést rezervaci 1 až N pokojů bez registrace: vyžadujte vhodné údaje (má možnost dokončit registraci a stát se zákazníkem)

Každý registrovaný uživatel má možnost editovat svůj profil.
