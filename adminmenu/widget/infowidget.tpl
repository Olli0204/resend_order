<style>
.infowidget {
    display: block;
}
.infowidget-count {
    display: block;
    text-align: center;
    font-size: 32px;
    font-weight: bold;
}
.infowidget-label {
    display: block;
    text-align: center;
}
.infowidget-button {
    display: flex;
    justify-content: flex-end;
}

#widget-InfoWidget .widget-title:before {
    content: "\f110";
}
</style>
<div class="infowidget">
    <span class="infowidget-count" style="{$color_active}">{$output}</span>
    <span class="infowidget-label">Bestellungen haben Status <strong>Pending</strong></span>
    <br>
    <div class="infowidget-button">
        <a href="{$plugin_path}"><button type="button" class="btn btn-primary">Zum Plugin</button></a>
    </div>
</div>
