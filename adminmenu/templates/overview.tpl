{include file='tpl_inc/model_list.tpl'
    items=$orders
    includeHeader=false
    tabs=false
    select=true
    edit=false
    search=true
    delete=true}

<script>
(function () {
    // Rename all delete-related buttons to "Zurücksetzen"
    document.querySelectorAll('.btn-danger').forEach(function (btn) {
        btn.classList.remove('btn-danger');
        btn.classList.add('btn-warning');
        btn.innerHTML = btn.innerHTML
            .replace(/fa-trash[a-z-]*/g, 'fa-undo')
            .replace(/[Ll]öschen|[Dd]elete/g, 'Zurücksetzen');
    });
}());
</script>
