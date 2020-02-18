{{layout 'Common/base.tpl'}}
{{fill header}}
<?php $l=strlen($sql);?>
{{if $l<=150000}}
<link rel="stylesheet" href="/static/highlight/styles/vs.css">
    <script src="/static/highlight/highlight.pack.js"></script>
{{/if}}
{{end header}}

{{fill container}}
<script>
    var box = document.getElementById("small-menu");
    box.remove();
</script>
<pre><code class="sql" style="padding: 0!important;background-color: #f2f2f2!important;">{{$sql}}</code></pre>
{{if $l<=150000}}
    <script type="text/javascript">
        hljs.initHighlighting();
    </script>
{{/if}}
{{end container}}
