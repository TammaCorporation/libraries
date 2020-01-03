# Re-usable skeleton loader library

The re-usable skeleton loader library provides an easy way for developers to construct and display content placeholders to users while their  application is making an AJAX call or waiting for an API response.

## Let's get started

### Include the following:
* tamma_skeleton_loader.js
* main.css
        
### Learn the (bootstrap style) Syntax:
<pre class="bg-light"><code>tamma_skeleton_loader('any_area_on_page_to_target', ['specification (img, p, article, card, table) description (.center, .large, .medium, .regular etc) '])</code></pre>

### Beautiful live Examples:

* fullpage:
<pre class="bg-light"><code>tamma_skeleton_loader('body', ['p.center', 'img.large', 'p.short_center', 'img.regular*4', 'p.short', 'card.medium*2']);</code></pre>

* blog:
<pre class="bg-light"><code>tamma_skeleton_loader('.container', ['p.short', 'article.left', 'p.regular*4', 'p.short_center', 'table', 'p.short_center', 'img.regular*4']);</code></pre>

* bio:
<pre class="bg-light"><code>tamma_skeleton_loader('.container', ['p.center', 'p.small_center', 'p.short_center',  'p.regular',  'p.short_center', 'p.regular', 'p.short_center', 'p.center',  'p.small_center']);</code></pre>

### Contributors:
* Mr. Michael Kaiva Nimley (Hercules) - C.T.O 1/3/2020
