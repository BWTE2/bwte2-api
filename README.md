# The best Webtech2 final exam api.
HTTP Status Codes: [https://restfulapi.net/http-status-codes/](https://restfulapi.net/http-status-codes/)

Priecinok *bwte2-api* treba mat na jednej urovni s *bwte2-backend*.  
Vytvorte si novy priecinok *bwte2* do ktoreho si date aj api aj backend.
 <br/><br/>/bwte2 <br/>
|-- bwte2-api <br/>
|-- bwte2-backend <br/><br/>

!!!!POZOR NA LOMITKA!!!!!!

prilozeny obrazok nizsie vizualizuje strukturu a urovne


**REQUESTY:**

1.
`/bwte2-api/key-generator`

GET - vrati generovany unikatny kluc noveho testu

  <br/>

2.
`/bwte2-api/student-creator`

POST - prida studenta do databazy ak neexistuje
  
<br/>

3.
`/bwte2-api/lecturer-logout`

POST - odhlasi ucitela
  
<br/>

4.
`/bwte2-api/lecturer-login`

POST - prihlasi ucitela
  
<br/>

5.
`/bwte2-api/lecturer-registration`

POST - registruje ucitela

  <br/>

6.
`/bwte2-api/tests/`

GET - ucitel ziska vypis testov prihlaseneho ucitela
  
<br/>

7.
`/bwte2-api/tests/{kluc testu}`

GET - ucitel ziska informacie o konkretnom teste

POST - ucitel ulozi test

PUT - ucitel aktivuje/deaktivuje test
  <br/>


8.
`/bwte2-api/tests/{kluc testu}/questions`

GET - (student) ziska  otazky bez odpovedi konkretneho testu


  <br/>

9.
`/bwte2-api/tests/{kluc testu}/export`

POST - ucitel exportuje vysledky konkretneho testu (vsetkych studentov) do csv

  <br/>

10.
`/bwte2-api/tests/{kluc testu}/students/`

GET - ucitel ziska vypis vsetkych studentov ktori pisali test

  <br/>
  

11.
`/bwte2-api/tests/{kluc testu}/students/{id studenta}`

GET - ucitel ziska odpovede konkretneho studenta na konkretny test

POST - student ukonci svoj test a odosle odpovede

PUT - ucitel upravi bodove hodnotenie konkretneho studenta pri konkretnom teste
  
  <br/>
  

12.
`/bwte2-api/tests/{kluc testu}/students/{id studenta}/export`

POST - ucitel exportuje odpovede konkretneho studenta pri konkretnom teste do pdf

  <br/>


**SSE:**

1.
`/bwte2-api/tests/{kluc testu}/timer`

-priebezne vracia ostavajuci cas konkretneho testu od jeho spustenia u studenta
  <br/>

2.
`/bwte2-api/tests/{kluc testu}/activities`

-priebezne vracia stav ("v teste", "mimo test", "dokoncil test") vsetkych studentov ktori sa zapojili do testu
  
<br/>


![alt text](./documentation-resources/wte2-api.png)
