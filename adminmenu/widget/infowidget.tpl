<style>
.infowidget {
    display: block;
}
.infowidget-button {
    display: flex;
    justify-content: right;
}

#widget-InfoWidget .widget-title:before {
    content: "\f110";
}

</style>
<div class="infowidget">
    <span class="font-weight-bold" style="font-size: 32px; {$color_active}"><center>{$output}</center></span>
    <center><span>Bestellungen haben Status </span><span class="font-weight-bold">Pending</span></center>
    <br>
    <div class="infowidget-button">
        <a href="{$plugin_path}"><button type="button" class="btn btn-primary" style="align-self: right;">Zum Plugin</button></a>
    <div>
</div>