<div class="p-3 h-100 overflow-auto">
    <p>{{ __('tgn-reports::reports.Pots crear nous reports amb la comanda Artisan:') }}</p>
    <p class="bg-dark p-3 rounded">
        <code >
        php artisan make:tgn-report {report_name}
        </code>
    </p>
    <p>En trobaràs el codi font a la ruta <code>storage/app/report-templates</code></p>
    <p>Es generarà:</p>
    <ul>
        <li><code>config.php</code> Arxiu de configuració</li>
        <li><code>ReportNameReport.php</code> Classe del report</li>
        <li><code>template.blade.php</code> Plantilla Blade</li>
    </ul>

    <hr/>
    <h1>Docs</h1>
    
    <h3>Configuració</h3>
    <p>Pots definir la configuració del report a l'arxiu <code>config.php</code>.</p>
    <ul>
        <li><code>name</code> Nom del report</li>
        <li><code>icon</code> Icona fontawesome o bootstrap</li>
        <li><code>color</code> Color bootstrap o hexadecimal  </li>
        <li><code>orientations</code> Array de mides orientacions possibles (portrait o landscape)</li>
        <li><code>pagesizes</code> Array de mides possibles de pàgina (A4, A3, A5 ...)</li>
        <li><code>languages</code> Array d'idiomes possibles (ca, es, en)</li>
        <li><code>pagination</code> Activa la paginació</li>
        <li><code>margin</code> Marge del document (xs, sm, md, <u>lg</u>, xl, xxl)</li>
    </ul>
    <h3>Paràmetres</h3>
    <p>Automàticament es detectaran totes les variables PHP que hi hagi a la plantilla.</p>
    <p>Opcionalment, podem definir, a la classe del report, un array amb els paràmetres, per així especificar-ne el nom, el tipus de dada, el valor per defecte i els formatadors.</p>
<pre class="bg-dark text-light p-3 rounded"><code>protected $parameters = [

    "param_1" => [
        "type"=>"textarea",
        "label"=>"Paràmetre 1",
        "default_value"=>"Lalalala",
    ],
    "fecha" => [
        "type"=>"date",
        "label"=>"Fecha",
        "formatter"=>"formatData",
        'formatter_parameters'=>['l, d F Y']
    ]
];
</code></pre>
<h3>Formatters</h3>
<p>Quan definim un paràmetre, hi podem especificar un o N formatadors. No son més que funcions per les quals farem passar el valor del paràmetre.</p>
<p>Poden ser funcions simples, com les natives de PHP (strtoupper, nl2br...) o bé funcions definides a la nostra classe. </p>
<p>La classe BaseReport incorpora per defecte alguns formatadors :</p>

<ul>
    <li>formatData</li>
    <li>slugify</li>
</ul>
      

<h3>Paràmetres multiples</h3>
<p>Quan definim un paràmetre podem especificar que és una col·lecció.</p>
<pre class="bg-dark text-light p-3 rounded"><code>protected $parameters = [

    "files" => [
        "type"=>"collection",
        "label"=>"Files",
        "columns"=>[
            "col1"=>[
                "type"=>"number",
                "label"=>"Col 1",
            ],
            "col2"=>[
                "type"=>"number",
                "label"=>"Col 2",
            ]
        ]
    ]
];
</code></pre>
<h3>Imatges</h3>
<p>Podem definir paràmetres de tipus imatge (<code>image</code>)</p>
<p>S'hauria de passar la URL de la imatge (http o https) (una URL que sigui accessible des del servidor).</p>
<pre class="bg-dark text-light p-3 rounded"><code>protected $parameters = [

    "imatge" => [
        "type"=>"image",
        "label"=>"Imatge"
    ]
</code></pre>

<h3>Paràmetres calculats</h3>
<p>Quan definim un paràmetre, hi podem especificar una funció a través de la qual obtindrem el valor.</p>
<p>Aquesta és una funció que estarà definida a la nostra classe. </p>
<p>La resta de paràmetres estan disponibles amb l'atribut <code>$this->template_attributes</code>. </p>

<pre class="bg-dark text-light p-3 rounded"><code>protected $parameters = [

    "numero" => [
        "type"=>"number",
        "label"=>"Numero"
    ],
    "doble" => [
        "type"=>"number",
        "label"=>"Fecha",
        "function'=>'calculaElDoble'
    ]
    ...

    public function calculaElDoble(){
        return $this->template_attributes["numero"] * 2 ;
    }
</code></pre>

<p>Si el paràmetre es una columna (ja sigui d'una col·lecció o d'un report multiple) la funció rebrà com a paràmetre la fila actual. </p>
<pre class="bg-dark text-light p-3 rounded"><code>public function calculaElDoble($row){
    return $row["col1"] * 2 ;
}
</code></pre>

<h3>Multiples plantilles</h3>
    <p>
        Pot haver plantilles diferents segons la mida de pàgina, orientació i idioma.
        <br/>
        El sistema buscarà si existeix l'arxiu <code>template-{mida}-{orientacio}-{idioma}.blade.php</code>.
        <br/>
        Els sufixes poden estar tots o només algun d'ells, i l'ordre pot ser qualsevol:
    </p>
    <p><code>template-a3</code>, <code>template-es</code>, <code>template-portrait-a5</code>, <code>template-a2-landscape-es</code> ... </p>

    <p>El que si que hi ha és una precedència en cas de conflicte. És a dir si existeixen les plantilles <code>template-a3</code> i <code>template-ca</code>, s'agafarà abans la primera.</p>

<h3>Tests . Random parameters</h3>
<p>Al tester podem passar les funcions de la llibreria <a href="https://github.com/fzaninotto/Faker" target="_blank">faker</a> com a paràmetres.</p>
<p>Per exemple: <code>@word</code> o <code>@sentence</code> o <code>@numberBetween(1,10)</code> </p>      
    

</div>


