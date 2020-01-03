# Re-usable skeleton loader library

<p class="btn btn-outline-primary" id="load">Simulate Load</p>
        
<p>Syntax:</p>
<pre class="bg-light"><code>tamma_skeleton_loader('any_area_on_page_to_target', ['specification (img, p, article, card, table) description (.center, .large, .medium, .regular etc) '])</code></pre class="bg-light">
<p>Example:</p>
<pre class="bg-light"><code>tamma_skeleton_loader('.container-fluid', ['p.center', 'p.small_center', 'p.short_center',  'p.regular',  'p.short_center', 'p.regular', 'p.short_center', 'p.center',  'p.small_center']);</code></pre>

<p>Narrative:</p>
<p>provides an easy way to construct placeholders while making AJAX calls or waiting for an API to respond.</p>
<p><i>By: Hercules Nimley</i></p>
