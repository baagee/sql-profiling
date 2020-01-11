{{layout 'Common/base.tpl'}}
{{fill header}}
<link rel="stylesheet" href="/static/highlight/styles/vs.css">
<script src="/static/highlight/highlight.pack.js"></script>
{{end header}}

{{fill container}}

<pre><code class="sql">{{$sql}}</code></pre>

{{end container}}

{{fill tail}}
<script type="text/javascript">
    hljs.initHighlighting();
</script>
{{end tail}}