<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dungeon Crawler Mini-RPG</title>
    <?= link_tag("css/misc.css"); ?>
    <?= link_tag("css/rpg-ui.css"); ?>
    <?= link_tag("css/bootstrap.min.css"); ?>
    <?= link_tag("css/bootstrap-theme.min.css"); ?>
</head>
<body>
    <div class="center-outer"><div class="center-middle">
        <div class="center-content">
            <div class="two-column pull-left units ff7">
                <div class="center-text title"><h1>Party</h1></div>
                <?php for($i = 0; $i < count($units); $i++): ?>
                <div id="unit-<?= $i ?>" class="media unit-slot" onClick="selectUnit(<?= $i; ?>);">
                    <div class="media-left media-middle">
                        <div class="media-object avatar">
                            <img src="<?= base_url('img/avatars/'.$units[$i]->sprite_name.'.gif'); ?>" />
                        </div>
                    </div>
                    <div class="media-body media-middle">
                        <div id="name-<?= $i ?>"><?= $units[$i]->name; ?></div>
                        <div id="delete-status-<?= $i ?>"></div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
            <div id="column-2" class="two-column pull-left stats ff7">
                <div class="center-text title"><h1 id="unit-name"></h1></div>
                <div class="sprite center-block"></div>
                <div class="center-block center-content unit-stats">
                    <div class="spring">
                        <input id="id" type="hidden">
                        Level: <span id="level"></span><br>
                        Exp: <span id="exp"></span>&nbsp;<span id="next-lvl"></span><br>
                        HP: <span id="hp"></span> / <span id="max_hp"></span><br>
                        Attack: <span id="atk"></span><br>
                        Defense: <span id="def"></span><br>
                        Evasion: <span id="evade"></span><br>
                        Speed: <span id="spd"></span><br>
                    </div>
                    <div class="spring">
                        <button id="heal" class="btn btn-default center-block">Heal</button><br>
                        <button id="dismiss" class="btn btn-danger center-block">Dismiss</button>
                    </div>
                </div>
            </div>
        </div>
    </div></div>
</body>
<script type="text/javascript" src="<?= base_url('js/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/base.url.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/string.extended.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/entities.js'); ?>"></script>
<script type="text/javascript">
var units;
$(document).ready(function() {
    var i, length, ave, unit;
    units = new Party();
    initBaseURL("");

    units.addAll(Unit.createAll(JSON.parse('<?= json_encode($units); ?>')));

    $("#heal").click(function() {
        var id = $("#id").val(),
            unit = units.get(id);

        unit.hp = unit.max_hp;

        controllerPost("unit/update", {
            "units": [unit.jsonify()]
        }, function(data) {
            selectUnit(id);
        });
    });

    $("#dismiss").click(function() {
        var id = $("#id").val();
            unit = units.get(id);

        unit.hp = 0; //kill unit

        controllerPost("unit/update", {
            "units": [unit.jsonify()]
        }, function(data) {
            $(String.format("#unit-{0}", id)).removeAttr("onClick");
            $(String.format("#delete-status-{0}", id)).html("Status: Dismissed");
        });
    });
});
function selectUnit(id) {
    var unit = units.get(id),
        next = controller("exp/get", "int", unit.level + 1) - unit.exp;

    $("#column-2").show();
    $("#id").val(id);
    $("#unit-name").html(unit.name);
    $("#column-2 .sprite").css("background-image", String.format("url({0})", unit.sprite("retreat")));
    $("#level").html(unit.level);
    $("#exp").html(unit.exp);
    $("#next-lvl").html(String.format("({0} to next level)", next));
    $("#hp").html(unit.hp);
    $("#max_hp").html(unit.max_hp);
    $("#atk").html(unit.attack);
    $("#def").html(unit.defense);
    $("#evade").html(unit.evasion);
    $("#spd").html(unit.speed);
    $("#heal").prop("disabled", (unit.hp === unit.max_hp));
}
</script>
</html>
