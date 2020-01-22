{{layout 'Common/base.tpl'}}
{{fill header}}
<?php $l=strlen($sql);?>
{{if $l<=20000}}
<link rel="stylesheet" href="/static/highlight/styles/vs.css">
    <script src="/static/highlight/highlight.pack.js"></script>
{{/if}}
{{end header}}

{{fill container}}

<pre><code class="sql">{{$sql}}</code></pre>

{{end container}}

{{fill tail}}
{{if $l<=20000}}
    <script type="text/javascript">
        hljs.initHighlighting();
    </script>
{{/if}}
{{end tail}}