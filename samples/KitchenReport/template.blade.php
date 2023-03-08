@extends('tgn-reports::layout/master')

@section('title')
    {!! $title !!}
@endsection

@section('subtitle')
    {!! $subtitle !!}
@endsection

@section('body')

	<div class="page">
        @if($title)
           <h1>Títol: {!! $title !!}</h1>
        @endif
        
        @if($subtitle)
           <h1>Subtítol: {!! $subtitle !!}</h1>
        @endif
        
        Paràmetre: {!! $param1 !!}
        
        <div class="display-1">Títols</div>
		<h1>Títol 1</h1>
        <h2>Títol 2</h2>
		<h3>Títol 3</h3>
        <h4>Títol 4</h4>
        <h5>Títol 5</h5>
        <hr class="spacer"/>
        <h1 class="display-1">Blocs de text</h1>
		
		<p>Paràgraf normal - Sint proident pariatur officia exercitation culpa nisi. Amet sint amet dolor dolore aute incididunt voluptate ipsum irure Lorem. Lorem consectetur non nulla nulla veniam aute tempor cillum excepteur occaecat cillum fugiat cillum.</p>
        <p class="text-sm">Text small - Est voluptate exercitation irure ullamco exercitation ipsum dolor occaecat dolore et nostrud eu eiusmod commodo.</p>
        <p class="text-lg">Text gran - Esse cupidatat nulla do quis aliquip sint quis magna ut ad velit laboris.</p>
        
        <blockquote>Quote - Esse cupidatat nulla do quis aliquip sint quis magna ut ad velit laboris.</blockquote>
        <div class="card">
            <h4>Card</h4>
            <p>Excepteur nostrud adipisicing elit ullamco tempor ipsum duis laboris commodo elit ipsum deserunt. Adipisicing anim minim nostrud mollit aliquip non Lorem cillum dolore reprehenderit veniam nostrud veniam. Consectetur mollit consectetur minim consectetur. Lorem esse deserunt nostrud est amet voluptate velit duis occaecat sint.</p>
        </div>
        
        
        
        <hr class="spacer"/>
       
       
       
       
        <h1 class="display-1">Llistes</h1>
		
        <h3>Llista desordenada</h3>
		<ul class="mb-3">
			<li>Llista 1</li>
			<li>Llista 1</li>
			<li>Llista 1</li>
			<li>Llista 1</li>
		</ul>
		
        
        <h3>Llista sense estil</h3>
		<ul class="unstyled mb-3">
			<li>Llista 1</li>
			<li>Llista 1</li>
			<li>Llista 1</li>
			<li>Llista 1</li>
		</ul>


        <h3>Llista ordenada</h3>
		<ol>
			<li>Llista ordenada 1</li>
			<li>Llista ordenada 1</li>
			<li>Llista ordenada 1</li>
			<li>Llista ordenada 1</li>
		</ol>
		




        <hr class="spacer"/>



        <h1 class="display-1">Taules</h1>
        <h3>Taula simple</h3>
		
        <table class="table fullwidth mb-3">
            <thead>
                <tr>
                    <th class="text-left "><div>Id.</div></th>
                    <th class="text-left "><div>Titular</div></th>
                    <th class="text-left "><div>Num.</div></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><div>Lala</div></td>
                    <td><div>Lala2</div></td>
                    <td><div>Lala4</div></td>
                </tr>
                
                <tr>
                    <td><div>Lala</div></td>
                    <td><div>Lala2</div></td>
                    <td><div>Lala4</div></td>
                </tr>
                
                <tr>
                    <td><div>Lala</div></td>
                    <td><div>Lala2</div></td>
                    <td><div>Lala4</div></td>
                </tr>
                
                <tr>
                    <td><div>Lala</div></td>
                    <td><div>Lala2</div></td>
                    <td><div>Lala4</div></td>
                </tr>
                
            </tbody>
        </table>

        <h3>Taula amb marcs</h3>
		
        <table class="table table-bordered fullwidth mb-3">
            <thead>
                <tr>
                    <th class="text-left "><div>Id.</div></th>
                    <th class="text-left "><div>Titular</div></th>
                    <th class="text-left "><div>Num.</div></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><div>Lala</div></td>
                    <td><div>Lala2</div></td>
                    <td><div>Lala4</div></td>
                </tr>
                
                <tr>
                    <td><div>Lala</div></td>
                    <td><div>Lala2</div></td>
                    <td><div>Lala4</div></td>
                </tr>
                
                <tr>
                    <td><div>Lala</div></td>
                    <td><div>Lala2</div></td>
                    <td><div>Lala4</div></td>
                </tr>
                
                <tr>
                    <td><div>Lala</div></td>
                    <td><div>Lala2</div></td>
                    <td><div>Lala4</div></td>
                </tr>
                
            </tbody>
        </table>


        <h3>Taula display</h3>
		
        <table class="table table-display mb-3">
            <thead>
                <tr>
                    <th class="text-left "><div>Id.</div></th>
                    <th class="text-left "><div>Titular</div></th>
                    <th class="text-left "><div>Num.</div></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><div>Lala</div></td>
                    <td><div>Lala2</div></td>
                    <td><div>Lala4</div></td>
                </tr>
                
                <tr>
                    <td><div>Lala</div></td>
                    <td><div>Lala2</div></td>
                    <td><div>Lala4</div></td>
                </tr>
                
                <tr>
                    <td><div>Lala</div></td>
                    <td><div>Lala2</div></td>
                    <td><div>Lala4</div></td>
                </tr>
                
                <tr>
                    <td><div>Lala</div></td>
                    <td><div>Lala2</div></td>
                    <td><div>Lala4</div></td>
                </tr>
                
            </tbody>
        </table>
        <hr class="spacer"/>

        <div class="page-break"></div>

        <h1 class="display-1">Colors</h1>
        <p>Podem fer servir les classes CSS <em>text-<code>color</code></em>, <em>bg-<code>color</code></em>, <em>border-<code>color</code></em>.
        <table class="fullwidth ">
           <tr>
                <td>
                    <ul class="unstyled">
                        <li><span class="text-primary">Primary</span></li>
                        <li><span class="text-secondary">secondary</span></li>
                        <li><span class="text-success">success</span></li>
                        <li><span class="text-info">info</span></li>
                        <li><span class="text-warning">warning</span></li>
                        <li><span class="text-danger">danger</span></li>
                        <li><span class="text-light bg-dark">light</span></li>
                        <li><span class="text-dark">dark</span></li>
                        <li><span class="text-muted">muted</span></li>
                        
                    </ul>
                </td>
                <td>
                    <ul class="unstyled">
                        <li><span class="text-indigo">indigo</span></li>
                        <li><span class="text-purple">purple</span></li>
                        <li><span class="text-pink">pink</span></li>
                        <li><span class="text-red">red</span></li>
                        <li><span class="text-orange">orange</span></li>
                        <li><span class="text-yellow">yellow</span></li>
                        <li><span class="text-green">green</span></li>
                        <li><span class="text-teal">teal</span></li>
                        <li><span class="text-cyan">cyan</span></li>
                        
                    </ul>
                </td>
                <td>
                    <ul class="unstyled">
                        <li><span class="text-white bg-dark">text-white</span></li>
                        <li><span class="text-black">text-black</span></li>
                        <li><span class="text-gray-100 bg-dark">text-gray-100</span></li>
                        <li><span class="text-gray-200 bg-dark">text-gray-200</span></li>
                        <li><span class="text-gray-300 bg-dark">text-gray-300</span></li>
                        <li><span class="text-gray-400">text-gray-400</span></li>
                        <li><span class="text-gray-500">text-gray-500</span></li>
                        <li><span class="text-gray-600">text-gray-600</span></li>
                        <li><span class="text-gray-700">text-gray-700</span></li>
                        <li><span class="text-gray-800">text-gray-800</span></li>
                        <li><span class="text-gray-900">text-gray-900</span></li>
                        
                    </ul>
                </td>
           </tr>
        </table>
        <hr class="spacer"/>
        
        <h1 class="display-1">Utilitats espaiat</h1>

        <p>Podem fer servir les classes <em>m<code>place</code>-<code>num</code></em> per afegir marges i <em>p<code>place</code>-<code>num</code></em> per paddings.</p>
        <p><code>place</code> pot ser: a, t, b, l, r, x, y  (o no definir-se)</p>
        <p><code>num</code> pot ser un numero entre 0 i 5</p>
        <p>pex: <code>mt-2</code>, <code>pb-1</code>, <code>m-0</code>, <code>px-4</code></p>
        
        <hr class="spacer"/>
        <h1 class="display-1">Grid</h1>
        <p>Podem fer servir les classes <em>col-<code>colsize</code></em> tant a DIVs com als TD dins d'una taula.</p>
        <p><code>colsize</code> pot ser un número entre 1 i 12, com a bootstrap.</p>
        <p class="text-sm text-warning">Hem d'assegurar-nos que als contenidors que hi afegim la classe col no li afegim un padding extra a través d'una altra classe.</p>
        <table class="mt-3 mb-1">
            <tr>
                <td class="col-3 bg-gray-300">
                    <div class="p-3">col-3</div>
                </td>
                <td class="col-4 bg-gray-400">
                    <div class="p-3">col-4</div>
                </td>
                <td class="col-5 bg-gray-500">
                    <div class="p-3">col-5</div>
                </td>
            </tr>

        </table>
        <table class="">
            <tr>
                <td class="col-7 bg-gray-300">
                    <div class="p-3">col-7</div>
                </td>
                <td class="col-3 bg-gray-400">
                    <div class="p-3">col-3</div>
                </td>
                <td class="col-2 bg-gray-500">
                    <div class="p-3">col-2</div>
                </td>
            </tr>

        </table>
    </div>
@endsection



@section('footer')
    Tarragona, {!! $fecha !!}
@endsection
